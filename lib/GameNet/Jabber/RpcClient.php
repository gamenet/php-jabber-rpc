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
        $context = stream_context_create(
            [
                'http' => [
                    'method'  => "POST",
                    'header'  => "User-Agent: XMLRPC::Client mod_xmlrpc\r\n" . "Content-Type: text/xml\r\n",
                    'content' => $request
                ]
            ]
        );

        $file = file_get_contents($this->server, false, $context);
        $response = xmlrpc_decode($file);

        if (xmlrpc_is_fault($response)) {
            throw new \RuntimeException("Error execution command $command with parameters " . var_export($params, true));
        }

        return $response;
    }
}
 