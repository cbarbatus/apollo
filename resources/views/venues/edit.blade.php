@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Edit a Venue</h1>
        <br><br>
        <form method="post" action="/venues/{{ $venue->id }}" id="edit">
            @csrf
            @method('put')
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" value="{{ $venue->name }}">
            <br>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" size="40" value="{{ $venue->title }}">
            <br>
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" size="60" value="{{ $venue->address }}">
            <br>
            <label for="map_link">Map URL:</label>
            <input type="url" name="map_link" id="map_link" size="60" value="{{ $venue->map_link }}">
            <br>
            <label for="directions">Driving directions:</label>
            <textarea id="directions" name="directions" rows="4" cols="60" value="{{ $venue->directions }}">
            {{ html_entity_decode($venue->directions) }}
            </textarea>
            <br><br>
        </form>
        <button type="submit" form='edit' class="btn btn-go">Submit</button>

    </div>
@endsection
