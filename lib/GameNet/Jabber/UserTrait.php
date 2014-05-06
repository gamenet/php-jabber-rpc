<?php
namespace GameNet\Jabber;

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
}
 