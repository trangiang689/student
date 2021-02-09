<?php

namespace App\Repositories\Permissions;

use App\Repositories\BaseRepository;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository extends BaseRepository implements PermissionRepositoryInterface
{
    public function getModel()
    {
        return Permission::class;
    }
}
