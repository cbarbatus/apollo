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

    /**
     * Display a listing of the resource.
     */
    public function list(bool $admin): View|RedirectResponse
    {
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Slideshow> $slideshows */
        $slideshows = Slideshow::all();

        $years = DB::table('slideshows')
            ->select('year')
            ->distinct()
            ->groupBy('year')
            ->orderBy('year', 'desc')
            ->get();
        $activeYears = [];
        foreach ($years as $year) {
            /** @var object{year: string} $year */
            $activeYears[] = $year->year;
        }

        $names = DB::table('slideshows')
            ->select('name')
            ->distinct()
            ->groupBy('name')
            ->get();
        $activeNames = [];
        foreach ($names as $name) {
            /** @var object{name: string} $name */
            $activeNames[] = $name->name;
        }

        return view('slideshows.index', compact('slideshows', 'activeYears', 'activeNames', 'admin'));
    }



    /**
     * Find one slideshow from year and name
     */
    // Inject Request for best practice
    public function one(Request $request, bool $admin): RedirectResponse
    {
        $target = "";
        // Use $request->input() instead of global request() helper
        $year = $request->input('year');
        $name = $request->input('name');

        $slideshow = null;
        if (is_string($year) && is_string($name)) {
            $slideshow = Slideshow::query()
                ->where('year', $year)
                ->where('name', $name)
                ->first();

            if (is_null($slideshow)) {
                return redirect('/slideshows/0/list')->with('message', 'Slideshow ' . $year . ' ' . $name . ' not available');
            }
        }
        /** @var \App\Models\Slideshow|null $slideshow */
        if (!is_null($slideshow)) {
            $id = $slideshow->id;
            if ($admin) {
                $target = 'slideshows/' . $id . '/edit';
            } else {
                $target = 'slideshows/' . $id . '/view';
            }
        }
        return redirect($target);
    }



    /**
     * Display a listing of one year if not admin.
     */
    public function year(string $year, bool $admin): View
    {
        $slideshows = DB::table('slideshows')
            ->select()
            ->where('year', '=', $year)
            ->orderBy('sequence')
            ->get();

        return view('slideshows.year', compact('slideshows', 'year', 'admin'));
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
    public function store(Request $request): RedirectResponse
    {
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

        return redirect('/slideshows/1/list');
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

        return redirect('/slideshows/1/list');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        // Renamed $venue to $slideshow for clarity and type safety
        $slideshow = Slideshow::findOrFail($id);
        /** @var \App\Models\Slideshow $slideshow */

        $slideshow->delete();

        return redirect('/slideshows')->with('success', 'Slideshow was deleted');
    }
}
