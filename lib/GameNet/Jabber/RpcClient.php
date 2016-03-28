<?php
/**
 * The MIT License
 *
 * Copyright (c) 2014, GameNet
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 *
 * PHP version 5.4
 *
 * @package GameNet\Jabber
 * @copyright 2014, GameNet
 * @author Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @license MIT http://opensource.org/licenses/MIT
 */
namespace GameNet\Jabber;

use fXmlRpc\Client;
use fXmlRpc\Serializer\NativeSerializer;

/**
 * Class RpcClient
 *
 * @package   GameNet\Jabber
 * @copyright Copyright (с) 2014, GameNet. All rights reserved.
 * @author    Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version   1.0
 */
class RpcClient
{
    use Mixins\UserTrait;
    use Mixins\GroupTrait;
    use Mixins\RoomTrait;
    use Mixins\RosterTrait;

    const VCARD_FULLNAME = 'FN';
    const VCARD_NICKNAME = 'NICKNAME';
    const VCARD_BIRTHDAY = 'BDAY';
    const VCARD_EMAIL = 'EMAIL USERID';
    const VCARD_COUNTRY = 'ADR CTRY';
    const VCARD_CITY = 'ADR LOCALITY';
    const VCARD_DESCRIPTION = 'DESC';
    const VCARD_AVATAR_URL = 'EXTRA PHOTOURL';

    /**
     * @var string
     */
    protected $server;
    /**
     * @var string
     */
    protected $host;
    /**
     * @var bool
     */
    protected $debug;
    /**
     * @var string
     */
    protected $username;
    /**
     * @var string
     */
    protected $password;

    public function __construct(array $options)
    {
        if (!isset($options['server'])) {
            throw new \InvalidArgumentException("Parameter 'server' is not specified");
        }

        if (!isset($options['host'])) {
            throw new \InvalidArgumentException("Parameter 'host' is not specified");
        }

        $this->server = $options['server'];
        $this->host = $options['host'];
        $this->username = isset($options['username']) ? $options['username'] : '';
        $this->password = isset($options['password']) ? $options['password'] : '';
        $this->debug = isset($options['debug']) ? (bool)$options['debug'] : false;

        if ($this->username && !$this->password) {
            throw new \InvalidArgumentException("Password cannot be empty if username was defined");
        }
        if (!$this->username && $this->password) {
            throw new \InvalidArgumentException("Username cannot be empty if password was defined");
        }
    }

    protected function sendRequest($command, array $params)
    {
        $client = new Client($this->server, null, null, new NativeSerializer());

        if ($this->username && $this->password) {
            $params = [
                ['user' => $this->username, 'server' => $this->server, 'password' => $this->password], $params
            ];
        }

        $result = $client->call($command, $params);

        if ($this->debug) {
            var_dump($command, $client->getPrependParams(), $client->getAppendParams(), $result);
        }

        return $result;
    }
}
