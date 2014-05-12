<?php
namespace GameNet\Jabber\Mixins;

/**
 * Class RosterTrait
 *
 * @category  GGS
 * @package   GameNet\Jabber
 * @copyright Copyright (с), Syncopate Limited and/or affiliates. All rights reserved.
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