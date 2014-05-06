<?php
namespace GameNet\Jabber;

/**
 * Class RoomTrait
 *
 * @category  GGS
 * @package   GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
 * @author    Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version   1.0
 */
trait RoomTrait
{
    /**
     * @param string $name
     */
    public function createRoom($name)
    {
        return $this->sendRequest(
            'create_room',
            [
                'name'    => $name,
                'service' => $this->host,
                'host'    => $this->host,
            ]
        );
    }

    /**
     * @param string $name
     */
    public function deleteRoom($name)
    {
        $this->sendRequest(
            'destroy_room',
            [
                'name'    => $name,
                'service' => $this->host,
                'host'    => $this->host,
            ]
        );
    }

    /**
     * @return mixed
     */
    public function getRooms()
    {
        return $this->sendRequest(
            'muc_online_rooms',
            ['host' => $this->host]
        );
    }
} 