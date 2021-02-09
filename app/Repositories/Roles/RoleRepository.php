<?php

namespace App\Repositories\Roles;

use App\Repositories\BaseRepository;
use App\Repositories\Roles\RoleRepositoryInterface;
use Spatie\Permission\Models\Role;

class RoleRepository extends BaseRepository implements RoleRepositoryInterface
{
    public function getModel()
    {
        return Role::class;
    }
}
