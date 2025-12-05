<?php

namespace App\Http\Controllers;

use App\Models\Element;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// Use Illuminate\Contracts\View\View for consistent static analysis of view returns
use Illuminate\Contracts\View\View;

class ElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * PHPDoc Fix: Using 'mixed' or 'void' since the function calls exit().
     * @return mixed
     */
    public function index()
    {
        var_dump('stopped at element.index');
        exit();
    }

    /**
     * Show the form for creating a new resource.
     *
     * PHPDoc Fix: Updated to reflect the actual possible return types (RedirectResponse or View).
     * Added method return type hint for greater safety.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */
    public function create(int $section_id): \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
    {
        // FIX for Line 35: Explicitly call guard() to satisfy strict static analysis.
        $user = auth()->guard()->user();
        if ($user === null) {
            return redirect('/')->with('warning', 'Login is needed.');
        }
        return view('elements.create', compact('section_id'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * Type Safety Fix: Use request()->input() and explicitly cast numeric fields to (int).
     */
    public function store(Request $request): RedirectResponse
    {

        // 1. CHECK IF THIS IS AN UPDATE REQUEST
        if ($request->has('id')) {
            // Find the existing model using the hidden ID field
            $element = Element::findOrFail($request->input('id'));

            // MANUALLY call your existing, correct update logic
            // This executes your update function logic:
            $element->title = (string) $request->input('title', $element->title);
            $element->name = (string) $request->input('name', $element->name);
            $element->sequence = (int) $request->input('sequence', $element->sequence);
            $element->item = (string) $request->input('item', $element->item);

            $element->save(); // This GUARANTEES an UPDATE query

            $section_id = $element->section_id;

            return redirect('/sections/'.$section_id.'/edit');
        }

        $element = new Element;

        // Fixed type-mixing by using input() for safety and explicit casting for numeric columns
        $element->section_id = (int) $request->input('section_id', 0);
        $element->name = (string) $request->input('name', '');
        $element->title = (string) $request->input('title', '');
        $element->sequence = (int) $request->input('sequence', 0); // Assuming sequence is an integer
        $element->item = (string) $request->input('item', '');

        $element->save();

        return redirect('/sections/'.$element->section_id.'/edit');

    }

    /**
     * Display the specified resource.
     *
     * PHPDoc Fix: Using 'mixed' or 'void' since the function calls exit().
     * @return mixed
     */
    public function show(int $id)
    {
        var_dump('stopped at element.show', $id);
        exit();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * Route Model Binding Fix: Changed parameter from int $id to Element $element.
     * This resolves all undefined property errors in this method.
     */
    public function edit(Element $element): View
    {
        // Element::findOrFail($id) is now handled automatically by Laravel.
        return view('elements.edit', compact('element'));

    }

    /**
     * Update the specified resource in storage.
     *
     * Route Model Binding Fix: Changed parameter from int $id to Element $element.
     * Type Safety Fix: Used input() and explicit casting.
     */
    public function update(Request $request, Element $element): RedirectResponse
    {
        // Element::find($id) is no longer needed.

        // Fixed type-mixing by using input() and explicit casting
        $element->title = (string) $request->input('title', $element->title);
        $element->name = (string) $request->input('name', $element->name);
        $element->sequence = (int) $request->input('sequence', $element->sequence); // Assuming sequence is an integer
        $element->item = (string) $request->input('item', $element->item);

        $element->save();
        $section_id = $element->section_id;

        return redirect()->route('sections.edit', $section_id)
            ->with('success', 'Element updated successfully!');
    }

    /**
     * Before destroy, ask sure.
     *
     * Route Model Binding Fix: Changed parameter from int $id to Element $element.
     */
    public function sure(int $id): View
    {
        // Fetch the element manually to confirm it exists
        $element = Element::findOrFail($id);

        // Pass BOTH the simple ID and the Element object to the view for redundancy
        return view('elements.sure', compact('element', 'id'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * Route Model Binding Fix: Changed parameter from int $id to Element $element.
     */
    public function destroy(int $id): RedirectResponse
    {
        // 2. Manually find the element (guarantees the object is loaded)
        $element = Element::findOrFail($id);

        // 3. Store necessary variables BEFORE deletion.
        $deleted_id = $element->id;
        $section_id = $element->section_id; // This will definitely be 10 now.

        // 4. Perform the deletion.
        $element->delete();

        // 5. Use the stored section_id for the reliable named route redirect.
        return redirect()->route('sections.edit', $section_id)
            ->with('success', 'Element '.$deleted_id.' was deleted');
    }
}
