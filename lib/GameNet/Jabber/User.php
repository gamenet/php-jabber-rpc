<?php
namespace GameNet\Jabber;

/**
 * Class User
 *
 * @category  GGS
 * @package   GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
 * @author    Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version   1.0
 */
class User extends RpcClient
{
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

        if (isset($response['res'])) {
            return true;
        }

        return false;
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
     * @return mixed
     */
    public function getLastActivity($username)
    {
        $result = $this->sendRequest(
            'get_last',
            [
                'host' => $this->host,
                'user' => $username,
            ]
        );

        return $result;
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
}
 