<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\Section;
use Illuminate\Http\RedirectResponse; // Imported for completeness
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View; // Switched to Contracts\View\View for consistency
use App\Models\User; // Import User for type casting

class WelcomeController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        // Cast $user to the expected intersection/union type
        /** @var (\App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable)|null $user */

        $contacts = null;
        /** @var \App\Models\Contact|null $contacts */

        if (! is_null($user)) {
            if ($user->can('change all')) {
                $contacts = Contact::where('status', '=', 'received')->first();
            }
        }

        $sections = Section::query()->where(function ($query) {
            // Changed string literal '99' to integer 99 for strict type comparison with int $id
            $query->where('id', '<>', 99);
        })
            ->orderBy('sequence')
            ->get();

        // Cast $sections to Collection of Section models
        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Section> $sections */

        return view('welcome.index', compact('sections', 'contacts'));

    }
}
