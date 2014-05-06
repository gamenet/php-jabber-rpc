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
    use UserTrait;
    use GroupTrait;
    use RoomTrait;
    use RosterTrait;

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
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['User-Agent: XMLRPC::Client mod_xmlrpc', 'Content-Type: text/xml']);
        $response = curl_exec($ch);
        curl_close($ch);

        $response = xmlrpc_decode($response);
        if (xmlrpc_is_fault($response)) {
            throw new \RuntimeException("Error execution command $command with parameters " . var_export($params, true));
        }

        return $response;
    }
}
 