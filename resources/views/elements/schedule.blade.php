@extends('layouts.app')

{{-- Ensure Trix assets are included --}}
@push('styles')
    <link rel="stylesheet" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
@endpush
@push('scripts')
    <script src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
@endpush

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Edit Ritual Schedule</h1>

        <div style="max-width: 500px;">
            <form action="{{ route('schedule.update', $element->id) }}" method="POST">
                @csrf
                @method('PUT')

                {{-- Hidden fields for Element validation --}}
                <input type="hidden" name="name" value="{{ $element->name }}">
                <input type="hidden" name="title" value="{{ $element->title }}">
                <input type="hidden" name="sequence" value="{{ $element->sequence }}">
                <input type="hidden" name="section_id" value="{{ $element->section_id }}">

                <div class="mb-4">
                    <label class="form-label"><strong>Schedule Content:</strong></label>

                    {{-- Trix Integration: The hidden input is what Laravel actually 'sees' --}}
                    <input id="item" type="hidden" name="item" value="{{ old('item', $element->item) }}">
                    <trix-editor input="item" class="trix-content" style="min-height: 250px; background-color: white;"></trix-editor>

                    <div class="form-text mt-2 text-muted">
                        Approx. 12 lines, 50 chars each.
                    </div>
                </div>

                <div class="d-flex justify-content-start gap-2 pt-3">
                    <x-apollo-button type="submit">
                        Submit
                    </x-apollo-button>

                    <x-cancel-button href="{{ route('home') }}">
                        Cancel
                    </x-cancel-button>
                </div>
            </form>
        </div>
    </div>

    <style>
        /* Ensure Trix fits the 'Big Edit' aesthetic */
        trix-toolbar .trix-button-group--file-tools { display: none !important; } /* Hide file uploads if not needed */
        trix-editor { border: 1px solid #ced4da !important; border-radius: 0.375rem; }
    </style>
@endsection
