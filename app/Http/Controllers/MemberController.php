<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;

class MemberController extends Controller
{
    public function __construct()
    {
        // 1. Apply 'auth' middleware to ALL methods in this controller by default.
        // This solves the session timeout/null user error for 95% of your functions.
        $this->middleware('auth')->except([

        ]);

        // 2. Add specific, fine-grained permission checks inside the function bodies
        //    (like the $user->can('change members') check) for further security.
    }

    /**
     * Display a listing of the resource.
     * must be a member
     *
     */
    public function index(Request $request): View|RedirectResponse
    {

        $user = auth()->user();
        /** @var \App\Models\User $user */

        // Check if the query string ?filter=all is present
        $showAll = $request->query('filter') === 'all';

        $query = Member::query();

        // If we are NOT showing all, apply the filters
        if (!$showAll) {
            $query->whereIn('category', ['Elder', 'Member', 'Joiner'])
                ->where('status', 'current');
        }

        $members = $query->orderBy('first_name')->orderBy('last_name')->get();

        // Permissions logic
        $change_all = $user->can('change all');
        $change_own = $user->can('change own');
        $change_members = $user->can('change members');

        return view('members.index', compact(
            'members',
            'change_all',
            'change_own',
            'change_members',
            'user'
        ))->with('full', $showAll); // Pass the state to the view
    }

    public function newmembers() : View|RedirectResponse
    {
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
                $newmembers = Member::whereIn('category', ['Joiner'])
                    ->orderBy('first_name')->orderBy('last_name')
                    ->get();
                return view('members.newmembers', compact('newmembers'));
    }


    public function accept(int $id): RedirectResponse
    {
        $member = Member::findOrFail($id);

        // Clean the Member Name
        if (str_starts_with($member->first_name, '_')) {
            $member->first_name = ltrim($member->first_name, '_');
        }
        $member->category = "Member";
        $member->save();

        // Safely handle the User
        if ($member->user_id) {
            $user = User::find($member->user_id);
            if ($user) {
                if (str_starts_with($user->name, '_')) {
                    $user->name = ltrim($user->name, '_');
                }
                $user->save();
                $user->syncRoles(['member']); // Upgrade from 'pending'
            }
        }

        return redirect('/members/newmembers')->with('success', 'Member accepted and moved to the roster successfully!');
}


    /**
     * Show the form for creating a new resource.
     *
     */
    public function join() : View|RedirectResponse
    {
          return view('members.join');
    }

    /**

     */
    public function savejoin(Request $request): RedirectResponse
    {
        // 1. Validation (Modern way to handle those 'null' checks)
        $validated = $request->validate([
            'email' => 'required|email|unique:users,email|unique:members,email',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
        ]);

        return DB::transaction(function () use ($request) { // <--- Added 'return' here
            // 2. Create User first with 'pending' role
            $newUser = User::create([
                'name' => '_' . $request->first_name . ' ' . $request->last_name,
                'email' => $request->email,
                'password' => Hash::make(Str::random(12)),
            ]);

            $newUser->assignRole('pending');

            // 3. Create Member
            $member = new Member;
            $member->user_id = $newUser->id;
            $member->first_name = '_' . $request->first_name;
            $member->last_name = $request->last_name;
            $member->email = $request->email;
            $member->status = 'Current';
            $member->category = $request->category ?? 'Joiner';

            // Use your existing 'scrubbing' for antique NOT NULL columns
            $optional = [
                'mid_name', 'rel_name', 'address', 'pri_phone', 'alt_phone',
                'joined', 'adf', 'adf_join', 'adf_renew',
                'city', 'state', 'zip', 'country', 'dob', 'emergency_contact' // Common culprits
            ];
            foreach ($optional as $field) {
                // Check if column exists on the model to avoid a different error
                if (Schema::hasColumn('members', $field)) {
                    $member->$field = $request->input($field) ?? '';
                }
            }

            $member->save();

            return redirect('/')->with('success', 'Join form accepted.');
        });
    }

