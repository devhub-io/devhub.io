<?php

/*
 * This file is part of develophub.net.
 *
 * (c) DevelopHub <master@develophub.net>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Entities;

use Venturecraft\Revisionable\RevisionableTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use RevisionableTrait;
}
