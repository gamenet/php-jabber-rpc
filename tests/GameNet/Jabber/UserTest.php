<?php

/**
 * Class UserTest
 *
 * @package tests\GameNet\Jabber
 * @copyright Copyright (с) 2015, GameNet. All rights reserved.
 * @author Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version 1.0
 */
class UserTest extends  PHPUnit_Framework_TestCase
{
    private $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder('\GameNet\Jabber\RpcClient')
            ->disableOriginalConstructor()
            ->setMethods(['sendRequest'])
            ->getMock();
    }

    public function testCreateUser()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('register'));

        $this->mock->createUser('user', 'password');
    }

    public function testIsExist()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('check_account'));

        $this->mock->isExist('user');
    }

    public function testSetPassword()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('change_password'));

        $this->mock->setPassword('user', 'password');
    }

    public function testSetVcardNickname()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('set_nickname'));

        $this->mock->setVcardNickname('user', 'nickname');
    }

    public function testLetLastActivity()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('get_last'));

        $this->mock->getLastActivity('user');
    }

    public function testSendMessage()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('send_message_chat'));

        $this->mock->sendMessage('from', 'to', 'body');
    }

    public function testDeleteUser()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('unregister'));

        $this->mock->deleteUser('user');
    }

    public function testSetStatus()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest');

        $this->mock->setStatus('user', 'show', 'status', 'priority');
    }

    public function testGetUserSessions()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('user_sessions_info'));

        $this->mock->getUserSessions('user');
    }

    public function testGetVcardFieldSimple()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('get_vcard'));

        $this->mock->getVcardField('user', 'name');
    }

    public function testGetVcardFieldExtra()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('get_vcard2'));

        $this->mock->getVcardField('user', 'extra name');
    }

    public function testSetVcardFieldSimple()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('set_vcard'));

        $this->mock->setVcardField('user', 'name', 'value');
    }

    public function testSetVcardFieldExtra()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('set_vcard2'));

        $this->mock->setVcardField('user', 'extra name', 'value');
    }

    public function testBanAccount()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('ban_account'));

        $this->mock->banAccount('user', 'reason');
    }

    public function testSetRosterUserGroup()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest');

        $this->mock->setRosterUserGroup('user', 'contact', ['group']);
    }

    public function testSendStanza()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest');

        $this->mock->sendStanza('user', 'stanza');
    }
}
 