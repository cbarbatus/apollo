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

    public function index(Request $request)
    {
        // 1. Basic Data Setup
        $activeYears = Ritual::whereNotNull('year')->where('year', '<>', '')
            ->distinct()->orderByDesc('year')->pluck('year');
        $selectedYear = $request->query('year', $activeYears->first());
        $selectedName = $request->query('name');
        $activeNames = Ritual::where('year', $selectedYear)->orderBy('id', 'asc')->pluck('name');

        // 2. THE TRANSPORTER (Admin/Selection Logic)
        $ritual = null; // Initialize to prevent Undefined Variable error

        if ($selectedName) {
            $ritual = Ritual::where('year', $selectedYear)
                ->where('name', $selectedName)
                ->first();

            // If a ritual is found and user is logged in, jump straight to Details
            if ($ritual && \Auth::check()) {
                return redirect()->route('rituals.show', $ritual->id);
            }
        }

        // 3. Render the Index (Falls here if no name is picked OR if guest)
        return view('rituals.index', compact(
            'activeYears',
            'selectedYear',
            'activeNames',
            'selectedName',
            'ritual'
        ));
    }

    /**
     * Display a listing of the resource if not admin.
     */
    public function list(bool $admin): View
    {
        // 1. Get distinct years directly from the Ritual model
        $activeYears = Ritual::distinct()
            ->orderByDesc('year')
            ->pluck('year');

        // 2. Fetch the Master List using the Element model
        $nameElement = Element::where('section_id', 99)
            ->where('name', 'RitualNames')
            ->first();

        // 3. Explode the comma-delimited string
        $names = $nameElement
            ? array_map('trim', explode(',', $nameElement->item))
            : ['Samhain', 'Yule', 'Imbolc'];

        return view('rituals.index', compact('activeYears', 'names', 'admin'));
    }

    public function search(Request $request)
    {
        $year = $request->input('year');
        $name = $request->input('name');
        $admin = $request->input('admin', 0);

        // 1. Start the query builder on the Ritual model
        $query = Ritual::query();

        // 2. Apply filters only if they are present
        if ($year) {
            $query->where('year', $year);
        }

        if ($name) {
            $query->where('name', $name);
        }

        // 3. Get the results
        $rituals = $query->orderByDesc('year')->get();

        // 4. Handle the "One" logic naturally
        // If they picked a specific year and name, and we found exactly one,
        // we can redirect straight to that ritual view.
        if ($rituals->count() === 1 && $year && $name) {
            return redirect()->route('rituals.show', [$rituals->first()->id, 'admin' => $admin]);
        }

        // 5. Otherwise, show the filtered list
        return view('rituals.index_results', compact('rituals', 'admin', 'year', 'name'));
    }


    /**
     * Display a listing of one year if not admin.
     */

    private function getRitualDisplayData(Ritual $ritual): array
    {
        $slideshow = Slideshow::where('year', $ritual->year)
            ->where('name', $ritual->name)
            ->first();

        $lit_file = $_SERVER['DOCUMENT_ROOT'] . "/liturgy/" . $ritual->year . "_" . $ritual->name . ".htm";
        if (!file_exists($lit_file)) { // Use file_exists() not !$lit_file
            $lit_file = '';
        }

        $announcement = Announcement::where('year', $ritual->year)
            ->where('name', $ritual->name)
            ->first();

        $venue_title = '';
        if ($announcement) {
            $venue = Venue::query()->where('name', (string)$announcement->venue_name)->first();
            if ($venue) {
                $venue_title = (string)$venue->title;
            }
        }

        // Return all ancillary data as an array
        return compact('slideshow', 'announcement', 'venue_title', 'lit_file');
    }



    /**
     * Display one ritual.
     */
    public function display (int $id): View
    {
        $ritual = Ritual::query()->findOrFail($id);
        $data = $this->getRitualDisplayData($ritual);
        $viewData = array_merge($data, compact('ritual'));

        return view('rituals.display', $viewData);

    }

    public function text(int $id) :  View|RedirectResponse
    {
        $rite = Ritual::query()->findOrFail($id);
        /** @var \App\Models\Ritual $rite */

        $theFile = $_SERVER['DOCUMENT_ROOT'] . "/liturgy/" . $rite->year . "_" . $rite->name . ".htm";
        $text = @file_get_contents($theFile);

        if ($text === false) {
            // Try the secondary name:
            $theFile = $theFile . '.htm';
            $text = @file_get_contents($theFile);
        }

        if ($text === false) {
            return redirect('/rituals/0/list')->with('message', 'Ritual text missing');
        }
// $text now contains the file content, proceed to encoding and parsing

        if ($text === false) {
            return redirect('/rituals/0/list')->with('message', 'Ritual text missing');
        }
// $text now contains the file content, proceed to encoding and parsing

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

// Use mb_convert_encoding() for reliable conversion.
// We use the @ symbol for error suppression during file reading, so this should
// handle the conversion to the required UTF-8 standard.

// Check if content is already UTF-8 based on file header
        if (str_starts_with($type, 'utf')) {
            $utftext = $text;
        } else {
            // Convert from the assumed legacy encoding (ISO-8859-1) to UTF-8
            $utftext = mb_convert_encoding($text, 'UTF-8', 'ISO-8859-1');

            // The fragile logic for updating the charset declaration inside the HTML is maintained
            // for compatibility, applied to the newly encoded text.
            if ($charset !== false && isset($size)) {
                $utftext = substr_replace($utftext, 'utf-8          ', $charset + 8, $size);
            }
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
        if ($elements)
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
        if ($elements) {
            $cultures = explode(',', (string)$elements->item);
        }

        return view('rituals.create', compact('venue_names', 'ritualNames', 'cultures'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. THE LOCK: Validates uniqueness and stops execution if it fails
        $request->validate([
            'year' => 'required|integer',
            'name' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('rituals')->where(function ($query) use ($request) {
                    return $query->where('year', $request->year);
                }),
            ],
        ], [
            'name.unique' => 'Ritual year and name already used!'
        ]);

        // 2. THE CREATION: Only happens if validation passes
        $ritual = new Ritual($request->all());
        $ritual->save();

        // 3. THE APOLLO REDIRECT: No more stranded 404s
        return redirect()->route('rituals.index', [
            'year' => $ritual->year,
            'name' => $ritual->name
        ])->with('success', "Liturgy for {$ritual->name} {$ritual->year} created successfully!");
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
    public function update(Request $request, int $id)
    {
        $ritual = Ritual::findOrFail($id);

        $request->validate([
            'year' => 'required|integer',
            'name' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('rituals')
                    ->where(fn ($query) => $query->where('year', $request->year))
                    ->ignore($ritual->id), // The "Jealous Rule" fix
            ],
        ], [
            'name.unique' => 'This ritual name and year combination is already in use.'
        ]);

        // Mass assignment using your specific $fillable fields
        $ritual->update($request->all());

        return redirect()->route('rituals.index', [
            'year' => $ritual->year,
            'name' => $ritual->name
        ])->with('success', 'Ritual updated successfully.');
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

        return redirect('/rituals/1/list')->with('success', 'Ritual '.$ritual->id.' was deleted');
    }



    /**
     * Show the form for editing ritual names
     */
    public function editNames(): View
    {
        $element = Element::query()->where('name', '=', 'RitualNames')->first();
        /** @var \App\Models\Element|null $element */

        return view('rituals.parameters', compact('element'));
    }


    /**
     * Show the form for editing ritual cultures
     */
    public function editCultures(): View
    {
        $element = Element::query()->where('name', '=', 'RitualCultures')->first();
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
