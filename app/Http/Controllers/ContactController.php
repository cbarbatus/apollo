<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use App\Models\User;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function index(): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

            if ($user && $user->hasRole(['admin', 'SeniorDruid'])) {

                $contacts = Contact::where('status', '=', 'received')
                    ->orderBy('created_at')
                    ->get();
                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contact> $contacts */

                return view('contacts.index', compact('contacts'));
            }
        }

        return redirect('/');
    }

    /**
     * Show the form for creating a new resource.
     *
     * FIX: Added return statement and corrected native type hint.
     * @return \Illuminate\Contracts\View\View
     */
    public function create(): View
    {
        return view('contacts.create');
    }

    /**
     * Store a newly created resource in storage (backend).
     *
     * FIX: Added return statement and corrected native type hint.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // Placeholder implementation for backend form submission
        return redirect('/contacts')->with('success', 'Contact stored (placeholder).');
    }

    /**
     * Display the specified resource.
     *
     * FIX: Added return statement and corrected native type hint.
     * @return \Illuminate\Contracts\View\View
     */
    public function show(Contact $contact): View
    {
        return view('contacts.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * FIX: Added return statement and corrected native type hint.
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Contact $contact): View
    {
        return view('contacts.edit', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Contact  $contact
     * FIX: Added return statement and corrected native type hint.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Contact $contact): RedirectResponse
    {
        // Placeholder implementation
        $contact->fill($request->only(['name', 'email', 'message']))->save();
        return redirect('/contacts')->with('success', 'Contact updated (placeholder).');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Contact  $contact
     * FIX: Added return statement and corrected native type hint.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();
        return redirect('/contacts')->with('success', 'Contact deleted.');
    }

    public function list(string $type): View|RedirectResponse
    {
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User&\Illuminate\Contracts\Auth\Access\Authorizable $user */

            if ($user && $user->hasRole(['admin', 'SeniorDruid'])) {
                if ($type == 'all') {
                    // FIX: Explicitly use query() to ensure PHPStan recognizes the Query Builder and resolves the get() issue.
                    $contacts = Contact::query()->orderBy('created_at')
                        ->get();
                } else {
                    $contacts = Contact::where('status', '=', $type)
                        ->orderBy('created_at')
                        ->get();
                }
                /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Contact> $contacts */
                return view('contacts.index', compact('contacts'));
            }
        }

        return redirect('/');
    }


    public function contactus(): View
    {
        return view('contacts.contactus');
    }

    public function submit(Request $request): RedirectResponse
    {
        $contact = new Contact;
        /** @var \App\Models\Contact $contact */

        // Switched from request('key') to $request->input('key', '') for type safety
        $contact->name = (string) $request->input('name', '');
        $contact->email = (string) $request->input('email', '');
        $contact->message = (string) $request->input('message', '');

        $contact->status = 'received';
        $contact->when_replied = '1970-01-09 00:00';
        $contact->save();

        return redirect('/contacts/thanks');
    }

    public function thanks(): View
    {
        return view('contacts.thanks');
    }

    public function spam(int $id): RedirectResponse // FIX: Added int type hint to resolve missingType.parameter
    {
        $contact = Contact::findOrFail($id);
        /** @var \App\Models\Contact $contact */

        $contact->status = 'spam';
        $contact->save();

        return redirect('/contacts');
    }

    public function reply(int $id): RedirectResponse // FIX: Added int type hint to resolve missingType.parameter
    {
        $contact = Contact::findOrFail($id);
        /** @var \App\Models\Contact $contact */

        $contact->status = 'replied';
        $contact->when_replied = date('Y-m-d H:i:s');
        $contact->save();

        return redirect('/contacts');
    }

}
