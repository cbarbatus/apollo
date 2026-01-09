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
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */
        $announcement = Announcement::findOrFail($id);
        /** @var \App\Models\Announcement $announcement */

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $locations */
        $locations = Venue::all();

        $element = Element::where('name', 'RitualNames')->first();
        /** @var \App\Models\Element|null $elements */

        // Handle possible null element and property access
        $rituals = ($element && !empty($element->item))
            ? array_map('trim', explode(',', $element->item))
            : [];

        return view('announcements.edit', compact('announcement', 'locations', 'rituals'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Announcement $announcement): RedirectResponse
    {
        // Capture the inputs, defaulting to current values if the request is empty
        $announcement->name         = (string) ($request->input('name') ?? $announcement->name);
        $announcement->when         = (string) ($request->input('when') ?? $announcement->when);
        $announcement->venue_name   = (string) ($request->input('venue_name') ?? $announcement->venue_name);
        $announcement->year         = (string) ($request->input('year') ?? $announcement->year);
        $announcement->picture_file = (string) ($request->input('picture_file') ?? $announcement->picture_file);

        // Explicitly handling the Trix fields
        $announcement->summary      = (string) ($request->input('summary') ?? $announcement->summary);
        $announcement->notes        = (string) ($request->input('notes') ?? $announcement->notes);

        $announcement->save();

        return redirect()->route('announcements.index')
            ->with('success', "Announcement #{$announcement->id} was updated");
    }

    /**
     * Remove elements and add elements for the announcement.
     */
    /**
     * Copy announcement to front page by populating related Element records.
     */
    public function activate(int $id): RedirectResponse
    {
        // Authorization check (Consider moving this to middleware or a Policy later)
        if (!Auth::user()?->hasRole(['admin', 'SeniorDruid'])) {
            abort(403);
        }

        $announcement = Announcement::findOrFail($id);
        $venue = Venue::where('name', $announcement->venue_name)->first();

        if (!$venue) {
            return redirect()->back()->with('error', "Venue not found.");
        }

        // Define the Map: 'element_name' => 'new_value'
        $dataMap = [
            'picture' => '<img alt="Ritual Picture" src="/img/'.$announcement->picture_file.'" style="display:block; margin-left:auto; margin-right:auto; width:100%; height:auto; border:5px groove black;" >',
            'summary' => $announcement->summary,
            'when'    => 'WHEN: ' . Carbon::parse($announcement->when)->format('M j, Y \a\t g:i A'),
            'where'   => 'WHERE: ' . $venue->title,
            'notes'   => $announcement->notes,
            'driving' => 'DRIVING DIRECTIONS: ' . $venue->directions,
            'map'     => 'Here is a Google Map to the ritual site. <a href="'.$venue->map_link.'">GOOGLE MAP LINK</a>',
        ];

        // Bulk processing
        Element::where('section_id', 5)
            ->get()
            ->each(function ($element) use ($dataMap) {
                if (isset($dataMap[$element->name])) {
                    $element->update(['item' => $dataMap[$element->name]]);
                }
            });

        return redirect('/')->with('success', "Home page updated with {$announcement->name}");
    }
    /**
     * Show the Upload Form for a specific Announcement
     */
    public function uploadpic(int $id): View
    {
        $announcement = Announcement::findOrFail($id);

        // Standard Phoenix Naming: 2024_Spring_Festival.jpg
        $picname = $announcement->year . '_' . str_replace(' ', '_', $announcement->name) . '.jpg';

        return view('announcements.uploadpic', compact('announcement', 'picname'));
    }

    /**
     * Process the Picture Upload
     */
    public function storepic(Request $request): RedirectResponse
    {
        $request->validate([
            'file' => 'required|image|mimes:jpeg,jpg|max:2048',
            'id' => 'required|exists:announcements,id'
        ]);

        $announcement = Announcement::findOrFail($request->id);
        $filename = $request->picname;

        // Use base_path or public_path depending on your local vs server setup
        // Since you use public_html/Img, we target that directly:
        $request->file('file')->move(public_path('Img'), $filename);

        $announcement->picture_file = $filename;
        $announcement->save();

        return redirect()->back()
            ->with('success', "Picture for Announcement {$announcement->id} updated in /Img.");
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $announcement = Announcement::findOrFail($id);
        $idLabel = $announcement->id; // Grab the ID before it's gone

        $announcement->delete();

        // Redirect to the index with the success message
        return redirect()->route('announcements.index')
            ->with('success', "Announcement {$idLabel} was deleted.");
    }
}
