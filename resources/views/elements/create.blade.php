@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New Element to Section {{ $section_id }}</h1>

        <form method="POST" action="{{ url('elements/store') }}">
            @csrf
            <input type="hidden" name="section_id" value="{{ $section_id }}">

            <div class="row mb-3">
                <div class="col-md-5">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. MISSION:">
                </div>
                <div class="col-md-5">
                    <label for="name" class="form-label">Internal Name</label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. mission">
                </div>
                <div class="col-md-2">
                    <label for="sequence" class="form-label">Sequence</label>
                    <input type="number" name="sequence" value="{{ $next_seq }}" class="form-control">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="trix-item-input">Element Content:</label>
                    <input type="hidden" name="item" id="trix-item-input">
                    <trix-editor input="trix-item-input" class="form-control" style="min-height: 300px; background-color: white;"></trix-editor>
                </div>
            </div>

            <div class="d-flex gap-2 mt-3">
                <x-apollo-button type="submit" color="success" class="fw-bold px-4">
                    Create Element
                </x-apollo-button>

                {{-- Send them back to the specific section and anchor --}}
                <x-apollo-button
                    href="/sections/{{ $section_id }}/edit#section-elements"
                    color="secondary"
                    class="px-4"
                    style="color: #ffffff !important;"
                >
                    Cancel
                </x-apollo-button>
            </div>
        </form>
    </div>
@endsection
