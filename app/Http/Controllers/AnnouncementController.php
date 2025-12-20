<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Element;
use App\Models\Venue;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon; // Use Carbon for cleaner date/time handling

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user->hasRole(['admin', 'SeniorDruid'])) {
                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Announcement> $announcements */
                $announcements = Announcement::all();

                return view('announcements.index', compact('announcements'));
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
            if ($user->hasRole(['admin', 'SeniorDruid'])) {
                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $locations */
                $locations = Venue::all();

                $elements = Element::where('name', '=', 'names')->first();
                /** @var \App\Models\Element|null $elements */

                // Handle possible null element and property access
                $rituals = ($elements && is_string($elements->item)) ? explode(',', $elements->item) : [];
                return view('announcements.create', compact('locations', 'rituals'));
            }
        }

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $announcement = new Announcement;
        /** @var \App\Models\Announcement $announcement */

        // Switched to $request->input() for type safety and consistency
        $item = $request->input('name');
        $announcement->name = (string) ($item ?? '');

        $item = $request->input('when');
        $announcement->when = (string) ($item ?? '');

        // Using 'venue_name' property
        $item = $request->input('venue_name');
        $announcement->venue_name = (string) ($item ?? '');

        $item = $request->input('notes');
        $announcement->notes = (string) ($item ?? '');

        // Handle year, summary, picture_file if they are required/present in the form
        $announcement->year = (string) ($request->input('year') ?? date('Y'));
        $announcement->summary = (string) ($request->input('summary') ?? '');
        $announcement->picture_file = (string) ($request->input('picture_file') ?? '');


        $announcement->save();

        return redirect('/announcements');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Announcement  $announcement
     */
    public function show(Announcement $announcement): void
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
            if ($user->hasRole(['admin', 'SeniorDruid'])) {

                $announcement = Announcement::findOrFail($id);
                /** @var \App\Models\Announcement $announcement */

                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $locations */
                $locations = Venue::all();

                $elements = Element::where('name', '=', 'names')->first();
                /** @var \App\Models\Element|null $elements */

                // Handle possible null element and property access
                $rituals = ($elements && is_string($elements->item)) ? explode(',', $elements->item) : [];

                return view('announcements.edit', compact('announcement', 'locations', 'rituals'));
            }
        }

        return redirect('/');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        // NO MANUAL FIND IS NEEDED! $announcement is already the correct model instance.
        // $announcement = Announcement::find($id); <-- DELETE THIS LINE

        // Your update logic (simplified):
        $announcement->name = (string) ($request->input('name') ?? $announcement->name);
        $announcement->when = (string) ($request->input('when') ?? $announcement->when);
        $announcement->venue_name = (string) ($request->input('venue_name') ?? $announcement->venue_name);
        $announcement->notes = (string) ($request->input('notes') ?? $announcement->notes);
        $announcement->year = (string) ($request->input('year') ?? $announcement->year);
        $announcement->summary = (string) ($request->input('summary') ?? $announcement->summary);
        $announcement->picture_file = (string) ($request->input('picture_file') ?? $announcement->picture_file);

        $announcement->save();
        // 🟢 CRITICAL CHANGE: Use the Named Route for reliability 🟢
        return redirect()->route('announcements.index')->with('success', 'Announcement '.$announcement->id.' was updated');
    }

    /**
     * Remove elements and add elements for the announcement.
     */
    /**
     * Copy announcement to front page by populating related Element records.
     */
    public function activate(int $id): View|RedirectResponse
    {
        /** @var \App\Models\User|null $user */
        $user = Auth::user();

        if ($user && $user->hasRole(['admin', 'SeniorDruid'])) {

            $announcement = Announcement::findOrFail($id);
            /** @var \App\Models\Announcement $announcement */

            // 1. Fetch Venue by name
            // Note: Venue::where('name', ...) is used in the working version.
            // We'll stick to this field name, assuming it's correct for the Venue model.
            $venue = Venue::where('name', $announcement->venue_name)->first();

            // Safety check: ensure venue exists before attempting to access properties
            if (!$venue) {
                return redirect('/')->with('error', "Venue '{$announcement->venue_name}' not found for this announcement.");
            }

            $cover_pic = '<img alt="Ritual Picture" src="/img/'.$announcement->picture_file.'" style="display:block; margin-left:auto; margin-right:auto; width:100%; height:auto; border:5px groove black;" >';
            $driving = $venue->directions;
            $map = $venue->map_link;

            // 2. Fetch all front-page elements (section_id = 5)
            $elements = Element::where('section_id', 5)->get();

            // 3. Process and save data to Element records
            foreach ($elements as $element) {

                // Clean up the ugly switch statement by using a lookup array and consistent logic
                switch ($element->name) {
                    case 'picture':
                        $element->item = $cover_pic;
                        break;
                    case 'summary':
                        $element->item = $announcement->summary;
                        break;
                    case 'when':
                        // MODERNIZED TIME CONVERSION: Use Carbon for reliable time formatting
                        try {
                            $carbonDate = Carbon::parse($announcement->when);
                            $whenap = $carbonDate->format('M j, Y \a\t g:i A'); // Example: "Dec 15, 2025 at 7:00 PM"
                            $element->item = 'WHEN: ' . $whenap;
                        } catch (\Exception $e) {
                            $element->item = 'WHEN: Invalid Date Format';
                        }
                        break;
                    case 'where':
                        $element->item = 'WHERE: ' . $venue->title;
                        break;
                    case 'notes':
                        $element->item = $announcement->notes;
                        break;
                    case 'driving':
                        $element->item = 'DRIVING DIRECTIONS: ' . $driving;
                        break;
                    case 'map':
                        $element->item = 'Here is a Google Map to the ritual site. <a href=" ' . $map . ' ">GOOGLE MAP LINK</a>';
                        break;
                }
                $element->save();
            }
        }

        // 4. Redirect to the homepage after activation
        return redirect('/');
    }
    /**
     * Before destroy, ask sure.
     */
    public function sure(int $id): View
    {
        return view('/announcements.sure', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);
        /** @var \App\Models\Announcement $announcement */

        $announcement->delete();

        return redirect('/announcements/')->with('success', 'Announcement '.$announcement->id.' was deleted');
    }
}
