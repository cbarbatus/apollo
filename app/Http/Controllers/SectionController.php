<?php

namespace App\Http\Controllers;

use App\Models\Element;
use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
// Explicitly import the User model for PHPDoc casting
use App\Models\User;
use App\Http\Requests\SectionRequest;
use Illuminate\Support\Facades\DB;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Cast $user to the expected type to resolve static analysis errors
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

            if ($user && $user->hasRole(['admin', 'SeniorDruid'])) {

                $sections = Section::query()->orderBy('sequence')->get();

                return view('sections.index', compact('sections'));
            }
        }

        return redirect('/');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id): View|RedirectResponse
    {
        if (Auth::check() && Auth::user()->hasRole(['admin', 'SeniorDruid'])) {

            // Manually fetch the section using the ID from the URL
            $section = Section::findOrFail($id);

            $elements = Element::where('section_id', $section->id)
                ->orderBy('sequence')
                ->get();

            return view('sections.edit', compact('section', 'elements'));
        }

        return redirect('/');
    }


    /**
     * Show the form for creating a section.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Cast $user to the expected type
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

            if ($user && $user->hasRole(['admin', 'SeniorDruid'])) {

                return view('sections.create');
            }
        }

        return redirect('/');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // 1. Validation (This is usually why a page "sticks" - it fails and reloads)
        $request->validate([
            'name'     => 'required|string',
            'title'    => 'required|string',
            'sequence' => 'required|integer',
        ]);

        // 2. Create the Section
        // Using the Model directly is cleaner than the old property-by-property way
        $section = \App\Models\Section::create([
            'name'     => $request->name,
            'title'    => $request->title,
            'sequence' => $request->sequence,
          ]);

        // 3. Redirect to the Edit page of the NEW section
        return redirect('sections/' . $section->id . '/edit')
            ->with('status', 'Section "' . $section->name . '" created successfully!');
    }

    /**
     * Turn on the showit flag.
     */
    public function on(Request $request, Section $section): RedirectResponse
    {
        // No need for a loop or findOrFail($id).
        // Just update the specific section provided by the route.
        $section->showit = 1;
        $section->save();

        return redirect('/');
    }
    /**
     * Turn off the showit flag.
     */
    public function off(Request $request, Section $section): RedirectResponse
    {

        $section->showit = 0;
        $section->save();

        return redirect('/');
    }


    public function updatePost(Request $request, $id)
    {
        // 1. Validate only what belongs to the Section
        $request->validate([
            'name'     => 'required|string',
            'title'    => 'required|string',
            'sequence' => 'required|integer',
        ]);

        // 2. Update the sections table (No 'item' here!)
        DB::table('sections')
            ->where('id', $id)
            ->update([
                'name'       => $request->input('name'),
                'title'      => $request->input('title'),
                'sequence'   => $request->input('sequence'),
                'updated_at' => now(),
            ]);

        // 3. THE APOLLO EXIT: Return to the list to break the "funky" loop
        return redirect('/sections')
            ->with('status', 'Section "' . $request->input('name') . '" updated successfully.');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $section = Section::findOrFail($id);

        // Check if there are any elements linked to this section
        if ($section->elements()->exists()) {
            return back()->with('error', "Cannot delete '{$section->title}': You must delete or move its elements first.");
        }

        $section->delete();

        return redirect('/sections')->with('status', 'Section deleted successfully.');
    }

}
