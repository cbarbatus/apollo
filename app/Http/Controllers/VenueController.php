<?php

namespace App\Http\Controllers;

use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
// Import the User model for PHPDoc casting
use App\Models\User;

class VenueController extends Controller
    // Cast $user to the expected type to resolve static analysis errors
    /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View | RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

            if ($user && $user->hasRole(['admin', 'SeniorDruid'])) {
                $venues = Venue::all();
                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $venues */

                return view('venues.index', compact('venues'));
            }
        }

        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View | RedirectResponse
    {
        $user = Auth::user();
        // FIX: Grouped the intersection type with parentheses to resolve phpDoc.parseError
        /** @var (\App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable)|null $user */

        if (is_null($user)) {
            return redirect()->to('/')->with('warning', 'Login is needed.');
        }

        return view('venues.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $venue = new Venue;
        /** @var \App\Models\Venue $venue */

        // Switched from request('key') helper to $request->input('key') and explicitly cast to (string)
        $venue->name = (string) $request->input('name', '');
        $venue->title = (string) $request->input('title', '');
        $venue->address = (string) $request->input('address', '');
        $venue->map_link = (string) $request->input('map_link', '');
        $venue->directions = (string) $request->input('directions', '');

        $venue->save();

        return redirect('/venues');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $venue = Venue::findOrFail($id);
        /** @var \App\Models\Venue $venue */

        return view('venues.show', compact('venue'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $venue = Venue::findOrFail($id);
        /** @var \App\Models\Venue $venue */

        return view('venues.edit', compact('venue'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $venue = Venue::findOrFail($id);

        // 1. Validate the data (this fixes the BadMethodCallException)
        $validated = $request->validate([
            'name'       => 'required|string',
            'title'      => 'required|string',
            'address'    => 'nullable|string',
            'map_link'   => 'nullable|string', // Corrected to match DB
            'directions' => 'nullable|string',
        ]);

        // 2. Update and Save in one go
        // This is the Gold Standard: it validates, updates, and saves to DB
        $venue->update($validated);
        $venue->save(); // This forces the DB write
        return redirect()->route('venues.index')
            ->with('status', "Venue '{$venue->name}' updated successfully.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venue $venue)
    {
        $venue->delete();

        return redirect()->back()->with('success', 'Venue was successfully deleted.');
    }
}
