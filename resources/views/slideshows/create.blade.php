@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create a Slideshow</h1>
        <br><br>
        <form method="post" action="/slideshows" id="create">
            @csrf
            <br>
            <label for="year">Year:</label>
            <input type="text" name="year" id="year" size="4">
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="name" size="10">
            <br>
            <label for="title">Title</label>
            <input type="text" name="title" id="title" size='40'>
            <br>
            <span class="note">Before creating the slideshow here, create the presentation at
                <a href="https://docs.google.com/presentation/u/0/"> Google Slides</a> and note its 45 character id between the '/d/' and ending '/' of the sharing link. </span>
            <br>
            <label for="google_id">Google Slideshow ID:</label>
            <input type="text" name="google_id" id="google_id" size="60">
            <br>
            <label for="sequence">Sequence:</label>
            <input type="number" name="sequence" id="sequence" size="4">
            <br><br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>
        <br><br>
    </div>
@endsection
