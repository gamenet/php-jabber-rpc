<?php
namespace GameNet\Jabber;

/**
 * Class GroupTrait
 *
 * @category  GGS
 * @package   GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
 * @author    Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version   1.0
 */
trait GroupTrait
{
    /**
     * @param string $group
     * @param string $name
     * @param string $description
     */
    function createGroup($group, $name, $description = '')
    {
        $this->sendRequest(
            'srg_create',
            [
                'host'        => $this->host,
                'group'       => $group,
                'name'        => $name,
                'description' => $description,
                'display'     => 'true'
            ]
        );
    }

    /**
     * @param string $group
     */
    function deleteGroup($group)
    {
        $this->sendRequest(
            'srg_delete',
            [
                'host'  => $this->host,
                'group' => $group,
            ]
        );
    }

    /**
     * @param string $group
     *
     * @return mixed
     */
    function getGroupMembers($group)
    {
        return $this->sendRequest(
            'srg_get_members',
            [
                'host'  => $this->host,
                'group' => $group,
            ]
        );
    }

    /**
     * @param string $user
     * @param string $group
     */
    function addUserToGroup($user, $group)
    {
        $this->sendRequest(
            'srg_user_add',
            [
                'user'      => $user,
                'host'      => $this->host,
                'group'     => $group,
                'grouphost' => $this->host,
            ]
        );
    }

    /**
     * @param string $user
     * @param string $group
     */
    function removeUserFromGroup($user, $group)
    {
        $this->sendRequest(
            'srg_user_del',
            [
                'user'      => $user,
                'host'      => $this->host,
                'group'     => $group,
                'grouphost' => $this->host,
            ]
        );
    }
} 