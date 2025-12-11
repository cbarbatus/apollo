@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Create Venues</h1>

        <form method="post" action="/venues" id="create">
            @csrf

            <div class="col-md-4 mb-3">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name">
            </div>

            <div class="col-md-4 mb-3">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" size="40">
            </div>

            <div class="col-md-4 mb-3">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" size="60">
            </div>

            <div class="col-md-4 mb-3">
                <label for="map_link">Map URL:</label>
                <input type="url" name="map_link" id="map_link" size="60">
            </div>

            <div class="row">
                <div class="col-md-8 mb-3">
                <label for="directions" class="form-label">Driving directions:</label>
                    <input
                        id="directions"
                        type="hidden"
                        name="directions"
                        value=""
                    >
                    {{-- The 'col-md-8' limits the width of the Trix editor --}}
                    <trix-editor input="directions" class="form-control" style="min-height: 200px;"></trix-editor>

                </div>
            </div>
            <button type="submit" form='create' class="btn btn-go">Submit</button>
        </form>
    </div>
@endsection
