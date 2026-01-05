@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Create a Slideshow</h1>

        <form method="post" action="/slideshows" id="create">
            @csrf

            <div class="row">
                <div class="col-md-1 mb-3">
                    <label for="year">Year:</label>
                 <input type="text" name="year" id="year" size="4">
                </div>

                <div class="col-md-1 mb-3">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" size="10">
                </div>
            </div>


            <div class="col-md-4 mb-3">
                <label for="title">Title</label>
                <input type="text" name="title" id="title" size='40'>
            </div>

            <span class="note">Before creating the slideshow here, create the presentation at
                <a href="https://docs.google.com/presentation/u/0/"> Google Slides</a> and note its 45 character id between the '/d/' and ending '/' of the sharing link. </span>

            <div class="col-md-4 mb-3">
                <label for="google_id">Google Slideshow ID:</label>
                <input type="text" name="google_id" id="google_id" size="60">
            </div>

            <div class="col-md-4 mb-3">
                <label for="sequence">Sequence:</label>
                <input type="number" name="sequence" id="sequence" size="4">
            </div>
            <x-apollo-button type="submit" form='create'>Submit</x-apollo-button>
        </form>
    </div>
@endsection
