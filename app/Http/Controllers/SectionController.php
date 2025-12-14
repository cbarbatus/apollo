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
    public function edit(int $id): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            // Cast $user to the expected type
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

            if ($user && $user->hasRole(['admin', 'SeniorDruid'])) {

                // Explicitly cast to Section model for better analysis
                $section = Section::query()->findOrFail($id);
                /** @var \App\Models\Section $section */

                // FIX: Added 'int' key type to resolve the PHPStan error on this line.
                $elements = Element::query()->where('section_id', '=', $id)->orderBy('sequence')->get();
                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Element> $elements */

                return view('sections.edit', compact('section', 'elements'));
            }
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
    public function store(Request $request): RedirectResponse
    {
        $section = new Section;
        /** @var \App\Models\Section $section */

        // Switched from request('key') helper to $request->input('key') for type safety
        $section->title = (string) $request->input('title', '');
        $section->name = (string) $request->input('name', '');
        $section->sequence = (string) $request->input('sequence', '');

        // Note: The 'slug' property, although in $fillable, is not set here. Laravel will default it to null/empty if not required.

        $section->save();

        return redirect('/sections');

    }

    /**
     * Turn on the showit flag.
     */
    public function on(Request $request, int $id): RedirectResponse
    {
        $sections = Section::orderBy('sequence')->get();
        // FIX: Added 'int' key type to resolve the PHPStan error on this line.
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Section> $sections */

        foreach ($sections as $section) {
            /** @var \App\Models\Section $section */
            $section->showit = 0;
            $section->save();
        }

        $thesection = Section::findOrFail($id);
        /** @var \App\Models\Section $thesection */ // Explicit cast for single model instance

        $thesection->showit = 1;
        $thesection->save();

        /*
        var_dump("stopped at section.on", $id, $thesection); exit();
        */
        return redirect('/');
    }

    /**
     * Turn off the showit flag.
     */
    public function off(Request $request, int $id): RedirectResponse
    {
        $section = Section::findOrFail($id);
        /** @var \App\Models\Section $section */ // Explicit cast for single model instance

        $section->showit = 0;
        $section->save();

        return redirect('/');
    }


    public function update(Request $request, int $id): RedirectResponse
    {
        $section = Section::query()->findOrFail($id);
        /** @var \App\Models\Section $section */ // Explicit cast for single model instance

        // Switched from request('key') helper to $request->input('key') for type safety
        $section->title = (string) $request->input('title', $section->title);
        $section->name = (string) $request->input('name', $section->name);
        $section->sequence = (string) $request->input('sequence', $section->sequence);

        $section->save();

        return redirect('/sections');
    }

    /**
     * Before destroy, ask sure.
     */
    public function sure(int $id): View
    {
        return view('/sections.sure', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $section = Section::findOrFail($id);
        $section->delete();

        return redirect('/sections')->with('success', 'section '.$section->id.' was deleted');
    }


}
