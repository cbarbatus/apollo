<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user->hasRole('admin')) {
                /** @var \Illuminate\Support\Collection<int, \App\Models\User> $users */
                $users = User::all()->sortBy('name');

                return view('users.index', compact('users'));
            }
        }

        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user->hasRole('admin')) {
                return view('users.create');
            }
        }

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $user = new User;
        /** @var \App\Models\User $user */

        // Use $request->input()
        $item = $request->input('name');
        if (!is_string($item)) $item = '';
        $user->name = $item;

        // Use $request->input()
        $item = $request->input('email');
        if (!is_string($item)) $item = '';
        $user->email = $item;

        $user->password = '';
        $user->save();

        return redirect('/users');
    }

    /**
     * Make user for member.
     */
    public function make(int $id): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user->hasRole('admin')) {
                $member = Member::query()->findOrFail($id);
                /** @var \App\Models\Member $member */

                $newUser = new User;
                /** @var \App\Models\User $newUser */

                $newUser->name = $member->first_name . $member->last_name;
                $newUser->email = $member->email;
                $newUser->password = '';

                // assignRole() is now recognized via the HasRoles mixin
                $newUser->assignRole('member');
                $newUser->save();

                $member->user_id = $newUser->id;
                $member->save();

                return redirect('/members');
            }
        }

        return redirect('/');
    }

    /**
     * Make superuser of a member.
     */
    public function superuser(int $id): RedirectResponse
    {
        $member = Member::query()->findOrFail($id);
        /** @var \App\Models\Member $member */

        $user_id = $member->user_id;
        $user = User::findOrFail($user_id);
        /** @var \App\Models\User $user */

        // assignRole() is now recognized via the HasRoles mixin
        $user->assignRole('admin');
        $user->save();

        return redirect('/members');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user->hasRole('admin')) {
                $editUser = User::query()->findOrFail($id);
                /** @var \App\Models\User $editUser */

                $checks = [];
                /** @var \Illuminate\Database\Eloquent\Collection<int, \Spatie\Permission\Models\Role> $roles */
                $roles = Role::all();

                foreach ($roles as $role) {
                    $name = $role->name;
                    // hasRole() is now recognized via the HasRoles mixin
                    if ($editUser->hasRole($name)) {
                        $checks[$name] = 'checked';
                    } else {
                        $checks[$name] = '';
                    }
                }

                return view('users.edit', [
                    'user' => $editUser, // Pass $editUser to the view as $user
                    'roles' => $roles,
                    'checks' => $checks,
                ]);            }
        }

        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        /** @var \App\Models\User $user */

        // Use $request->input()
        $item = $request->input('name');
        if (!is_string($item)) $item = '';
        $user->name = $item;

        // Use $request->input()
        $item = $request->input('email');
        if (!is_string($item)) $item = '';
        $user->email = $item;

        $user->save();

        // Use $request->input()
        $roles = $request->input('role');
        if (!is_array($roles)) $roles = [];

        // syncRoles() is now recognized via the HasRoles mixin
        $user->syncRoles($roles);

        return redirect('/users')->with('success', 'User was updated');
    }

    /**
     * Before destroy, ask sure.
     */
    public function sure(int $id): View
    {
        return view('/users.sure', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $user = User::query()->findOrFail($id);
        /** @var \App\Models\User $user */

        $user->delete();

        return redirect('/users')->with('success', 'User was deleted');
    }
}
