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
 * Class RosterTrait
 *
 * @package   GameNet\Jabber
 * @copyright Copyright (с) 2014, GameNet. All rights reserved.
 * @author    Vadim Sabirov <vadim.sabirov@syncopate.ru>
 * @version   1.0
 */
trait RosterTrait
{
    /**
     * @param string $user
     *
     * @return array [['jid', 'nick', 'subscription', 'ask', 'group'], [], ...]
     */
    public function getRosterContacts($user)
    {
        $response = $this->sendRequest(
            'get_roster',
            [
                'user' => $user,
                'host' => $this->host,
            ]
        );

        if (!isset($response['contacts']) || empty($response['contacts'])) {
            return [];
        }

        $rosterContacts = [];
        foreach ($response['contacts'] as $item) {
            $contact = [];
            foreach ($item['contact'] as $data) {
                foreach ($data as $key => $value) {
                    $contact[$key] = $value;
                }
            }
            $rosterContacts[] = $contact;
        }

        return $rosterContacts;
    }

    /**
     * @param string $user
     * @param string $contact
     * @param string $nickname
     * @param string $group
     * @param string $subs Available: none, from, to or both
     */
    public function addRosterContact($user, $contact, $nickname, $group = '', $subs = 'both')
    {
        $this->sendRequest(
            'add_rosteritem',
            [
                'localuser'   => $user,
                'localserver' => $this->host,
                'user'        => $contact,
                'server'      => $this->host,
                'nick'        => $nickname,
                'group'       => $group,
                'subs'        => $subs,
            ]
        );
    }

    /**
     * @param string $user
     * @param string $contact
     */
    public function removeRosterContact($user, $contact)
    {
        $this->sendRequest(
            'delete_rosteritem',
            [
                'localuser'   => $user,
                'localserver' => $this->host,
                'user'        => $contact,
                'server'      => $this->host,
            ]
        );
    }
} 