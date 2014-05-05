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
    public function getRosterContacts($username)
    {
        $response = $this->sendRequest(
            'get_roster',
            [
                'user' => $username,
                'host' => $this->host,
            ]
        );

        $rosterContacts = [];
        if (isset($response['contacts'])) {
            foreach ($response['contacts'] as $contact) {
                if (isset($contact['contact'])) {
                    foreach ($contact['contact'] as $contactProperty) {
                        if (isset($contactProperty['jid'])) {
                            array_push($rosterContacts, $contactProperty['jid']);
                        }
                    }
                }
            }
        }

        return $rosterContacts;
    }

    public function addRosterContact($username, $contactJid, $group = '')
    {
        return $this->sendRequest(
            'add_rosteritem',
            [
                'localuser'   => $username,
                'localserver' => $this->host,
                'user'        => $contactJid,
                'server'      => $this->host,
                'nick'        => $contactJid,
                'group'       => $group,
                'subs'        => 'both',
            ]
        );
    }

    /**
     * @param string $username
     * @param string $contactJid
     */
    public function removeRosterContact($username, $contactJid)
    {
        $this->sendRequest(
            'delete_rosteritem',
            [
                'localuser'   => $username,
                'localserver' => $this->host,
                'user'        => $contactJid,
                'server'      => $this->host,
            ]
        );
    }
} 