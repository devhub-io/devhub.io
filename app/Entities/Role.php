<?php
/**
 * User: yuan
 * Date: 16/7/13
 * Time: 16:53
 */

namespace App\Entities;

use Venturecraft\Revisionable\RevisionableTrait;
use Zizaco\Entrust\EntrustRole;

class Role extends EntrustRole
{
    use RevisionableTrait;
}
