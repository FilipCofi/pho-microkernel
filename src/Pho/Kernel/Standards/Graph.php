<?php

namespace Pho\Kernel\Standards;

use Pho\Kernel\Foundation;
use Pho\Kernel\Kernel;

class Graph extends Foundation\AbstractGraph {

    /**
     * u:: (group owner) f -- can do anything
     * s:: (members) 7 -- anything except manage the group, rad, post, join (see all members only in this case)
     * g:: (people in the same context) (space) 5 -- read contents, ..., join (and see  all members because 4 is  enabled)
     * o:: (people outside) (irrelevant) 1 -- ..., ..., subscribe (read limited group info and friends? who are members)
     */
    const DEFAULT_MODE = 0x0f751;

    /**
     * how owner can change the settings
     * e may change sticky bit, allowing others to delete the group and change ACL (practically anything he can do, disabled by default)
     * f can't change his own settings, must remain intact
     * 8 can't give members "manage" privilege, can do anything else: take their write permission to make the group read-only etc.
     * a can't give people in the same network "manage" or "write" privilege, can do anything else. can take subscribe to make it invite-only or read to make it private.
     * a can't give outsiders "manage" or "write" privilege, can do anything else
     */
    const DEFAULT_MASK = 0xef8aa;

    const T_EDITABLE = true; // admins
    const T_PERSISTENT = true;
    const T_EXPIRATION = 0;
    const T_VERSIONABLE = false;

    public function __construct(Kernel $kernel, Foundation\AbstractActor $founder)
    { 
        parent::__construct($kernel, $founder, $kernel->space());
        $founder->changeContext($this);
    }

}