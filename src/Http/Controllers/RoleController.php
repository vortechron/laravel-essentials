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

        return view('admin.user.role.index');
    }
    
    public function create()
    {
        $this->prepareData((new Role)->forModel([]), 'Create Role', route('admin.roles.store'));

        View::share('permissions', Permission::all());
        View::share('role', new Role);

        return view('admin.user.role.template');
    }

    public function store(Request $request)
    {
        $role = Role::firstOrCreate(['name' => $request->name]);

        foreach ($request->input('permissions', []) as $permission => $value) {
            $role->givePermissionTo($permission);
        }

        return $this->handleRedirect(route('admin.roles.edit', $role), route('admin.roles.index'));   
    }

    public function edit(Role $role)
    {
        $this->prepareData($role->forModel([]), 'Edit Role', route('admin.roles.update', $role), route('admin.roles.destroy', $role), route('admin.roles.index'));

        View::share('permissions', Permission::all());
        View::share('role', $role);

        return view('admin.user.role.template');
    }

    public function update(Request $request, Role $role)
    {
        $role->name = $request->name;
        $role->save();

        $role->permissions()->detach();
        
        foreach ($request->input('permissions', []) as $permission => $value) {
            $role->givePermissionTo($permission);
        }
        
        return $this->handleRedirect(route('admin.roles.edit', $role), route('admin.roles.index'));   
    }

    public function destroy(Role $role)
    {
        if ($role->id == 1) error('Admin cannot be delete.');

        $role->permissions()->detach();
        $role->delete();

        return redirect()->route('admin.roles.index');
    }
}
