@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Edit a Slideshow</h1>

        <form method="post" action="/slideshows/{{ $slideshow->id }}" id="edit">
            @csrf
            @method('put')

            <div class="row">
                <div class="col-md-1 mb-3">
                    <label for="year">Year:</label>
                    <input type="text" name="year" id="year" size='4' value="{{ $slideshow->year }}">
                </div>
                <div class="col-md-2 mb-3">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" size='10' value="{{ $slideshow->name }}">
                </div>
            </div>

            <div class="col-md-4 mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" size='40' value="{{ $slideshow->title }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="google_id">Google Slideshow ID:</label>
                <input type="text" name="google_id" id="google_id" size="80" value="{{ $slideshow->google_id }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="sequence">Sequence:</label>
                <input type="number" name="sequence" id="sequence" size="4" value="{{ $slideshow->sequence }}">
            </div>
            <button type="submit" form='edit' class="btn btn-go">Submit</button>
        </form>
    </div>
    <br>
@endsection
