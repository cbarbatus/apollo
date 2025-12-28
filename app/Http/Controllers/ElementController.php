<?php

namespace App\Http\Controllers;

use App\Models\Element;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
// Use Illuminate\Contracts\View\View for consistent static analysis of view returns
use Illuminate\Contracts\View\View;
use App\Models\Section;
use App\Http\Requests\ElementRequest;
use Illuminate\Support\Facades\DB;

class ElementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * PHPDoc Fix: Using 'mixed' or 'void' since the function calls exit().
     * @return mixed
     */
// The signature is now correct: GET /sections/{section}/elements
    public function index(Section $section): View
    {
        // Retrieve elements that belong ONLY to this specific section
        $elements = $section->elements()->orderBy('sequence')->get();

        // Pass both the section and its elements to the view
        return view('elements.index', compact('section', 'elements'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * PHPDoc Fix: Updated to reflect the actual possible return types (RedirectResponse or View).
     * Added method return type hint for greater safety.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Contracts\View\View
     */

    public function create(Request $request)
    {
        $section_id = $request->query('section_id');

        // Find the current max sequence for THIS section to suggest the next one
        $last_seq = Element::where('section_id', $section_id)->max('sequence');
        $next_seq = $last_seq ? $last_seq + 100 : 100;

        return view('elements.create', compact('section_id', 'next_seq'));
    }


    public function store(Request $request)
    {
        // 1. Validation - Removed 'item' since it's not in the DB
        $request->validate([
            'section_id' => 'required|integer|exists:sections,id',
            'name'       => 'required|string',
            'title'      => 'required|string',
            'sequence'   => 'required|integer',
            'item'       => 'required|string',
        ]);

        // 2. Create via Model
        // This automatically handles created_at and updated_at.
        // It also ignores any extra data (like 'item') not in your $fillable list.

        Element::create($request->all());

        // 3. Redirect home
        return redirect('sections/' . $request->section_id . '/edit')
            ->with('status', 'Element created successfully!');
    }

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
    public function edit($id)
    {
        // 1. Find the element or fail with a clean 404
        $element = Element::findOrFail($id);
        // 2. Return the view
        return view('elements.edit', compact('element'));
    }

    /**
     * Update the specified resource in storage.
     *
     * Route Model Binding Fix: Changed parameter from int $id to Element $element.
     * Type Safety Fix: Used input() and explicit casting.
     */
    public function updatePost(Request $request, $id)
    {
        $element = Element::findOrFail($id);

        $validated = $request->validate([
            'name'       => 'required|string',
            'title'      => 'required|string',
            'sequence'   => 'required|integer',
            'item'       => 'nullable|string',
            'section_id' => 'required|integer',
        ]);

        $element->update($validated);

        return redirect('sections/' . $element->section_id . '/edit')
            ->with('status', "Element '{$element->name}' updated successfully!");
    }

    public function editSchedule(): View
    {
        // Simplified Authorization - assuming your middleware handles the heavy lifting
        $element = Element::where('name', 'Schedule')->firstOrFail();

        return view('elements.schedule', compact('element'));
    }

    public function updateSchedule(Request $request, int $id): RedirectResponse
    {
        $element = Element::findOrFail($id);

        // Clean input handling
        $element->item = $request->input('item', '');
        $element->save();

        return redirect()->route('schedule.edit')
            ->with('success', "Ritual Schedule updated successfully.");
    }

    /**
     * Remove the specified resource from storage.
     *
     * Route Model Binding Fix: Changed parameter from int $id to Element $element.
     */
    public function destroy($id)
    {
        // 1. Find the element so we can get its section_id
        $element = \DB::table('elements')->where('id', $id)->first();

        if (!$element) {
            return redirect()->back()->with('error', 'Element not found.');
        }

        $section_id = $element->section_id;

        // 2. Perform the deletion
        \DB::table('elements')->where('id', $id)->delete();

        // 3. Redirect back to the parent Section Edit page
        return redirect('sections/' . $section_id . '/edit')
            ->with('status', 'Element deleted successfully.');
    }
}
