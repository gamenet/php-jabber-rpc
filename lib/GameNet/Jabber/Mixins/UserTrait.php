<?php
namespace GameNet\Jabber\Mixins;

use GameNet\Jabber\Vcard;

/**
 * Class UserTrait
 *
 * @category  GGS
 * @package   GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
 * @author    Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version   1.0
 */
trait UserTrait
{
    /**
     * @param string $user
     * @param string $password
     * @return bool
     */
    public function createUser($user, $password)
    {
        $response = $this->sendRequest(
            'register',
            [
                'host'     => $this->host,
                'user'     => $user,
                'password' => $password
            ]
        );

        return $response['res'] == 0;
    }

    /**
     * @param string $username
     *
     * @return bool
     */
    public function isExist($username)
    {
        $response = $this->sendRequest(
            'check_account',
            [
                'user' => $username,
                'host' => $this->host
            ]
        );

        return $response['res'] == 0;
    }

    /**
     * @param string $username
     * @param string $password
     */
    public function setPassword($username, $password)
    {
        $this->sendRequest(
            'change_password',
            [
                'host'    => $this->host,
                'user'    => $username,
                'newpass' => $password
            ]
        );
    }

    /**
     * @param string $username
     * @param string $nickname
     */
    public function setVcardNickname($username, $nickname)
    {
        $this->sendRequest(
            'set_nickname',
            [
                'host'     => $this->host,
                'user'     => $username,
                'nickname' => $nickname
            ]
        );
    }

    /**
     * @param string $username
     *
     * @return string
     */
    public function getLastActivity($username)
    {
        $response = $this->sendRequest(
            'get_last',
            [
                'host' => $this->host,
                'user' => $username,
            ]
        );

        return $response['last_activity'];
    }

    /**
     * @param string $fromJid
     * @param string $toJid
     * @param string $message
     */
    public function sendMessage($fromJid, $toJid, $message)
    {
        $this->sendRequest(
            'send_message_chat',
            [
                'from' => $fromJid,
                'to'   => $toJid,
                'body' => $message
            ]
        );
    }

    /**
     * @param string $user
     */
    public function deleteUser($user)
    {
        $this->sendRequest(
            'unregister',
            [
                'host' => $this->host,
                'user' => $user,
            ]
        );
    }

    /**
     * UNDONE: After executing status does not change. Requires additional research
     *
     * @param string $user
     * @param string $resource
     * @param string $type Valid values: unavailable, subscribe, subscribed, unsubscribe, unsubscribed, probe, error
     * @param string $show Valid values are: away, chat, dnd, xa
     * @param string $status Text message
     * @param int $priority The value MUST be an integer between -128 and +127
     */
    public function setPresence($user, $resource, $type, $show, $status, $priority)
    {
        $this->sendRequest(
            'set_presence',
            [
                'host' => $this->host,
                'resource' => $resource,
                'type' => $type,
                'user' => $user,
                'show' => (string)$show,
                'status' => (string)$status,
                'priority' => (string)$priority,
            ]
        );
    }

    public function getUserSessions($user)
    {
        $response = $this->sendRequest(
            'user_sessions_info',
            [
                'host' => $this->host,
                'user' => $user,
            ]
        );

        if (!isset($response['sessions_info']) || empty($response['sessions_info'])) {
            return [];
        }

        $sessions = [];
        foreach ($response['sessions_info'] as $info) {
            $session = [];
            foreach ($info['session'] as $data) {
                foreach ($data as $key => $value) {
                    $session[$key] = $value;
                }
            }
            $sessions[] = $session;
        }

        return $sessions;
    }

    /**
     * @param string $user
     * @param string $name
     *
     * @return string
     */
    public function getVcardField($user, $name)
    {
        if (strstr($name, ' ')) {
            $command = 'get_vcard2';
            list($name, $subname) = explode(' ', $name);

            $params = [
                'host' => $this->host,
                'user' => $user,
                'name' => $name,
                'subname' => $subname,
            ];
        } else {
            $command = 'get_vcard';
            $params = [
                'host' => $this->host,
                'user' => $user,
                'name' => $name,
            ];
        }

        try {
            $response = $this->sendRequest($command, $params);
        } catch (\RuntimeException $e) {
            return '';
        }

        return $response['content'];
    }

    /**
     * @param string $user
     * @param string $name
     * @param string $value
     */
    public function setVcardField($user, $name, $value)
    {
        if (strstr($name, ' ')) {
            $command = 'set_vcard2';
            list($name, $subname) = explode(' ', $name);

            $params = [
                'host' => $this->host,
                'user' => $user,
                'name' => $name,
                'subname' => $subname,
                'content' => $value,
            ];
        } else {
            $command = 'set_vcard';
            $params = [
                'host' => $this->host,
                'user' => $user,
                'name' => $name,
                'content' => $value,
            ];
        }

        $this->sendRequest($command, $params);
    }

    /**
     * This method destroy session and set random password
     *
     * @param string $user
     * @param string $reason
     */
    public function banAccount($user, $reason)
    {
        $this->sendRequest(
            'ban_account',
            [
                'host' => $this->host,
                'user' => $user,
                'reason' => $reason,
            ]
        );
    }
}
 