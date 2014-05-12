<?php
namespace GameNet\Jabber\Mixins;

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
     * @param string $groupId
     * @param string $name
     * @param string $description
     */
    function createGroup($groupId, $name, $description = '')
    {
        $this->sendRequest(
            'srg_create',
            [
                'host'        => $this->host,
                'group'       => (string) $groupId,
                'name'        => $name,
                'description' => $description,
            ]
        );
    }

    /**
     * @param string $groupId
     */
    function deleteGroup($groupId)
    {
        $this->sendRequest(
            'srg_delete',
            [
                'host'  => $this->host,
                'group' => $groupId,
            ]
        );
    }

    /**
     * @param string $groupId
     *
     * @return array ['jid1', 'jid2', ...]
     */
    function getGroupMembers($groupId)
    {
        $response = $this->sendRequest(
            'srg_get_members',
            [
                'host'  => $this->host,
                'group' => $groupId,
            ]
        );

        if (!isset($response['members']) || empty($response['members'])) {
            return [];
        }

        $members = [];
        foreach ($response['members'] as $member) {
            $members[] = $member['member'];
        }

        return $members;
    }

    /**
     * @param string $user
     * @param string $groupId
     */
    function addUserToGroup($user, $groupId)
    {
        return $this->sendRequest(
            'srg_user_add',
            [
                'user'      => $user,
                'host'      => $this->host,
                'group'     => $groupId,
                'grouphost' => $this->host,
            ]
        );
    }

    /**
     * @param string $user
     * @param string $groupId
     */
    function removeUserFromGroup($user, $groupId)
    {
        return $this->sendRequest(
            'srg_user_del',
            [
                'user'      => $user,
                'host'      => $this->host,
                'group'     => $groupId,
                'grouphost' => $this->host,
            ]
        );
    }

    /**
     * @return array ['group1', 'group2', ...]
     */
    function getSharedGroups()
    {
        $response = $this->sendRequest(
            'srg_list',
            ['host' => $this->host]
        );

        if (!isset($response['groups']) || empty($response['groups'])) {
            return [];
        }

        $sharedGroups = [];
        foreach ($response['groups'] as $group) {
            $sharedGroups[] = $group['id'];
        }

        return $sharedGroups;
    }
} 