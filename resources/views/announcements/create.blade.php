@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Create an Announcement</h1>

        <form method="post" action="/announcements" id="create">
            @csrf

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="year" class="form-label">Year:</label>
                    <input type="text" name="year" id="year"  class="form-control" size="4">
                </div>

                <div class="col-md-2 mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <select name="name" id="name" class="form-control">
                        @foreach($rituals as $ritual_name)
                            <option value="{{ $ritual_name }}"> {{ $ritual_name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="row">
                <div class="col-md-9 mb-3">
                    <label for="summary" class="form-label">Summary:</label>
                    <input
                        id="summary"
                        type="hidden"
                        name="summary"
                        value=""
                    >
                    {{-- The 'col-md-9' limits the width of the Trix editor --}}
                    <trix-editor input="summary" class="form-control" style="min-height: 200px;"></trix-editor>
                </div>
            </div>

            <div class="row mb-3">

                <div class="col-md-4">
                    <label for="when" class="form-label">When:</label>
                    <input type="datetime-local" name="when" id="when" class="form-control" value="{{ now()->format('Y-m-d\TH:i') }}">
                </div>

                <div class="col-md-4">
                    <label for="venue_name" class="form-label">Venue:</label>
                    <select name="venue_name" id="venue_name" class="form-control">
                        @foreach($locations as $location)
                            <option value="{{ $location->name }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    <small class="form-text text-muted">If the venue is new, create it before a new announcement.</small>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-9 mb-3">
                    <label for="notes" class="form-label">Notes:</label>
                    <input
                        id="notes"
                        type="hidden"
                        name="notes"
                        value=""
                    >
                    {{-- The 'col-md-8' limits the width of the Trix editor --}}
                    <trix-editor input="notes" class="form-control" style="min-height: 200px;"></trix-editor>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <x-apollo-button type="submit">Submit</x-apollo-button>
                    <x-cancel-button></x-cancel-button>
            </div>

        </form>

    </div>
@endsection
