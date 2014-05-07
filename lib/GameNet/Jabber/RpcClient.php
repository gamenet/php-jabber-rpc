<?php
namespace GameNet\Jabber;

/**
 * Class RpcClient
 *
 * @category  GGS
 * @package   GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
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
    const VCARD_AVATAR_URL = 'PHOTO_URL';

    protected $server;
    protected $host;

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
        $this->debug = isset($options['debug']) ? (bool)$options['debug'] : false;
    }

    protected function sendRequest($command, array $params)
    {
        $request = xmlrpc_encode_request($command, $params, ['encoding' => 'utf-8']);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->server);
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 5);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $request);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: GameNet', 'Content-Type: text/xml']);
        $response = curl_exec($ch);
        curl_close($ch);

        $xml = xmlrpc_decode($response);
        if (!$xml || xmlrpc_is_fault($xml)) {
            throw new \RuntimeException("Error execution command '$command'' with parameters " . var_export($params, true) . ". Response: $response");
        }

        if ($this->debug) {
            var_dump($command, $params, $response);
        }

        return $xml;
    }
}
 