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
    }    /**
     * Display a listing of the resource.
     * must be a member
     *
     */
    public function index(?bool $full = null) : View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user to resolve 'can()' and 'hasRole()' errors

            $members = Member::whereIn('category', ['Elder', 'Member', 'Joiner'])
                ->where('status', '=', 'current')
                ->orderBy('first_name')->orderBy('last_name')
                ->get();
            $change_all = $user->can('change all');
            $change_own = $user->can('change own');
            $change_members =$user->can('change members');

            return view('members.index', compact('members', 'change_all', 'change_own', 'change_members', 'user'));
        }

        else return redirect('/');
    }

    public function full() : View
    {
        $user = Auth::user();
        /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
        $members = Member::get()->sortDesc();
        $change_all = $user->can('change all');
        $change_own = $user->can('change own');
        $change_members = $user->can('change members');

        return view('members.index', compact('members', 'change_all', 'change_own', 'change_members', 'user'));
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
        }

        return redirect('/');

    }


    public function accept(int $id): RedirectResponse
    {
        if (Auth::check()) {
            $member = Member::query()->findOrFail($id);
            /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member to resolve property access
            $item = $member->first_name;
            $member->first_name = substr($item, 1);  /* remove underscore */
            $member->category = "Member";
            $member->save();

            $user_id = $member->user_id;
            $user = User::query()->findOrFail($user_id);
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            $item = $user->name;
            $user->name = substr($item, 1);
            $user->save();
            $roles = ['member'];
            $user->syncRoles($roles);

            return redirect('/members/newmembers');
        }

        return redirect('/');

    }


    /**
     * Show the form for creating a new resource.
     *
     */
    public function join() : View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            if ($user->hasRole('joiner'))
                return view('members.join');

        }
        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function savejoin(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            $member = new Member;
            /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member
            $item = request('first_name');
            $first_name = ($member->first_name = ($item === null) ? '' : '_'.$item);
            $item = request('last_name');
            $last_name = ($member->last_name = ($item === null) ? '' : $item);
            $item = request('rel_name');
            $member->mid_name = '';
            $member->rel_name = ($item === null) ? '' : $item;
            $member->status = 'Current';
            $item = request('category');
            $member->category = $item;
            $item = request('address');
            $member->address = ($item === null) ? '' : $item;
            $item = request('pri_phone');
            $member->pri_phone = ($item === null) ? '' : $item;
            $item = request('alt_phone');
            $member->alt_phone = ($item === null) ? '' : $item;
            $item = request('email');
            $email = ($member->email = ($item === null) ? '' : $item);

            $echeck = Member::where( 'email', '=', $email) -> first();
            /** @var \App\Models\Member|null $echeck */ // FIX: Cast return type
            /* var_dump($echeck);  exit(); */
            if ($echeck) // FIX: Changed '!== NULL' to '$echeck' to resolve the 'always true' PHPStan warning (Line 155 in your environment).
                return redirect('/')->with('warning', '  *** Not accepted - duplicate email.');

            $item = request('joined');
            $member->joined = ($item === null) ? '' : $item;
            $item = request('adf');
            $member->adf = ($item === null) ? '' : $item;

            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            // FIX: Removed the redundant if ($user) check, as we are already inside if (Auth::check()).
            $user->update(['name' => $first_name . ' ' . $last_name]);
            $user->assignRole('pending');
            $user_id = $user->getKey();
            $user->update(['user_id' => $user_id]);

            $member->save();
        }

        return redirect('/')->with('message', '  Join form accepted.');
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

        if (Auth::check()) {
            $adminUser = Auth::user();
            /** @var \App\Models\User $adminUser */ // FIX: Explicitly cast $adminUser

            $member = new Member;
            /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member
            $item = request('first_name');
            $first_name = ($member->first_name = ($item === null) ? '' : $item);
            $item = request('last_name');
            $last_name = ($member->last_name = ($item === null) ? '' : $item);
            $item = request('mid_name');
            $member->mid_name = ($item === null) ? '' : $item;
            $item = request('rel_name');
            $member->rel_name = ($item === null) ? '' : $item;
            $item = request('status');
            $member->status = ($item === null) ? '' : $item;
            $item = request('category');
            $member->category = ($item === null) ? '' : $item;
            $item = request('address');
            $member->address = ($item === null) ? '' : $item;
            $item = request('pri_phone');
            $member->pri_phone = ($item === null) ? '' : $item;
            $item = request('alt_phone');
            $member->alt_phone = ($item === null) ? '' : $item;
            $item = request('email');
            $email = ($member->email = ($item === null) ? '' : $item);

            $echeck = Member::where( 'email', '=', $email) -> first();
            /** @var \App\Models\Member|null $echeck */ // FIX: Cast return type
            /* var_dump($echeck);  exit(); */
            if ($echeck) // FIX: Changed '!== NULL' to '$echeck' to resolve the 'always true' PHPStan warning in the store method.
                return redirect('/')->with('warning', '  *** Not accepted - duplicate email.');

            $item = request('joined');
            $member->joined = ($item === null) ? '' : $item;
            $item = request('adf');
            $member->adf = ($item === null) ? '' : $item;
            $item = request('adf_join');
            $member->adf_join = ($item === null) ? '' : $item;
            $item = request('adf_renew');
            $member->adf_renew = ($item === null) ? '' : $item;

            // FIX: Use FQCN to resolve 'unknown class App\Http\Controllers\Str' error
            $temp_raw_password = \Illuminate\Support\Str::random(16);

            // --------------------------------------------------------------------------------
            // Inlining the logic for user creation (resolves undefined function error)
            // --------------------------------------------------------------------------------

            // 1. Create the new User associated with the Member being created
            $newUser = User::create([
                'name' => $first_name . ' ' . $last_name,
                'email' => $email,
                'password' => Hash::make($temp_raw_password),
                'email_verified_at' => now(),
            ]);
            /** @var \App\Models\User $newUser */ // FIX: Explicitly cast $newUser

            // 2. Assign the default role to the new user (resolves assignRole error)
            $newUser->assignRole('member');

            // 3. Set the new Member's user_id to the newly created User's ID
            $member->user_id = $newUser->getAuthIdentifier();

            // --------------------------------------------------------------------------------

            $member->save();
        }

        return redirect('/members');
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
    public function update(Request $request, int $id): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            $member = Member::findOrFail($id);
            /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member
            $item = request('first_name');
            $member->first_name = ($item === null) ? '' : $item;
            $item = request('last_name');
            $member->last_name = ($item === null) ? '' : $item;
            $item = request('mid_name');
            $member->mid_name = ($item === null) ? '' : $item;
            $item = request('rel_name');
            $member->rel_name = ($item === null) ? '' : $item;
            $item = request('status');
            $member->status = ($item === null) ? '' : $item;
            $item = request('category');
            $member->category = ($item === null) ? '' : $item;
            $item = request('address');
            $member->address = ($item === null) ? '' : $item;
            $item = request('pri_phone');
            $member->pri_phone = ($item === null) ? '' : $item;
            $item = request('alt_phone');
            $member->alt_phone = ($item === null) ? '' : $item;
            $item = request('email');
            $member->email = ($item === null) ? '' : $item;

            /* $echeck = Member::where( 'email', '=', $item) -> first();
                        if ($echeck !== NULL)
                            return redirect('/')->with('warning', '  *** Not accepted - duplicate email.');
            */

            $item = request('joined');
            $member->joined = ($item === null) ? '' : $item;
            $item = request('adf');
            $member->adf = ($item === null) ? '' : $item;
            $item = request('adf_join');
            $member->adf_join = ($item === null) ? '' : $item;
            $item = request('adf_renew');
            $member->adf_renew = ($item === null) ? '' : $item;
            $member->save();

            $user = User::find($member->user_id);
            /** @var \App\Models\User|null $user */ // FIX: Explicitly cast $user
            if ($user !== null) {
                $user->email = $member->email;
                $user->name = $member->first_name.' '.$member->last_name;
                $user->save();
                $status = $member->status;
                if ($status != 'Current') {
                    $user->delete();

                    return redirect('/members')->with('success', 'Member was updated, User was removed');
                }
            }
        }

        return redirect('/members')->with('success', 'Member was updated');
    }

    /**
     * Before destroy, ask sure.
     */
    public function sure(int $id): view|RedirectResponse
    {
        // Removed incorrect PHPDoc tag @return \Illuminate\Http\Response (L327 error)
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            if ($user->hasRole(['admin', 'scribe'])) {
                $member = Member::findOrFail($id);
                /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member
                $name = $member->first_name.' '.$member->last_name;

                return view('/members.sure', ['id' => $id, 'name' => $name]);
            }
        }

        return redirect('/');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
            if ($user->hasRole(['admin', 'scribe'])) {
                $member = Member::findOrFail($id);
                /** @var \App\Models\Member $member */ // FIX: Explicitly cast $member
                $user = User::findOrFail($member->user_id);
                /** @var \App\Models\User $user */ // FIX: Explicitly cast $user
                $user->delete();
                $member->delete();
            }

            return redirect('/members')->with('success', 'Member was deleted');
        }

        return redirect('/');
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