    public function deletejoin(Member $member): RedirectResponse
    {
        // 1. The Bouncer (Policy)
        $this->authorize('delete', $member);

        return DB::transaction(function () use ($member) {
            // 2. Delete the User record [cite: 2025-12-31]
            if ($member->user_id) {
                \App\Models\User::where('id', $member->user_id)->delete();
            }

            // 3. Delete the Member record
            $member->delete();

            // 4. Use the Named Route for consistency
            return redirect()->route('members.index')
                ->with('success', "Applicant {$member->first_name} has been fully removed.");
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View
    {
        $member = Member::findOrFail($id);
        $user = Auth::user();

        // The logic we know works:
        $isManager = $user->canAny(['change all', 'change members', 'change_members']);
        $isOwner = ($user->can('change own') && (int)$member->user_id === (int)$user->id);

        if (!$isManager && !$isOwner) {
            abort(403, 'User does not have the right roles.');
        }

        $change_members = $isManager || $isOwner;
        return view('members.edit', compact('member', 'change_members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $member = Member::findOrFail($id);
        $user = Auth::user();

        // The logic that we've verified works for the Edit screen:
        $isManager = $user->canAny(['change all', 'change members', 'change_members']);
        $isOwner = ($user->can('change own') && (int)$member->user_id === (int)$user->id);

        if (!$isManager && !$isOwner) {
            abort(403, 'You do not have permission to update this member.');
        }

        // Now proceed with your validation and update logic
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email',
            'status'     => 'required|string',
            'category'   => 'required|string',
            // ... add the rest of your fields here ...
        ]);

        // 2. Perform the Member update
        $validated = $request->all(); // Or $request->validate([...]);
        $member->update($validated);



        // 3. User Record Management [cite: 2025-12-31]
        // We use optional() or null-coalescing to avoid "if" nesting
        if ($member->user_id && $user = \App\Models\User::find($member->user_id)) {

            $existingUser = \App\Models\User::where('email', $member->email)
                ->where('id', '!=', $user->id) // Make sure it's not the current user's own email
                ->first();
            if ($existingUser) {
                return back()->withInput()->with('error', "The email {$member->email} is already assigned to another user record.");
            }

            if ($member->status !== 'Current') {
                $user->delete();
                $member->update(['user_id' => 0]);
            } else {
                // We use the already updated $member object here
                $user->update([
                    'email' => $member->email,
                    'name'  => $member->first_name . ' ' . $member->last_name
                ]);
            }
        }
        return redirect()->route('members.index')->with('success', 'Member updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): RedirectResponse
    {
        //// REVOKE ACCESS: Delete the User record, but leave the Member record alone.
        if ($member->user_id) {
            $user = \App\Models\User::find($member->user_id);
            if ($user) {
                $user->delete();
            }

            // Unlink the member so they don't point to a ghost user
            $member->update(['user_id' => null]);
        }
        else {

            $member->delete();
            return redirect('/members')->with('success', 'Member record ' . $member->id . ' deleted.');
        }

        // Single clear announcement to stop the "stronger than needed" duplicates
        return redirect('/members')->with('success', 'User login revoked. Member record preserved for history.');
    }

    /**
     * Restore a Resigned member
     */
    public function restore(Request $request): RedirectResponse
    {
        $member = Member::where('status', 'Resigned')
            ->where('first_name', trim($request->first_name))
            ->where('last_name', trim($request->last_name))
            ->first();

        if ($member) {
            $member->update(['status' => 'Current']);

            // Since only active members have user records [cite: 2025-12-31]
            // We ensure a User exists now.
            $user = User::updateOrCreate(
                ['email' => $member->email],
                [
                    'name' => "{$member->first_name} {$member->last_name}",
                    'password' => '',
                ]
            );

            $user->assignRole('member');
            $member->update(['user_id' => $user->id]);

            return redirect()->route('members.index')
                ->with('success', "{$member->first_name} has been restored.");
        }

        // FALLBACK: If we get here, $member was null. No crash!
        return redirect()->route('members.index')
            ->with('error', "Could not find a resigned member named {$request->first_name} {$request->last_name}.");
    }
}
