<?php
/**
 * User: yuan
 * Date: 16/7/13
 * Time: 16:55
 */

namespace App\Entities;

use Venturecraft\Revisionable\RevisionableTrait;
use Zizaco\Entrust\EntrustPermission;

class Permission extends EntrustPermission
{
    use RevisionableTrait;
}
