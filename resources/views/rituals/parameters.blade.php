@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4">Edit Ritual Parameter</h1>

        <div style="max-width: 500px;">
            <form method="post" action="/rituals/{{ $element->id }}/updateParameter" id="edit">
                @csrf
                @method('put')

                {{-- Read-only name field, kept small but styled --}}
                <div class="mb-3" style="max-width: 200px;">
                    <label class="form-label"><strong>Name:</strong></label>
                    <input type="text" name="name" id="name" class="form-control fw-bold bg-light" value="{{ $element->name }}" readonly>
                </div>

                {{-- Text input for the comma-separated list --}}
                <div class="mb-4">
                    <label class="form-label"><strong>Values (Comma Separated):</strong></label>
                    <textarea id="item" name="item" class="form-control" rows="3"
                    >{{ old('item', html_entity_decode($element->item)) }}</textarea>
                    <div class="form-text mt-2 text-muted">
                        Enter the list of available {{ $element->name }} for rituals.
                    </div>
                </div>

                {{-- Apollo Standard Button Group --}}
                <div class="d-flex justify-content-start gap-2 pt-2">
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
@endsection
