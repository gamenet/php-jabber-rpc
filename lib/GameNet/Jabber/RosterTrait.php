<?php
namespace GameNet\Jabber;

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
     * @param string $username
     *
     * @return array
     */
    public function getRosterContacts($username)
    {
        $response = $this->sendRequest(
            'get_roster',
            [
                'user' => $username,
                'host' => $this->host,
            ]
        );

        if (!isset($response['contacts']) || empty($response['contacts'])) {
            return [];
        }

        $rosterContacts = [];
        foreach ($response['contacts'] as $contact) {
            foreach ($contact['contact'] as $contactProperty) {
                if (isset($contactProperty['jid'])) {
                    $rosterContacts[] = $contactProperty['jid'];
                }
            }
        }

        return $rosterContacts;
    }

    /**
     * @param string $username
     * @param string $contact
     * @param string $nickname
     * @param string $group
     */
    public function addRosterContact($username, $contact, $nickname, $group = '')
    {
        $this->sendRequest(
            'add_rosteritem',
            [
                'localuser'   => $username,
                'localserver' => $this->host,
                'user'        => $contact,
                'server'      => $this->host,
                'nick'        => $nickname,
                'group'       => $group,
                'subs'        => 'both',
            ]
        );
    }

    /**
     * @param string $username
     * @param string $contact
     */
    public function removeRosterContact($username, $contact)
    {
        $this->sendRequest(
            'delete_rosteritem',
            [
                'localuser'   => $username,
                'localserver' => $this->host,
                'user'        => $contact,
                'server'      => $this->host,
            ]
        );
    }
} 