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
namespace GameNet\Jabber\Mixins;

/**
 * Class GroupTrait
 *
 * @package   GameNet\Jabber
 * @copyright Copyright (с) 2014, GameNet. All rights reserved.
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