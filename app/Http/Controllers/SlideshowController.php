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
        // 1. Requirement: Ensure user is logged in for most actions [cite: 2026-01-06]
        $this->middleware('auth')->except([
            'index', 'list', 'one', 'year', 'view'
        ]);

        // 2. Requirement: Only Admin OR senior_druid can create/edit/delete [cite: 2026-01-06]
        // Note: We except the same public methods so guests aren't blocked from the gallery.
        $this->middleware('role:admin|senior_druid')->except([
            'index', 'list', 'one', 'year', 'view'
        ]);
    }

    public function index(Request $request)
    {
        // 1. Capture the selection state (The "Parallel" variables)
        $selectedYear = $request->get('year');
        $selectedName = $request->get('name');

        // 2. Your existing logic to fetch dropdown data
        // (Assuming these are already in your controller)
        // 2. Your updated logic to fetch the full objects
        $activeYears = Slideshow::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

// Initialize as an empty collection so the @foreach doesn't break
        $activeSlideshows = collect();

        if ($selectedYear) {
            // We fetch the full records so the View can access $slideshow->id and $slideshow->name
            $activeSlideshows = Slideshow::where('year', $selectedYear)
                ->orderBy('sequence', 'asc')
                ->get();
        }

// 3. Logic to determine if a specific record is "Selected"
        $choiceId = $request->get('choice_id'); // Get the ID directly from the new dropdown value

        if ($choiceId) {
            // We check if it exists just to be safe
            $slideshow = Slideshow::find($choiceId);

            if ($slideshow) {
                // 🚦 The Public Redirect Interceptor
                $isStaff = auth()->user()?->hasAnyRole(['admin', 'senior_druid']);

                if (!$isStaff) {
                    // Redirect using the exact ID for precision
                    return redirect()->route('slideshows.view', ['id' => $slideshow->id]);
                }
            }
        }
// 4. Return everything to the view (Only Staff reach this if a choiceId is set)
// 4. Update the compact() to include the new variable
        return view('slideshows.index', compact(
            'selectedYear',
            'selectedName',
            'activeYears',
            'activeSlideshows', // Changed from activeNames
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
    public function create()
    {
        // Fetch the master list using your newly corrected helper
        $names = $this->getSection99Names();
        return view('slideshows.create', compact('names'));
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
    public function edit(Slideshow $slideshow): View
    {
        // No findOrFail needed; Laravel handles the 404 automatically
        return view('slideshows.edit', compact('slideshow'));
    }
    /**
     * View a slideshow.
     */
    public function view(int $id): View
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
            ->where('name', 'names')
            ->first();

        // Explode and clean the comma-delimited string
        return $nameElement
            ? array_map('trim', explode(',', $nameElement->item))
            : ['SYSTEM ERROR: Ritual Names Not Found']; // High Day fallback
    }


}
