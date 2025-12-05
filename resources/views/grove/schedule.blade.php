@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Edit Ritual Schedule</h1>
        <br>
        {{-- FIX: Use the standard Laravel route helper and POST method for update --}}
        <form method="post" action="{{ route('schedule.update', $element->id) }}" id="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            @method('put')

            <label for="item">Text:</label>

            {{-- CRITICAL: Replace textarea with Trix input --}}
            <input
                id="schedule-item"
                type="hidden"
                name="item"
                {{-- This loads the HTML correctly, which you've already implemented --}}
                value="{{ html_entity_decode($element->item ?? '') }}"
            >            <trix-editor input="schedule-item" class="form-control" style="min-height: 400px;"></trix-editor>

            <br><br>
        </form>
        <button type="submit" form='edit' class="btn btn-warning">Submit</button>
        <br>
    </div>
    <br>

@endsection
