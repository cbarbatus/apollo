<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * We'll only allow users with 'admin' or 'senior_druid' roles to create/update books.
     */
    public function authorize(): bool
    {
        $user = Auth::user();

        // Check if user is authenticated and has the required role
        return $user && $user->hasRole(['admin', 'senior_druid']);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            // Title is required and must be a string, max 255 chars
            'title' => ['required', 'string', 'max:255'],
            // Author is optional but if present, must be a string
            'author' => ['required', 'string', 'max:255'],
            // Link should be a valid URL, max 255 chars
            'link' => ['required', 'url', 'max:255'],
            // Pix (image path) is a string
            'pix' => ['required', 'string', 'max:255'],
            // Remarks is a nullable string (for longer text)
            'remarks' => ['required', 'string'],
            // Sequence is required and must be an integer
            'sequence' => ['required', 'integer'],
        ];
    }
}
