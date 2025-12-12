@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Edit a Venue</h1>

        <form method="post" action="/venues/{{ $venue->id }}" id="edit">
            @csrf
            @method('put')

            <div class="col-md-4 mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ $venue->name }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-control" size="40" value="{{ $venue->title }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="address" class="form-label">Address:</label>
                <input type="text" name="address" id="address" class="form-control" size="60" value="{{ $venue->address }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="map_link" class="form-label">Map URL:</label>
                <input type="url" name="map_link" id="map_link" class="form-control" size="60" value="{{ $venue->map_link }}">
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="directions">Driving directions:</label>
                    <input
                        type="hidden"
                        name="item"
                        value="{{ html_entity_decode($venue->directions ?? '') }}"
                        >
                    <trix-editor input="element-item" class="form-control" style="min-height: 200px;"></trix-editor>
                </div>
            </div>
            <button type="submit" form='edit' class="btn btn-go">Submit</button>
        </form>
    </div>
@endsection
