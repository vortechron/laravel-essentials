<?php

namespace Vortechron\Essentials\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Vortechron\Essentials\Models\Role;
use Illuminate\Support\Facades\View;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    public function index()
    {
        $this->prepareIndexData(Role::class, 'Manage Roles', ['name'], ['name', 'created_at']);

        return view(config('laravel-essentials.admin.view_path').'.users.roles.index');
    }
    
    public function create()
    {
        $this->prepareData((new Role)->forModel([]), 'Create Role', route(config('laravel-essentials.admin.route_prefix').'.roles.store'));

        View::share('permissions', Permission::all());
        View::share('role', new Role);

        return view(config('laravel-essentials.admin.view_path').'.users.roles.template');
    }

    public function store(Request $request)
    {
        $role = Role::firstOrCreate(['name' => $request->name]);

        foreach ($request->input('permissions', []) as $permission => $value) {
            $role->givePermissionTo($permission);
        }

        return $this->handleRedirect(route(config('laravel-essentials.admin.route_prefix').'.roles.edit', $role), route(config('laravel-essentials.admin.route_prefix').'.roles.index'));   
    }

    public function edit(Role $role)
    {
        $this->prepareData($role->forModel([]), 'Edit Role', route(config('laravel-essentials.admin.route_prefix').'.roles.update', $role), route(config('laravel-essentials.admin.route_prefix').'.roles.destroy', $role), route(config('laravel-essentials.admin.route_prefix').'.roles.index'));

        View::share('permissions', Permission::all());
        View::share('role', $role);

        return view(config('laravel-essentials.admin.view_path').'.users.roles.template');
    }

    public function update(Request $request, Role $role)
    {
        $role->name = $request->name;
        $role->save();

        $role->permissions()->detach();
        
        foreach ($request->input('permissions', []) as $permission => $value) {
            $role->givePermissionTo($permission);
        }
        
        return $this->handleRedirect(route(config('laravel-essentials.admin.route_prefix').'.roles.edit', $role), route(config('laravel-essentials.admin.route_prefix').'.roles.index'));   
    }

    public function destroy(Role $role)
    {
        if ($role->id == 1) error('Admin cannot be delete.');

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route(config('laravel-essentials.admin.route_prefix').'.roles.index');
    }
}
