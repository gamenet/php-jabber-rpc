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
     * @param string $user
     *
     * @return bool
     */
    public function isExist($user)
    {
        $response = $this->sendRequest(
            'check_account',
            [
                'user' => $user,
                'host' => $this->host
            ]
        );

        return $response['res'] == 0;
    }

    /**
     * @param string $user
     * @param string $password
     */
    public function setPassword($user, $password)
    {
        $this->sendRequest(
            'change_password',
            [
                'host'    => $this->host,
                'user'    => $user,
                'newpass' => $password
            ]
        );
    }

    /**
     * @param string $user
     * @param string $nickname
     */
    public function setVcardNickname($user, $nickname)
    {
        $this->sendRequest(
            'set_nickname',
            [
                'host'     => $this->host,
                'user'     => $user,
                'nickname' => $nickname
            ]
        );
    }

    /**
     * @param string $user
     *
     * @return string
     */
    public function getLastActivity($user)
    {
        $response = $this->sendRequest(
            'get_last',
            [
                'host' => $this->host,
                'user' => $user,
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
     * @param string $user
     * @param string $show Valid values are: away, chat, dnd, xa
     * @param string $status Text message
     * @param int $priority The value MUST be an integer between -128 and +127
     */
    public function setStatus($user, $show, $status, $priority)
    {
        $priority = (string) $priority;
        $stanza = "
            <presence>
                <show>$show</show>
                <status>$status</status>
                <priority>$priority</priority>
            </presence>";

        $this->sendStanza($user, $stanza);
    }

    /**
     * @param string $user
     *
     * @return array [['connection', 'ip', 'port', 'priority', 'node', 'uptime', 'status', 'resource', 'statustext'], [], ...]
     */
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

    public function sendStanza($user, $stanza)
    {
        $sessions = $this->getUserSessions($user);
        foreach ($sessions as $session) {
            $this->sendRequest(
                'send_stanza_c2s',
                [
                    'host' => $this->host,
                    'user' => $user,
                    'resource' => $session['resource'],
                    'stanza' => $stanza,
                ]
            );
        }
    }

    /**
     * @param string $user
     * @param string $contact
     * @param array $groups
     */
    public function setRosterUserGroup($user, $contact, array $groups)
    {
        $jid = "$contact@$this->host";
        $group = '';
        foreach ($groups as $group) {
            $group .= "<group>$group</group>";
        }

        $stanza = "
            <iq type=\"set\" id=\"ab48a\">
                <query xmlns=\"jabber:iq:roster\">
                    <item jid=\"$jid\">$group</item>
                </query>
            </iq>";

        $this->sendStanza($user, $stanza);
    }
}
 