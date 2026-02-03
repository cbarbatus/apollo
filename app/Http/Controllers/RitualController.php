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
use Illuminate\Validation\Rule;

class RitualController extends Controller
{
    public function __construct()
    {
        // Add 'display' and 'liturgy' to the whitelist
        $this->middleware('auth')->except(['index', 'display', 'liturgy']);

        // Ensure leaders can still manage, but members/guests can view these three
        $this->middleware('role:admin|senior_druid')->except(['index', 'display', 'liturgy']);
    }


    public function index(Request $request)
    {
        // 1. STICKY YEAR: Get year from request, or fallback to the most recent year in the DB
        // This prevents the 'highest value' reset after a delete.
        $selectedYear = $request->get('year', Ritual::max('year') ?? date('Y'));
        $selectedName = $request->get('name');

        // 2. MASTER YEAR LIST: For the primary year selector
        $activeYears = Ritual::select('year')->distinct()->orderBy('year', 'desc')->pluck('year');

        // 3. PRECISION NAME LIST: Only names that exist for the currently selected sticky year
        // This fixes the 'blinking' by ensuring the dropdown matches the reality of that year.
// 1. Get the "God List" (The Master Sequence)
        $masterList = Element::where('name', 'names')->value('item');
        $ritualSequence = array_map('trim', explode(',', $masterList));

// 2. The Precision Filter: Get ONLY names that exist for this year
        $existingNames = Ritual::where('year', $selectedYear)
            ->distinct()
            ->pluck('name');

// 3. The Sort: Reorder the existing names based on the Master List
        $activeNames = $existingNames->sortBy(function ($name) use ($ritualSequence) {
            $pos = array_search($name, $ritualSequence);
            return ($pos === false) ? 999 : $pos;
        })->values();
        // 4. FIND THE SPECIFIC RITUAL (If selected)
        $ritual = null;
        if ($selectedYear && $selectedName) {
            $ritual = Ritual::where('year', $selectedYear)
                ->where('name', $selectedName)
                ->first();
        }

        // 5. THE FLOW LOGIC
// 5. THE FLOW LOGIC [cite: 2026-01-06]
        if ($ritual) {
            // Check if the user has the 'Master Key' (Admin or senior_druid)
            $isStaff = auth()->user()?->hasAnyRole(['admin', 'senior_druid']);

            if (!$isStaff) {
                // GUESTS & MEMBERS: Transport immediately to the clean public view
                // Using the direct path you already had established
                return redirect()->to("/rituals/{$ritual->id}/display");
            }

            // AUTHORIZED STAFF: Stay here to show the Ritual Management card
        }

// 6. PASS TO APOLLO
        return view('rituals.index', compact(
            'activeYears',
            'activeNames',
            'selectedYear',
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
            ->where('name', 'names')
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
    public function display (Ritual $ritual): View
    {
        $data = $this->getRitualDisplayData($ritual);
        $viewData = array_merge($data, compact('ritual'));
        return view('rituals.display', $viewData);
    }

    /**
     * Modernized Liturgy Reader
     * Replaces legacy 'text' method and manual line-parsing loops.
     */
    public function liturgy(Ritual $ritual): View|RedirectResponse
    {
        // 1. Use the Phoenix 'liturgy_base' column for the filename
        $fileName = "{$ritual->liturgy_base}.htm";
        $filePath = public_path("liturgy/{$fileName}");

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', "The liturgy file '{$fileName}' could not be found in the archive.");
        }

        // 2. Efficiently read and handle encoding
        $rawContent = file_get_contents($filePath);
        $utf8Content = mb_convert_encoding($rawContent, 'UTF-8', 'ISO-8859-1');

        // 3. Extraction: Use Regex instead of manual line-by-line 'for' loops
        // This grabs everything between <body> and </body> regardless of whitespace/newlines
        if (preg_match('/<body[^>]*>(.*?)<\/body>/is', $utf8Content, $matches)) {
            $content = $matches[1];
        } else {
            $content = $utf8Content; // Fallback to full file if no body tag exists
        }

        return view('rituals.liturgy', compact('ritual', 'content'));
    }


    public function create()
    {
        $activeNames = $this->getSection99Names();
        $cultures = $this->getSection99Cultures(); // Dynamic fetch

        return view('rituals.create', compact('activeNames', 'cultures'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => 'required|integer',
            'culture' => 'required',
            // Validates uniqueness for the name/year combo
            'name' => [
                'required',
                Rule::unique('rituals')->where(fn ($query) =>
                $query->where('year', $request->year)
                ),
            ],
        ], [
            'name.unique' => 'This ritual name and year combination is already in use.'
        ]);

        $ritual = Ritual::create($request->all());

        return redirect()->route('rituals.show', $ritual->id)
            ->with('success', 'Ritual created successfully!');
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
    public function edit(Ritual $ritual)
    {
        $activeNames = $this->getSection99Names();
        $cultures = $this->getSection99Cultures(); // Dynamic fetch

        return view('rituals.edit', compact('ritual', 'activeNames', 'cultures'));
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ritual $ritual) // No more $id!
    {
        $request->validate([
            'year' => 'required|integer',
            'name' => [
                'required',
                'string',
                \Illuminate\Validation\Rule::unique('rituals')
                    ->where(fn ($query) => $query->where('year', $request->year))
                    ->ignore($ritual->id), // Still using the "Jealous Rule" fix
            ],
        ], [
            'name.unique' => 'This ritual name and year combination is already in use.'
        ]);

        // Mass assignment - Eloquent handles the dirty check
        $ritual->update($request->all());

// Redirect back to the specific ritual management page instead of the list
        return redirect()->route('rituals.show', $ritual->id)
            ->with('success', "{$ritual->name} updated successfully.");
    }


    public function uploadlit(int $id): View
    {
        $ritual = Ritual::findOrFail($id);

        // Clean Phoenix Naming: 2026_Imbolc.htm
        $litName = $ritual->year . '_' . str_replace(' ', '_', $ritual->name) . '.htm';

        return view('rituals.uploadlit', compact('ritual', 'litName'));
    }

    public function storelit(Request $request): RedirectResponse
    {
        $request->validate([
            // Strictly .htm and .docx only
            'file' => 'required|mimes:htm,docx|max:5120',
            'id' => 'required|exists:rituals,id'
        ]);
        $ritual = Ritual::findOrFail($request->id);
        $extension = $request->file('file')->getClientOriginalExtension();

        // Ensure we use the correct extension for the final filename
        $baseName = pathinfo($request->litName, PATHINFO_FILENAME);
        $fullFilename = $baseName . '.' . $extension;

        if (in_array($extension, ['doc', 'docx'])) {
            // Explicitly move to the legacy grove folder
            $request->file('file')->move(storage_path('app/grove'), $fullFilename);
            $message = "Private source file ({$extension}) uploaded successfully to the grove archive.";
        } else {
            // PUBLIC: Move to public/liturgy
            $request->file('file')->move(public_path('liturgy'), $fullFilename);

            // Update the database only for public files to track the "active" ritual
            $ritual->liturgy_base = $baseName;
            $ritual->save();
            $message = "Public liturgy (.htm) is now live.";
        }

        return redirect()->route('rituals.show', $ritual->id)
            ->with('success', $message);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ritual $ritual)
    {
        // Save the year so we can "stick" to it after the redirect
        $preservedYear = $ritual->year;

        $ritual->delete();

        // Redirect back to index while passing the year as a query parameter
        return redirect()->route('rituals.index', ['year' => $preservedYear])
            ->with('success', 'Ritual successfully removed.');
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

    private function getSection99Names()
    {
        // Fix: Change 'RitualNames' to 'names' to match your database
        $nameElement = \App\Models\Element::where('section_id', 99)
            ->where('name', 'names') // Match the record you showed in the image
            ->first();

        return $nameElement
            ? array_map('trim', explode(',', $nameElement->item))
            : ['SYSTEM ERROR: Ritual Names Not Found'];
    }

    private function getSection99Cultures()
    {
        $cultureElement = \App\Models\Element::where('section_id', 99)
            ->where('name', 'cultures')
            ->first();

        return $cultureElement
            ? array_map('trim', explode(',', $cultureElement->item))
            : ['SYSTEM ERROR: Ritual Cultures Not Found'];
    }
}
