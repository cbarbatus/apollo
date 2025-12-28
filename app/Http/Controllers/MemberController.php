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
use Illuminate\Support\Facades\DB;

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
        if (!auth()->check()) {
            return redirect('/');
        }

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
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            if ($user->hasRole(['admin', 'scribe', 'SeniorDruid'])) {
                $newmembers = Member::whereIn('category', ['Joiner'])
                    ->orderBy('first_name')->orderBy('last_name')
                    ->get();
                return view('members.newmembers', compact('newmembers'));
            }
            else dd('not authorized');
        }

        return redirect('/');

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
           if (Auth::user()?->hasRole('joiner'))
                return view('members.join');
            return redirect('/');
    }

    /**

     */
    public function savejoin(Request $request): RedirectResponse
    {
        if (!Auth::check()) return redirect('/');

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
        // Authorization check
        if (!auth()->user()->hasRole(['admin', 'scribe'])) {
            return redirect('/members')->with('error', 'Unauthorized.');
        }

        return DB::transaction(function () use ($member) {
            // 1. Delete the User account (User 139)
            if ($member->user_id) {
                \App\Models\User::where('id', $member->user_id)->delete();
            }

            // 2. Delete the Member record entirely (Member 231)
            $member->delete();

            return redirect('/members')->with('success', 'Applicant ' . $member->first_name . ' has been fully removed.');
        });
    }
    /**
     * Show the form for creating a new resource.
     *
     */
    public function create() : View | RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            if ($user->hasRole(['admin', 'scribe'])) {
                return view('members.create');
            }
        }

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Authorization Check
        if (!Auth::check()) {
            return redirect('/')->with('error', 'Unauthorized.');
        }

        // 2. Clean Validation (Replaces all those manual null checks)
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'email'      => 'required|email|unique:users,email|unique:members,email',
            'role'       => 'nullable|string'
        ]);

        // 3. The Integrity Guardrail: Database Transaction
        return \DB::transaction(function () use ($request, $validated) {

            // Create User Parent
            $newUser = User::create([
                'name' => $validated['first_name'] . ' ' . $validated['last_name'],
                'email' => $validated['email'],
                'password' => Hash::make(\Illuminate\Support\Str::random(16)),
                'email_verified_at' => now(),
            ]);

            // Assign Role (Spatie) - Use the role from the form or default to 'member'
            $roleName = $request->input('role', 'member');
            $newUser->assignRole($roleName);

            // Create Member Child
            $member = new Member;
            $member->user_id = $newUser->id;
            $member->first_name = $validated['first_name'];
            $member->last_name = $validated['last_name'];
            $member->email = $validated['email'];

            // Map the remaining optional fields cleanly
            $optionalFields = [
                'mid_name', 'rel_name', 'status', 'category', 'address',
                'pri_phone', 'alt_phone', 'joined', 'adf', 'adf_join', 'adf_renew'
            ];

            foreach ($optionalFields as $field) {
                $value = $request->input($field);

                // Check if it's the specific 'date' type column
                if ($field === 'joined') {
                    // Because 'joined' is 'date' and 'NOT NULL'
                    // MySQL often requires a dummy date if it's not nullable
                    $member->$field = (empty($value)) ? '0001-01-01' : $value;
                } else {
                    // For all varchar fields set to NOT NULL
                    // We must provide an empty string '' to avoid SQLSTATE[23000]
                    $member->$field = (empty($value)) ? '' : $value;
                }
            }
            $member->save();

            return redirect('/members')->with('success', 'Member and User account created successfully.');
        });
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        // FIX: Variables initialized outside the if block to resolve compact() errors
        $member = new Member();
        /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member
        $change_members = false;

        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            $member = Member::findOrFail($id);
            /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member after retrieval
            $change_members = $user->can('change members');
        }

        return view('members.edit', compact('member', 'change_members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Member $member): RedirectResponse
    {
        // 1. Validation & Security Gate
        // This ensures only the fields Susan is allowed to see are updated by her
        $isManager = auth()->user()->canAny(['change all', 'change members', 'change_members']);

        $data = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name'  => 'required|string|max:255',
            'mid_name'   => 'nullable|string',
            'rel_name'   => 'nullable|string',
            'address'    => 'nullable|string',
            'pri_phone'  => 'nullable|string',
            'alt_phone'  => 'nullable|string',
            'email' => [
                'required',
                'email',
                // Validate against the 'members' table and ignore this specific member's ID
                Rule::unique('members', 'email')->ignore($member->id),

            ],
        ]);

        // 2. Add Management Fields ONLY if the user is a Manager
        if ($isManager) {
            $managementData = $request->validate([
                'status'     => 'required|string',
                'category'   => 'required|string',
                'joined'     => 'nullable|date',
                'adf'        => 'nullable|string',
                'adf_join' => 'nullable', // or remove 'date' if you just want to permit the string
                'adf_renew' => 'nullable',

            ]);
            $data = array_merge($data, $managementData);
        }

        // 3. Update Member (Handles all those null/item assignments at once)
        // 3. Update Member (Handles all those null/item assignments at once)
// Convert any null string fields to empty strings to satisfy antique NOT NULL constraints
        $stringFields = ['mid_name', 'rel_name', 'address', 'pri_phone', 'alt_phone'];

        foreach ($stringFields as $field) {
            $data[$field] = $data[$field] ?? '';
        }

        $member->update($data);
        $member->update($data);

// 4. Sync User Record (Safe Version)
        if ($member->user_id && $user = \App\Models\User::find($member->user_id)) {

            $user->name = $member->first_name . ' ' . $member->last_name;

            // Only update email if it's actually different from what's in the User record
            // This prevents the "Email already taken" database crash
            if ($user->email !== $member->email) {
                $user->email = $member->email;
            }

            $user->save(); // Laravel now only touches the columns that changed

            if ($member->status !== 'Current') {
                $user->delete();
                return redirect('/members')->with('success', 'Member updated, User removed.');
            }
        }
        return redirect('/members')->with('success', 'Member updated successfully!');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Member $member): RedirectResponse
    {

        // Authorization check
        if (!auth()->user()->hasRole(['admin', 'scribe'])) {
            return redirect('/members')->with('error', 'Unauthorized.');
        }
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
    public function restore(Request $request) : RedirectResponse
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
        if ($user->can('change members')) {

            $first_name = request('first_name');
            $last_name = request('last_name');

            $member = Member::whereIn('category', ['Elder', 'Member'])
                ->where('status', '=', 'Resigned')
                ->where('first_name', '=', $first_name)
                ->where('last_name', '=', $last_name)
                ->first();
            /** @var \App\Models\Member|null $member */ // FIX: Explicitly cast $member

            if ($member !== null) {

                $member->status = 'Current';
                $member->save();

                $user = new User;
                /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
                $user->name = $first_name.' '.$last_name;
                $user->email = $member->email;
                $user->password = '';
                $user->assignRole('member');
                $user->save();

                $member->user_id = $user->id;
                $member->save();

            }

            return redirect('/members');
        }

        return redirect('/');
    }
}
