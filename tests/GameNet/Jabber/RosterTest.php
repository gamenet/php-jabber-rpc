<?php

/**
 * Class RosterTest
 *
 * @category GGS 
 * @package tests\GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
 * @author Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version 1.0
 */
class RosterTest extends  PHPUnit_Framework_TestCase
{
    private $mock;

    public function setUp()
    {
        $this->mock = $this->getMockBuilder('\GameNet\Jabber\RpcClient')
            ->disableOriginalConstructor()
            ->setMethods(['sendRequest'])
            ->getMock();
    }

    public function testGetRosterContacts()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('get_roster'));

        $this->mock->getRosterContacts('user');
    }

    public function testAddRosterContact()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('add_rosteritem'));

        $this->mock->addRosterContact('user', 'contact', 'nickname');
    }

    public function testRemoveRosterContact()
    {
        $this->mock->expects($this->once())
            ->method('sendRequest')
            ->with($this->equalTo('delete_rosteritem'));

        $this->mock->removeRosterContact('user', 'contact');
    }
}
 