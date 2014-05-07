<?php

/**
 * Class GroupTest
 *
 * @category GGS 
 * @package tests\GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
 * @author Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version 1.0
 */
class GroupTest extends  PHPUnit_Framework_TestCase
{
    private $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder('\GameNet\Jabber\RpcClient')
            ->disableOriginalConstructor()
            ->setMethods(['sendRequest'])
            ->getMock();
    }

    public function testCreateGroup()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('srg_create'));

        $this->mock->createGroup('groupId', 'name', 'description');
    }

    public function testDeleteGroup()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('srg_delete'));

        $this->mock->deleteGroup('groupId');
    }

    public function testGetGroupMembers()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('srg_get_members'));

        $this->mock->getGroupMembers('groupId');
    }

    public function testAddUserToGroup()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('srg_user_add'));

        $this->mock->addUserToGroup('groupId', 'name');
    }

    public function testRemoveUserFromGroup()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('srg_user_del'));

        $this->mock->removeUserFromGroup('groupId', 'name');
    }

    public function testGetSharedGroups()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('srg_list'));

        $this->mock->getSharedGroups();
    }
}
 