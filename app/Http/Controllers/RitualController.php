<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Ritual;
use App\Models\Slideshow;
use App\Models\Venue;
use App\Models\Element;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class RitualController extends Controller
{

    /**
     * Display a listing of the resource if not admin.
     */
    public function list(bool $admin): View|RedirectResponse
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Ritual> $rituals */
        $rituals = Ritual::all();

        $years = DB::table('rituals')
            ->select('year')
            ->groupBy('year')
            ->orderby('year', 'DESC')
            ->get()->toArray();
        $activeYears = [];
        foreach ($years as $year) {
            // $year is a generic object from DB::table('rituals')->get()
            if (property_exists($year, 'year')) {
                $activeYears[] = $year->year;
            }
        }

        return view('rituals.index', compact('rituals', 'activeYears', 'admin'));
    }

    /**
     * Display a listing of one year if not admin.
     */
    public function year(string $year, bool $admin): View
    {
        $rituals = Ritual::query()
            ->where('year', $year)
            ->get();
        return view('rituals.year', compact('rituals', 'year', 'admin'));
    }


    public function one(Request $request)  : View|RedirectResponse
    {
        // FIX: Use $request->input() and sanitize
        $year = $request->input('year');
        $name = $request->input('name');
        $admin = $request->input('admin', false);

        if (!is_string($year) || !is_string($name)) {
            return redirect('/rituals/0/list')->with('message', 'Invalid ritual parameters.');
        }

        /** @var \App\Models\Ritual|null $ritual */
        $ritual = Ritual::query()
            ->where('year', $year)
            ->where( 'name', $name)
            ->first();

        if (!$ritual) {
            return redirect('/rituals/0/list')->with('message', 'Ritual not defined');
        }

                /** @var \App\Models\Announcement|null $announcement */
        $announcement = Announcement::query()
            ->where('year', '=', $year)
            ->where('name', '=', $name)
            ->first();


/* dd("end of one", $ritual, $ritual->year); */
        if ($admin)
            return view('rituals.show', compact('ritual'));


            /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $locations */

        $slideshow = \App\Models\Slideshow::where('year', $ritual->year)
            ->where('name', $ritual->name)
            ->first();

        $lit_file = $_SERVER['DOCUMENT_ROOT'] . "/liturgy/" . $ritual->year . "_" . $ritual->name . ".htm";
        if (!file_exists($lit_file)) {
            $lit_file = '';
        }
        $venue_title = '';

        if ($announcement !== null) {
            // Accessing $announcement->venue_name is now safe due to corrected PHPDoc
            $venue = Venue::query()->where('name', '=', (string)$announcement->venue_name)->first();
            /** @var \App\Models\Venue|null $venue */

            if ($venue) {
                // Accessing $venue->title is now safe due to corrected PHPDoc
                $venue_title = (string)$venue->title;
            }
        }

        return view('rituals.display', compact('ritual', 'slideshow', 'announcement', 'venue_title', 'lit_file'));
    }

    /**
     * Display one ritual.
     */
    public function display (int $id): View
    {
        $ritual = Ritual::query()->findOrFail($id);
        /** @var \App\Models\Ritual $ritual */

        /**
         * compute normalized or special liturgy exists
         */

        $lit_file = $_SERVER['DOCUMENT_ROOT'] . "/liturgy/" . $ritual->year . "_" . $ritual->name . ".htm";
        if (!file_exists($lit_file)) $lit_file = '';

        $sid = DB::table('slideshows')
            ->select()
            ->where([['year', '=', $ritual->year],
                ['name', '=', $ritual->name]])
            ->value('id');
        $slideshow = Slideshow::find($sid);
        /** @var \App\Models\Slideshow|null $slideshow */

        $announcement = Announcement::query()->where('year', '=', $ritual->year)
            ->where('name', '=', $ritual->name)
            ->first();
        // FIX: Corrected PHPDoc syntax to resolve 'undefined property' errors on lines 113/114
        /** @var \App\Models\Announcement|null $announcement */

        $venue_title = '';
        if ($announcement !== null) {
            // Accessing $announcement->venue_name is now safe due to corrected PHPDoc
            $venue = Venue::query()->where('name', '=', (string)$announcement->venue_name)->first();
            /** @var \App\Models\Venue|null $venue */

            if ($venue !== null) {
                // Accessing $venue->title is now safe due to corrected PHPDoc
                $venue_title = (string)$venue->title;
            }
        }

        return view('rituals.display', compact('ritual', 'slideshow', 'announcement', 'venue_title', 'lit_file'));
    }

    public function text(int $id) :  View|RedirectResponse
    {
        $rite = Ritual::query()->findOrFail($id);
        /** @var \App\Models\Ritual $rite */

        $theFile = $_SERVER['DOCUMENT_ROOT'] . "/liturgy/" . $rite->year . "_" . $rite->name . ".htm";
        $text = '';

        $fp = @fopen($theFile, 'rb');
        if ($fp) {
            $text = fread($fp, (int)filesize($theFile));
            fclose($fp);
        } else {
            $theFile = $theFile.'.htm';
            $fp = @fopen($theFile, 'rb');
            if ($fp) {
                $text = fread($fp, (int)filesize($theFile));
                fclose($fp);
            } else {
                return redirect('/rituals/0/list')->with('message', 'Ritual text missing');
            }
        }

        // Parse the HTML file into array of lines $contents
        $charset = strpos($text, 'charset=');

        $type = 'utf-8';
        if ($charset !== false) {
            $endset = strpos(substr($text, $charset), '"');
            if ($endset !== false) {
                $start = $charset + 8;
                $size = $endset;
                $type = strtolower(substr($text, $start, $size));
            }
        }

        if (substr($type, 0, 3) != 'utf') {
            // Fragile encoding logic maintained, but applied safely
            if ($charset !== false && isset($size)) {
                $utftext = substr_replace($text, 'utf-8          ', $charset + 8, $size);
                $utftext = utf8_encode($utftext);
            } else {
                $utftext = utf8_encode($text);
            }
        } else {
            $utftext = $text;
        }

        $contents = preg_split("/\r?\n|\r/", $utftext);
        if ($contents === false) {
            return redirect('/rituals/0/list')->with('message', 'Ritual text parsing failed');
        }

        $count = count($contents);
        $i = 0; // Start index
        $l = $count; // End index

        // Find <body>
        for ($i = 0; $i < $count; $i++) {
            if (strtolower(substr($contents[$i], 0, 5)) == '<body') {
                break;
            }
        }

        // Find </body>
        for ($l = $i + 1; $l < $count; $l++) {
            if (strtolower(substr($contents[$l], 0, 4)) == '</bo') {
                break;
            }
        }

        $parms = [$i + 1, $l, $contents];

        return view('rituals.text', compact('parms'));
    }


    public function create() : View|RedirectResponse
    {
        // FIX: Inverted and incomplete authorization logic.
        // Access should be restricted to 'admin' users.
        $user = Auth::user();
        // FIX: Narrowed PHPDoc type hint to \App\Models\User|null to resolve method.notFound error for hasRole()
        /** @var \App\Models\User|null $user */

        if (!$user || !$user->hasRole('admin')) {
            return redirect('/')->with('warning', 'Access denied. Administrator login is required.');
        }

        $elements = Element::query()->where('name', '=', 'names')->first();
        /** @var \App\Models\Element|null $elements */

        $ritualNames = [];
        if ($elements !== null)
            $ritualNames = explode(',', (string)$elements->item);

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $venues */
        $venues = Venue::all();
        $venue_names = [];
        foreach ($venues as $venue) {
            $venue_names[] = $venue->name;
        }

        $elements = Element::query()->where('name', '=', 'cultures')->first();
        /** @var \App\Models\Element|null $elements */

        $cultures = [];
        if ($elements !== null) {
            $cultures = explode(',', (string)$elements->item);
        }

        return view('rituals.create', compact('venue_names', 'ritualNames', 'cultures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $ritual = new Ritual;
        /** @var \App\Models\Ritual $ritual */

        // FIX: Refactored input handling to use dedicated variables and ternary operators
        // This resolves the function.alreadyNarrowedType error caused by reusing $item.

        $year = $request->input('year');
        $ritual->year = is_string($year) ? $year : '';

        $name = $request->input('name');
        $ritual->name = is_string($name) ? $name : '';

        $ritual->liturgy_base = $ritual->year.'_'.$ritual->name;

        $culture = $request->input('culture');
        $ritual->culture = is_string($culture) ? $culture : '';

        /* check that ritual year and name are not duplicated */
        $previous = Ritual::where('year', '=', $ritual->year)
            ->where('name', '=', $ritual->name)
            ->first();
        /** @var \App\Models\Ritual|null $previous */

        if ($previous !== null) {
            return back()->with('error', 'Ritual year and name already used! ');
        }

        /* check that announcement exists */

        $ritual->save();

        return redirect('/rituals/1/list');

    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $ritual = Ritual::where('id','=', $id)
            ->first();
        return view('rituals.show', compact('ritual'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $ritual = Ritual::findOrFail($id);
        /** @var \App\Models\Ritual $ritual */

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Venue> $locations */
        $locations = Venue::all();

        $elements = Element::query()->where('name', '=', 'cultures')->first();
        /** @var \App\Models\Element|null $elements */
        $cultures = ($elements !== null) ? explode(',', (string)$elements->item) : [];

        $elements = Element::query()->where('name', '=', 'names')->first();
        /** @var \App\Models\Element|null $elements */
        $ritualNames = ($elements !== null) ? explode(',', (string)$elements->item) : [];

        return view('rituals.edit', compact('ritual', 'ritualNames',  'locations', 'cultures'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $ritual = Ritual::findOrFail($id);
        /** @var \App\Models\Ritual $ritual */

        // FIX: Use $request->input() and sanitize
        $item = $request->input('year');
        if (!is_string($item)) $item = '';
        $ritual->year = $item;

        $item = $request->input('name');
        if (!is_string($item)) $item = '';
        $ritual->name = $item;

        $item = $request->input('culture');
        if (!is_string($item)) $item = '';
        $ritual->culture = $item;

        $ritual->save();

        return redirect('/rituals/'.$ritual->id);
    }

    /**
     * Before destroy, ask sure.
     */
    public function sure(int $id): View
    {
        return view('/rituals.sure', ['id' => $id]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $ritual = Ritual::findOrFail($id);
        /** @var \App\Models\Ritual $ritual */

        $ritual->delete();

        return redirect('/rituals/1/list')->with('success', 'Ritual was deleted');
    }



    /**
     * Show the form for editing ritual names
     */
    public function editNames(): View
    {
        $element = Element::query()->where('name', '=', 'names')->first();
        /** @var \App\Models\Element|null $element */

        return view('rituals.parameters', compact('element'));
    }


    /**
     * Show the form for editing ritual cultures
     */
    public function editCultures(): View
    {
        $element = Element::query()->where('name', '=', 'cultures')->first();
        /** @var \App\Models\Element|null $element */

        return view('rituals.parameters', compact('element'));
    }

    /**
     * Update the parameter in storage.
     */
    public function updateParameter(Request $request, int $id): RedirectResponse
    {
        $element = Element::query()->find($id);
        /** @var \App\Models\Element|null $element */

        if ($element === null) {
            return back()->with('error', 'Element not found.');
        }

        // FIX: Use $request->input() and sanitize
        $item = $request->input('item');

        // Ensure $item is treated as a string or null before assignment
        $element->item = ($item === null) ? '' : (string)$item;
        $element->save();

        return redirect('/');
    }
}
