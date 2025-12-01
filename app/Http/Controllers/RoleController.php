<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    /**
     * Display a listing of roles and permissions.
     */
    public function roles(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user && $user->hasRole('admin')) {
                /** @var \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles */
                $roles = Role::all();
                /** @var \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions */
                $permissions = Permission::all();

                return view('roles.index', compact('roles', 'permissions'));
            }
        }

        return redirect('/');
    }

    /* MAINTAIN RULES  */

    /**
     * Show the form for creating a new role.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user && $user->hasRole('admin')) {
                return view('roles.create');
            }
        }

        return redirect('/');
    }

    /**
     * Create the role
     */
    public function store(Request $request): RedirectResponse
    {
        // Use $request->input() instead of global request()
        $item = $request->input('name');
        if (!is_string($item)) $item = '';

        $role = Role::create(['name' => $item]);

        return redirect('/roles');
    }

    /**
     * Delete a role, before destroy, ask sure.
     */
    public function sure(string $name): View
    {
        return view('/roles.sure', ['name' => $name]);
    }

    /**
     * Remove the role.
     */
    public function destroy(string $name): RedirectResponse
    {
        $role = Role::query()->where('name', $name)->firstOrFail();
        /** @var \Spatie\Permission\Models\Role $role */
        $role->delete();

        return redirect('/roles')->with('success', 'Role was deleted');
    }

    /* MAINTAIN PERMISSIONS  */

    /**
     * Show the form for creating a new permission.
     */
    public function pcreate(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user && $user->hasRole('admin')) {
                return view('roles.pcreate');
            }
        }

        return redirect('/');
    }

    /**
     * Create the permission
     */
    public function pstore(Request $request): RedirectResponse
    {
        // Use $request->input() instead of global request()
        $item = $request->input('name');
        if (!is_string($item)) $item = '';

        $permission = Permission::create(['name' => $item]);

        return redirect('/roles');
    }

    /**
     * Delete a permission, before destroy, ask sure.
     */
    public function psure(string $name): View
    {
        return view('/roles.psure', ['name' => $name]);
    }

    /**
     * Remove the permission.
     */
    public function pdestroy(string $name): RedirectResponse
    {
        $permission = Permission::query()->where('name', $name)->firstOrFail();
        /** @var \Spatie\Permission\Models\Permission $permission */
        $permission->delete();

        return redirect('/roles')->with('success', 'Permission was deleted');
    }

    /* ASSOCIATE PERMISSIONS WITH ROLES   */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $name): View
    {
        $role = Role::query()->where('name', $name)->firstOrFail();
        /** @var \Spatie\Permission\Models\Role $role */

        // FIX: Use permissions() method call to resolve property.notFound
        $pnames = $role->permissions()->get();
        /** @var \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $pnames */ // FIX: Added int key for generics

        return view('roles.edit', compact(['role', 'pnames']));
    }

    /**
     * Remove the permission from role.
     */
    public function remove(string $name, string $pname): RedirectResponse
    {
        $role = Role::query()->where('name', $name)->firstOrFail();
        /** @var \Spatie\Permission\Models\Role $role */

        $role->revokePermissionTo($pname);

        // FIX: Use permissions() method call to resolve property.notFound
        $pnames = $role->permissions()->get();
        /** @var \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $pnames */ // FIX: Added int key for generics

        return redirect('/roles/'.$name.'/edit');
    }

    /**
     * Add permission to role.
     */
    public function add(string $name): View
    {
        $role = Role::query()->where('name', $name)->firstOrFail();
        /** @var \Spatie\Permission\Models\Role $role */

        /** @var \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Permission> $permissions */
        $permissions = Permission::all();

        return view('roles.add', compact(['role', 'permissions']));
    }

    public function set(Request $request, string $name): RedirectResponse
    {
        $role = Role::query()->where('name', $name)->firstOrFail();
        /** @var \Spatie\Permission\Models\Role $role */

        $pname = $request->input('permission_name');
        if (is_string($pname)) {
            $role->givePermissionTo($pname);
        }

        return redirect('/roles/'.$name.'/edit');
    }
}
