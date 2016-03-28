<?php

use GameNet\Jabber\RpcClient;

/**
 * Class RpcClientTest
 *
 * @package tests\GameNet\Jabber
 * @copyright Copyright (с) 2016, GameNet. All rights reserved.
 * @author Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version 1.0
 */
class RpcClientTest extends  PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider invalidCredentials
     * @expectedException InvalidArgumentException
     */
    public function testInvalidCredentials($username, $password)
    {
        $this->getClient(['username' => $username, 'password' => $password]);
    }

    public function invalidCredentials()
    {
        return [
            ['username' => 'username', 'password' => ''],
            ['username' => '', 'password' => 'password'],
        ];
    }

    private function getClient(array $options = [])
    {
        return new RpcClient(['server' => 'test', 'host' => 'test'] + $options);
    }
}
