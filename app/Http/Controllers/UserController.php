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
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        // Fetch all roles from the Spatie table shown in your structure
        $roles = \Spatie\Permission\Models\Role::all();
        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8',
        ]);

        // Update Identity
        $user->name = $request->name;
        $user->email = $request->email;

        // Safe Password Handling: Only update if not empty
        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        // Spatie Sync: Automatically manages the model_has_roles pivot table
        $user->syncRoles($request->input('roles', []));

        return redirect()->route('users.index')->with('status', 'User updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): RedirectResponse
    {
        // Authorization check using modern Laravel gates
        if (auth()->user()->cannot('delete members')) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        // Safe User Deletion: Only attempt if a user_id exists
        if ($member->user_id) {
            $user = \App\Models\User::find($member->user_id);
            if ($user) {
                $user->delete();
            }
        }

        // Final Member Cleanup
        $member->delete();

        return redirect()->back()->with('success', 'Member data removed.');
    }
}
