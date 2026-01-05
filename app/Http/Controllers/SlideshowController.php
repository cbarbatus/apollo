<?php

namespace App\Http\Controllers;

use App\Models\Slideshow;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;

class SlideshowController extends Controller
{
    public function __construct()
    {
        // 1. Apply 'auth' middleware to ALL methods in this controller by default.
        // This solves the session timeout/null user error for 95% of your functions.
        $this->middleware('auth')->except([
            'list',        // Public: display list of years
            'one',         // Public: display one show
            'year',         // Public: display one year
            'view',         // Public: display one year
        ]);

    }


    public function index(Request $request)
    {
        // 1. Capture the selection state (The "Parallel" variables)
        $selectedYear = $request->get('year');
        $selectedName = $request->get('name');

        // 2. Your existing logic to fetch dropdown data
        // (Assuming these are already in your controller)
        $activeYears = Slideshow::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');
        $activeNames = [];

        if ($selectedYear) {
            $activeNames = Slideshow::where('year', $selectedYear)->orderBy('name')->pluck('name');
        }

        // 3. Logic to determine if a specific record is "Selected"
        $choiceId = null;
        if ($selectedYear && $selectedName) {
            $slideshow = Slideshow::where('year', $selectedYear)
                ->where('name', $selectedName)
                ->first();
            $choiceId = $slideshow ? $slideshow->id : null;
        }

        // 4. Return everything to the view
        return view('slideshows.index', compact(
            'selectedYear',
            'selectedName',
            'activeYears',
            'activeNames',
            'choiceId'
        ));
    }
        /**
     * Display a listing of the resource.
     */
    public function search(Request $request)
    {
        $year = $request->input('year');
        $name = $request->input('name');
        $admin = $request->input('admin', 0);

        $query = Slideshow::query();

        if ($year) { $query->where('year', $year); }
        if ($name) { $query->where('name', $name); }

        $slideshows = $query->orderBy('sequence')->get();

        // If exactly one match exists (the "One" logic), go there directly
        if ($slideshows->count() === 1 && $year && $name) {
            return $admin
                ? redirect()->route('slideshows.edit', $slideshows->first()->id)
                : redirect()->route('slideshows.show', $slideshows->first()->id);
        }

        // Otherwise, show whatever we found (even if 0) on a results page
        return view('slideshows.index', [
            'slideshows' => $slideshows,
            'admin' => $admin,
            'activeYears' => Slideshow::distinct()->pluck('year'), // For the sidebar/form
            'names' => $this->getSection99Names() // Helper to get clean names
        ]);
    }



    /**
     * Show the form for creating a new resource.
     *
     * @return View|RedirectResponse  // FIX 1: Corrected PHPDoc return type
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            // FIX 2: Added PHPDoc cast to recognize hasRole()
            if ($user->hasRole('admin')) {
                return view('slideshows.create');
            }
        }

        return redirect('/')->with('warning', 'Login is needed.');
    }

    /**
     * Store a newly created resource in storage.
     */

        public function store(Request $request)
        {
        $request->validate([
            'year' => 'required|integer',
            'google_id' => 'required',
            'name' => [
                'required',
                'string',
                // This rule says: "Look in slideshows table where name = X AND year = Y"
                \Illuminate\Validation\Rule::unique('slideshows')->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                }),
            ],
        ], [
            'name.unique' => "Stop! A slideshow for {$request->name} {$request->year} already exists."
        ]);

        $slideshow = new Slideshow;
        /** @var \App\Models\Slideshow $slideshow */

        // Use $request->input()
        $item = $request->input('year');
        if (!is_string($item)) $item = '';
        $slideshow->year = $item;

        // Use $request->input()
        $item = $request->input('name');
        if (!is_string($item)) $item = '';
        $slideshow->name = $item;

        // Use $request->input()
        $item = $request->input('title');
        if (!is_string($item)) $item = '';
        $slideshow->title = $item;

        // Use $request->input()
        $item = $request->input('google_id');
        if (!is_string($item)) $item = '';
        $slideshow->google_id = $item;

        // Use $request->input()
        $item = $request->input('sequence');
        if (!is_string($item)) $item = '';
        $slideshow->sequence = $item;

        $slideshow->save();

        return redirect()->route('slideshows.index')
            ->with('success', "Slideshow '{$slideshow->name}' created successfully!");
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $slideshow = Slideshow::findOrFail($id);
        /** @var \App\Models\Slideshow $slideshow */

        return view('slideshows.edit', compact('slideshow'));
    }

    /**
     * View a slideshow.
     */
    public function show(int $id): View
    {
        $slideshow = Slideshow::findOrFail($id);
        /** @var \App\Models\Slideshow $slideshow */

        return view('slideshows.view', compact('slideshow'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $slideshow = Slideshow::query()->findOrFail($id);
        /** @var \App\Models\Slideshow $slideshow */

        // Use $request->input()
        $item = $request->input('year');
        if (!is_string($item)) $item = '';
        $slideshow->year = $item;

        // Use $request->input()
        $item = $request->input('name');
        if (!is_string($item)) $item = '';
        $slideshow->name = $item;

        // Use $request->input()
        $item = $request->input('title');
        if (!is_string($item)) $item = '';
        $slideshow->title = $item;

        // Use $request->input()
        $item = $request->input('google_id');
        if (!is_string($item)) $item = '';
        $slideshow->google_id = $item;

        // Use $request->input()
        $item = $request->input('sequence');
        if (!is_string($item)) $item = '';
        $slideshow->sequence = $item;

        $slideshow->save();

        return redirect()->route('slideshows.index', ['year' => $slideshow->year, 'name' => $slideshow->name])
            ->with('success', "Slideshow updated successfully.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $slideshow = Slideshow::findOrFail($id);
        $slideshow->delete();

        // Apollo-standard named route
        return redirect()->route('slideshows.index')->with('success', 'Slideshow was deleted successfully.');
    }


    private function getSection99Names()
    {
        // Fetch the Master List from Section 99 via the Element model
        $nameElement = \App\Models\Element::where('section_id', 99)
            ->where('name', 'RitualNames')
            ->first();

        // Explode and clean the comma-delimited string
        return $nameElement
            ? array_map('trim', explode(',', $nameElement->item))
            : ['Samhain', 'Yule', 'Imbolc', 'Beltaine']; // High Day fallback
    }


}
