@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create Venues</h1>
        <br><br>
        <form method="post" action="/venues" id="create">
            @csrf
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
            <br>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" size="40">
            <br>
            <label for="address">Address:</label>
            <input type="text" name="address" id="address" size="60">
            <br>
            <label for="map_link">Map URL:</label>
            <input type="url" name="map_link" id="map_link" size="60">
            <br>
            <label for="directions">Driving directions:</label>
            <textarea id="directions" name="directions" rows="4" cols="60"></textarea>
            <br><br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>

    </div>
@endsection
