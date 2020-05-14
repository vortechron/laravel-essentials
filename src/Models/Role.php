<?php

namespace Vortechron\Essentials\Models;

use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role as ModelsRole;
use Vortechron\Essentials\Traits\Controller\Essentials;
use Vortechron\Essentials\Traits\Modeler;

class Role extends ModelsRole
{
    use Modeler, Essentials;

    protected $fillable = ['name', 'guard_name', 'created_at', 'updated_at'];

    public static function indexAction()
    {
        (new Static)->prepareIndexData(Role::class, 'Manage Roles', ['name'], ['name', 'created_at']);
    }

    public static function createAction($action)
    {
        $essentials = new Static;
        $essentials->prepareData((new Role)->forModel([]), 'Create Role', $action);

        View::share('permissions', Permission::all());
        View::share('role', new Role);
    }

    public static function storeAction($request)
    {
        $role = static::firstOrCreate(['name' => $request->name]);

        foreach ($request->input('permissions', []) as $permission => $value) {
            $role->givePermissionTo($permission);
        }

        return $role;
    }

    public static function editAction($role, $action, $deleteAction, $backAction)
    {
        $essentials = new Static;
        $essentials->prepareData($role->forModel([]), 'Edit Role', $action, $deleteAction, $backAction);

        View::share('permissions', Permission::all());
        View::share('role', $role);
    }

    public static function updateAction($role, $request)
    {
        $role->name = $request->name;
        $role->save();

        $role->permissions()->detach();
        
        foreach ($request->input('permissions', []) as $permission => $value) {
            $role->givePermissionTo($permission);
        }
    }

    public static function destroyAction($role)
    {
        if ($role->id == 1) error('Admin cannot be delete.');

        $role->permissions()->detach();
        $role->delete();
    }
    
}