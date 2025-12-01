@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Edit a Slideshow</h1>

        <br><br>
        <form method="post" action="/slideshows/{{ $slideshow->id }}" id="edit">
            @csrf
            @method('put')
            <label for="year">Year:</label>
            <input type="text" name="year" id="year" size='4' value="{{ $slideshow->year }}">
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" size='10' value="{{ $slideshow->name }}">
            <br>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" size='40' value="{{ $slideshow->title }}">
            <br>
            <label for="google_id">Google Slideshow ID:</label>
            <input type="text" name="google_id" id="google_id" size="80" value="{{ $slideshow->google_id }}">
            <br>
            <label for="sequence">Sequence:</label>
            <input type="number" name="sequence" id="sequence" size="4" value="{{ $slideshow->sequence }}">
            <br><br>
        </form>
        <button type="submit" form='edit' class="btn btn-go">Submit</button>

    </div>
    <br>
@endsection
