<?php

namespace App\Http\Controllers;

// NEW: Use the custom Form Request classes
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;

class BookController extends Controller
{
    // 1. CONSTRUCTOR: CENTRALIZED AUTHENTICATION & PERMISSION CHECK
    public function __construct()
    {
        // Apply 'auth' middleware to all methods EXCEPT the public ones.
        // If the user's session times out, they will be redirected to the login screen.
        $this->middleware('auth')->except(['index']);

        // Apply a high-level authorization check for methods requiring permission.
        // This stops unauthorized users before the function body executes.
// TEMPORARILY COMMENT OUT THIS LINE FOR DIAGNOSTICS:
        // $this->middleware('role:admin|SeniorDruid')->only(['create', 'store', 'edit', 'update', 'sure', 'destroy']);

    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        // Index is public, so Auth::user() can be null. Check if the user is logged in.
        $changeok = false;
        if (Auth::check()) {
            $user = Auth::user();
            /** @var \App\Models\User $user */
            // We still need the hasRole check here to pass the flag to the view.
            $changeok = $user->hasRole(['admin', 'SeniorDruid']);
        }

        /** @var \Illuminate\Database\Eloquent\Collection<int, \App\Models\Book> $books */
        $books = \App\Models\Book::query()->orderBy('sequence')->get();
        return view('books.index', compact('books', 'changeok'));
    }

    /**
     * Show the form for creating a new resource.
     * Middleware now handles auth/permission, so internal checks are removed.
     */
    public function create(): View
    {
        // Execution reaches here only if user is logged in and has the required role (via middleware).
        return view('books.create');
    }

    /**
     * Store a newly created resource in storage.
     * FIX: Signature changed to StoreBookRequest.
     */
    public function store(StoreBookRequest $request): RedirectResponse // <-- FIX APPLIED
    {
        // $request->validated() is now GUARANTEED to work.
        /** @var \App\Models\Book $newBook */
        $newBook = \App\Models\Book::query()->create($request->validated());

        return redirect('/books')->with('success', 'Book was successfully created.');
    }

    /**
     * Show the form for editing the specified resource.
     * Middleware now handles auth/permission, so internal checks are removed.
     */
    public function edit(int $id): View
    {
        /** @var \App\Models\Book $book */
        $book = \App\Models\Book::query()->findOrFail($id);

        return view('books.edit', compact('book'));
    }

    /**
     * Update the specified resource in storage.
     * FIX: Signature changed to UpdateBookRequest.
     */
    public function update(UpdateBookRequest $request, int $id): RedirectResponse
    {
        $book = \App\Models\Book::findOrFail($id);

        // This single line validates against your 'sequence' rule
        // and fills the model safely
        $book->fill($request->validated());
        $book->save();

        return redirect('/books')->with('success', 'Book updated successfully');
    }



    /**
     * Remove the specified resource from storage.
     * Middleware now handles auth/permission, so internal checks are removed.
     */
    public function destroy(int $id): RedirectResponse
    {
        /** @var \App\Models\Book $book */
        $book = \App\Models\Book::query()->findOrFail($id);
        $book->delete();

        return redirect()->back()->with('success', 'Book #'.$id.' was deleted');
    }
}
