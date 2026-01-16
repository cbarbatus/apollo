<?php

namespace App\Http\Controllers;

use App\Models\Ritual;
use App\Models\Element;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class LiturgyController extends Controller
{
    /**
     * Narrow selection of liturgies.
     */
    public function find(): View|RedirectResponse
    {
        if (Auth::check()) {
            $rawelements = Element::all();

            // 1. Pull the "Allowed List" using the verified keys
            $cultures = [];
            $cultureElement = $rawelements->where('name', 'cultures')->first();
            if ($cultureElement && is_string($cultureElement->item)) {
                $cultures = array_map('trim', explode(',', $cultureElement->item));
            }

            $rituals = [];
            $nameElement = $rawelements->where('name', 'names')->first();
            if ($nameElement && is_string($nameElement->item)) {
                $rituals = array_map('trim', explode(',', $nameElement->item));
            }

            // 2. Return the view with the dropdown options
            return view('liturgy.find', compact('rituals', 'cultures'));
        }
        return redirect('/');
    }

    /**
     * List the selected rituals.
     */
    public function list(Request $request): View|RedirectResponse
    {
        if (Auth::check()) {

            $name = request('name');
            if ($name == '0') {
                $name = '%';
            }
            $culture = request('culture');
            if ($culture == '0') {
                $culture = '%';
            }
            $rituals = Ritual::query()->orderBy('year', 'DESC')
                ->where('culture', 'LIKE', $culture)
                ->where('name', 'LIKE', $name, 'and')
                ->get();
// 🛑 THE FIX: Check for empty results and redirect 🛑
            if ($rituals->isEmpty()) {
                // Flash a warning message (which is styled by your app.blade.php fix)
                // and redirect back to the search form (assumed to be named 'liturgy.find').
                return redirect()
                    ->route('liturgy.find')
                    ->with('warning', 'No rituals found matching your selection. Please try again.');
            }
            return view('liturgy.list', compact('rituals'));
        }

        return redirect('/');
    }

    /**
     * Download the .docx for the ritual.
     *
     * @return \Illuminate\Foundation\Application|RedirectResponse|\Illuminate\Http\Response|\Illuminate\Routing\Redirector|BinaryFileResponse
     */
    public function downloadSource(int $id)
    {
        if (Auth::check()) {
            $ritual = Ritual::query()->findOrFail($id);
            $location = (string) storage_path('app/grove/');
            $name = $ritual['name'];
            $year = $ritual['year'];

            $fullName = '';
            if (is_string($name) && is_string($year) )
                $fullName = $location . (string) $year . "_" . (string) $name . ".docx";

            if (file_exists($fullName)) {
                // Adding the second parameter ensures the user sees a clean name like "2024_Imbolc.docx"
                return response()->download($fullName, "{$year}_{$name}.docx");
            }
            else
                return redirect('/liturgy/find')->with('message', 'Ritual .docx missing');
        }

        return redirect('/');
    }
}
