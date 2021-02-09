<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Models\User;
use App\Repositories\Permissions\PermissionRepositoryInterface;
use App\Repositories\Roles\RoleRepositoryInterface;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Traits\HasPermissions;

class PermissionController extends Controller
{
    protected $permissionRepository;
    protected $roleRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository, RoleRepositoryInterface $roleRepository)
    {
        $this->permissionRepository = $permissionRepository;
        $this->roleRepository = $roleRepository;
    }

    public function indexPermissions()
    {
        $permissions = $this->permissionRepository->getAll();
        $count = 1;
        return view('admin.decentralization.permissions.index', compact('permissions', 'count'));
    }

    public function indexRoles()
    {
        $roles = $this->roleRepository->getAll();
        $count = 1;
        return view('admin.decentralization.roles.index', compact('roles', 'count'));
    }

    public function editPermissions($id)
    {
        $permission = $this->permissionRepository->find($id);
        if ($permission) {
            $roles = $permission->roles;
            $users = User::whereHas('permissions', function ($query) use ($permission) {
                $query->where('name', $permission->name);
            })->get();

            $usersDoesntHavePermission = User::whereDoesntHave('permissions', function ($query) use ($permission) {
                $query->where('name', $permission->name);
            })->pluck('email','id');

            $rolesDoesntHavePermission = Role::whereDoesntHave('permissions', function ($query) use ($permission) {
                $query->where('name', $permission->name);
            })->pluck('name','id');
            return view('admin.decentralization.permissions.edit',
                compact('permission', 'users', 'roles', 'usersDoesntHavePermission', 'rolesDoesntHavePermission'));
        } else {
            return redirect()->route('admin.permissions.index')->with('warning', 'page not found');
        }
    }

    public function editRoles($id)
    {
        $role = $this->roleRepository->find($id);
        if ($role) {
            $permissions = $role->permissions;
            $users = $role->users;

            $usersDoesntHaveRole = User::whereDoesntHave('roles', function ($query) use ($role) {
                $query->where('name', $role->name);
            })->pluck('email','id');

            $permissionsDoesntHaveRole = Permission::whereDoesntHave('roles', function ($query) use ($role) {
                $query->where('name', $role->name);
            })->pluck('name','id');
            return view('admin.decentralization.roles.edit',
                compact('role', 'users', 'permissions', 'usersDoesntHaveRole', 'permissionsDoesntHaveRole'));
        } else {
            return redirect()->route('admin.roles.index')->with('warning', 'page not found');
        }
    }
}
