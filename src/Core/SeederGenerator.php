<?php

namespace Vortechron\Essentials\Core;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class SeederGenerator
{
    public static function mapPermissions($permissions, $roles)
    {
        foreach ($roles as $roleName) {
            $role = Role::whereName($roleName)->first();

            if (! $role) $role = Role::create(['name' => $roleName]);

            foreach ($permissions as $permissionName) {
                $permission = Permission::whereName($permissionName)->first();

                if (! $permission) $permission = Permission::create(['name' => $permissionName]);
                
                if ($permission && ! $role->hasPermissionTo($permissionName)) {
                    $role->givePermissionTo($permissionName);
                }
            }
        }
    }
}