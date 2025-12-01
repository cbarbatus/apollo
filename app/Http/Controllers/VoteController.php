<?php

namespace App\Http\Controllers;

use App\Models\Member;
use App\Models\Motion;
use App\Models\Vote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// Use explicit FQCN for Auth facade
use Illuminate\Support\Facades\Auth;
// Use Contracts\View\View for strict analysis
use Illuminate\Contracts\View\View;
// Add the Authenticatable interface for type hinting Auth::user()
use Illuminate\Contracts\Auth\Authenticatable;
// Assuming a User model exists for Auth::user()
use App\Models\User;

class VoteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function index(): \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
    {
        $data = [];
        $user = Auth::user();

        // 1. Null Safety Check for User
        if (is_null($user)) {
            return redirect('/')->with('warning', 'Login is needed.');
        }

        // FIX: Cast $user to App\Models\User (or equivalent) to resolve the `$user->id` and `->can()` errors.
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

        $member = Member::where('user_id', '=', $user->id)->first();
        /** @var \App\Models\Member|null $member */ // Explicit cast for Member model

        // 2. Null Safety Check for Member
        if (is_null($member)) {
            return redirect('/')->with('warning', 'Member record not found.');
        }

        $data['member_id'] = $member->id;

        $motion = Motion::where('status', '=', 'open')
            ->first();
        /** @var \App\Models\Motion|null $motion */ // Explicit cast for Motion model

        if (! is_null($motion)) {
            $data['current'] = $motion;
            $data['motion_id'] = $motion_id = $motion->id;
            $data['motion_member_id'] = $motion_member_id = $motion->member_id;

            $member_creator = Member::where('id', '=', $motion_member_id)
                ->first();
            /** @var \App\Models\Member|null $member_creator */ // Explicit cast for Member model

            // 3. Null Safety Check for Motion Creator
            if (! is_null($member_creator)) {
                $data['name'] = $member_creator->first_name.' '.$member_creator->last_name;
            } else {
                $data['name'] = 'Unknown Member';
            }

            if ($user->can('change any')) {
                $data['create'] = 'yes';
            } else {
                $data['create'] = 'no';
            }

            /**
             * Build table of all votes for this motion
             */
            $members = Member::whereIn('category', ['Member', 'Elder', 'Elder*', 'Member*'])
                ->where('status', '=', 'current')
                ->orderBy('last_name')
                ->get();

            $votes = [];
            foreach ($members as $loop_member) {
                /** @var \App\Models\Member $loop_member */ // Cast loop variable to Member
                $vote = [];
                $vote['name'] = $loop_member->first_name.' '.$loop_member->last_name;

                $vote_record = Vote::where('motion_id', '=', $motion->id)
                    ->where('member_id', '=', $loop_member->id)
                    ->first();
                /** @var \App\Models\Vote|null $vote_record */ // Explicit cast for Vote model

                $vote['vote'] = $vote_record;

                if (! is_null($vote['vote'])) {
                    $votes[] = $vote;
                }
            }
            $data['votes'] = $votes;

            return view('votes.index')->with('data', $data);
        }

        if ($user->can('change any')) {
            $data['create'] = 'yes';
        } else {
            $data['create'] = 'no';
        }

        return view('votes.nomo', compact('data'));
    }

    public function voted(Request $request): RedirectResponse
    {
        $member_id = (int) $request->input('member_id');
        $motion_id = (int) $request->input('motion_id');
        $vote_type = $request->input('vote');

        if ($vote_type == 'close') {
            $motion = Motion::where('id', '=', $motion_id)
                ->first();
            /** @var \App\Models\Motion|null $motion */ // Explicit cast for Motion model

            // 4. Null Safety Check for Motion
            if (is_null($motion)) {
                return redirect('/votes')->with('error', 'Motion to close not found.');
            }

            $motion->status = 'closed';
            $motion->save();

            return redirect('/votes')->with('message', 'Voting closed');
        } else {
            $vote = Vote::where('member_id', '=', $member_id)
                ->where('motion_id', '=', $motion_id)
                ->first();
            /** @var \App\Models\Vote|null $vote */ // Explicit cast for Vote model

            if (is_null($vote)) {
                $vote = new Vote;
            }

            $vote->member_id = $member_id;
            $vote->motion_id = $motion_id;
            $vote->vote = $vote_type;
            $vote->save();

            return redirect('/votes')->with('message', 'Vote '.$vote_type.' recorded');
        }
    }

    public function admin(): \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
    {
        $user = Auth::user();

        // 5. Null Safety Check for User in admin()
        if (is_null($user)) {
            return redirect('/')->with('warning', 'Admin permission is needed.');
        }

        // Cast $user to the correct type for static analysis
        /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

        if ($user->can('change any')) {
            $votes = Vote::orderBy('created_at')
                ->get();
            $change_any = $user->can('change any');

            return view('votes.admin', compact('votes', 'change_any', 'user'));
        }

        return redirect('/')->with('warning', 'Admin permission is needed.');

    }

    public function review(): View
    {
        $motions = Motion::where('status', 'closed')->get();

        return view('votes.review', compact('motions'));
    }

    /**
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function look(int $id)
    {
        $motion = Motion::find($id);
        /** @var \App\Models\Motion|null $motion */ // Explicit cast for Motion model

        // 6. Null Safety Check for Motion
        if (is_null($motion)) {
            return redirect('/votes')->with('error', 'Motion not found.')->withInput();
        }

        $member = Member::find($motion->member_id);
        /** @var \App\Models\Member|null $member */ // Explicit cast for Member model

        // 7. Null Safety Check for Member
        if (is_null($member)) {
            $name = 'Unknown Member';
        } else {
            $name = $member->first_name.' '.$member->last_name;
        }

        $motion->name = $name;

        $members = Member::whereIn('category', ['Member', 'Elder', 'Elder*', 'Member*'])
            ->orderBy('id')
            ->get();
        $votes = [];

        foreach ($members as $loop_member) {
            /** @var \App\Models\Member $loop_member */ // Cast loop variable to Member
            $voted = Vote::where('motion_id', '=', $motion->id)
                ->where('member_id', '=', $loop_member->id)
                ->first();
            /** @var \App\Models\Vote|null $voted */ // Explicit cast for Vote model

            if ($voted) {
                $vote = [];
                $vote['name'] = $loop_member->first_name.' '.$loop_member->last_name;
                $vote['vote'] = $voted->vote;
                $votes[] = $vote;
            }
        }
        $motions = Motion::where('status', 'closed')->get();

        return view('votes.look', compact('motion', 'votes', 'motions'));

    }

    public function reopen(int $id): RedirectResponse
    {
        $motion = Motion::find($id);
        /** @var \App\Models\Motion|null $motion */ // Explicit cast for Motion model

        // 8. Null Safety Check for Motion
        if (is_null($motion)) {
            return redirect('/votes')->with('error', 'Motion to reopen not found.');
        }

        $motion->status = 'open';
        $motion->save();

        return redirect('/votes')->with('message', 'Motion '.$id.' reopened');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): RedirectResponse
    {
        $motion = new Motion;
        /** @var \App\Models\Motion $motion */ // Explicit cast for Motion object

        $motion->status = 'open';

        // Input Consistency Fix
        $motion->member_id = (int) $request->input('member_id');
        $motion->item = (string) $request->input('motion');

        $motion->motion_date = date('Y-m-d');
        $motion->save();

        return redirect('/votes')->with('message', 'Motion created');
    }

    /**
     * Store a newly created resource in storage.
     *
     * PHPDoc Fix: The method calls dd() which returns mixed/void.
     * @return mixed
     */
    public function store(Request $request)
    {
        dd('at store');
    }

    /**
     * Display the specified resource.
     *
     * PHPDoc Fix: The method calls dd() which returns mixed/void.
     * @return mixed
     */
    public function show(Vote $vote)
    {
        dd('at show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * PHPDoc Fix: The method calls dd() which returns mixed/void.
     * @return mixed
     */
    public function edit(Vote $vote)
    {
        dd('at edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * PHPDoc Fix: The method calls dd() which returns mixed/void.
     * @return mixed
     */
    public function update(Request $request, Vote $vote)
    {
        dd('at update');
    }

    /**
     * Remove the specified resource from storage.
     *
     * PHPDoc Fix: The method calls dd() which returns mixed/void.
     * @return mixed
     */
    public function destroy(Vote $vote)
    {
        dd('at destroy');
    }
}
