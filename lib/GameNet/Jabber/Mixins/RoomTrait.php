<?php
namespace GameNet\Jabber\Mixins;

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
        $this->sendRequest(
            'create_room',
            [
                'name'    => $name,
                'service' => 'conference.' . $this->host,
                'host'    => $this->host,
            ]
        );
    }

    /**
     * @param string $name
     * @param string $password
     * @param string $reason
     * @param array  $users Users JIDs
     */
    public function sendInviteToRoom($name, $password, $reason, array $users)
    {
        $this->sendRequest(
            'send_direct_invitation',
            [
                'room'     => $name . '@conference.' . $this->host,
                'password' => $password,
                'reason'   => $reason,
                'users'    => join(':', $users),
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
                'service' => 'conference.' . $this->host,
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

    /**
     * @param string $name
     * @param string $option Valid values:
     *                       title (string)
     *                       password (string)
     *                       password_protected (bool)
     *                       anonymous (bool)
     *                       max_users (int)
     *                       allow_change_subj (bool)
     *                       allow_query_users (bool)
     *                       allow_private_messages (bool)
     *                       public (bool)
     *                       public_list (bool)
     *                       persistent (bool)
     *                       moderated (bool)
     *                       members_by_default (bool)
     *                       members_only (bool)
     *                       allow_user_invites (bool)
     *                       logging (bool)
     * @param string $value
     */
    public function setRoomOption($name, $option, $value)
    {
        $value = !is_bool($value) ? $value : ($value ? 'true' : 'false');

        $this->sendRequest(
            'change_room_option',
            [
                'name'    => $name,
                'service' => 'conference.' . $this->host,
                'option'  => $option,
                'value'   => (string) $value,
            ]
        );
    }

    /**
     * @param string $name
     * @param string $userJid
     * @param string $affiliation Valid values: outcast, none, member, admin, owner
     *                            If the affiliation is 'none', the action is to remove
     */
    public function setRoomAffiliation($name, $userJid, $affiliation)
    {
        $this->sendRequest(
            'set_room_affiliation',
            [
                'name'        => $name,
                'service'     => 'conference.' . $this->host,
                'jid'         => $userJid,
                'affiliation' => $affiliation,
            ]
        );
    }
} 