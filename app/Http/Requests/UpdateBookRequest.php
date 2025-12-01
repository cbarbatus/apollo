<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Check if the authenticated user has the 'admin' role
        return auth()->user()->hasRole('admin');
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
            'author' => ['nullable', 'string', 'max:255'],
            // Link should be a valid URL, max 255 chars
            'link' => ['nullable', 'url', 'max:255'],
            // Pix (image path) is a string
            'pix' => ['nullable', 'string', 'max:255'],
            // Remarks is a nullable string (for longer text)
            'remarks' => ['nullable', 'string'],
            // Sequence is required and must be an integer
            'sequence' => ['required', 'integer'],
        ];
    }
}
