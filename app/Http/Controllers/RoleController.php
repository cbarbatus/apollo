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

    public function __construct()
    {
        // This is your 'Bouncer'.
        // It applies to every single method in this file.
        $this->middleware(['auth', 'role:admin']);
    }

        /**
     * Display a listing of roles and permissions.
     */
    /**
     * Display a listing of the roles and permissions.
     */
    public function index(): View
    {
        // The middleware handles the 'admin' check now,
        // so we can focus purely on the data.
        $roles = Role::all();
        $permissions = Permission::all();

        return view('roles.index', compact('roles', 'permissions'));
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
     * Remove the role.
     */
    public function destroy(Role $role) // Laravel matches {role} to this model
    {
        $role->syncPermissions([]);
        $role->users()->detach();
        $role->delete();

        return redirect('/roles')->with('success', 'Role deleted.');
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
     * Remove the permission.
     */
    public function pdestroy(Permission $permission)
    {
        // Safety check: is it in use by ANY role?
        if ($permission->roles()->count() > 0) {
            $names = $permission->roles()->pluck('name')->join(', ');
            return back()->with('error', "Cannot delete! This is still assigned to: {$names}.");
        }

        $permission->delete();
        return back()->with('success', "Permission purged.");
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
