@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create an Announcement</h1>
        <br><br>
        <form method="post" action="/announcements" id="create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <br>
            <label for="year">Year:</label>
            <input type="text" name="year" id="year" size="4">
            <label for="name">Name:</label>
            <select name="name" id="name">
                @foreach($rituals as $ritual_name)
                    <option value= {{ $ritual_name }}> {{ $ritual_name }}</option>
                @endforeach
            </select>
            <br>
            <label for="summary">Summary:</label>
            <textarea id="summary" name="summary" rows="15" cols="60">
            </textarea>
            <br>
            <label for="when">When:</label>
            <input type="datetime-local" name="when" id="title" size="40" value="yyyy-mm-dd">
            <br>
            <label for="venue_name">Venue:</label>
            <select name="venue_name" id="venue_name">
                @foreach($locations as $location)
                    <option value="{{$location->name}}">{{$location->name}}</option>
                @endforeach
            </select>
            <span class="note">If the venue is new, create it before a new announcement.</span>
            <br>
            <label for="notes">Notes:</label>
            <textarea id="notes" name="notes" rows="15" cols="60">
            </textarea>
            <br><br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>

    </div>
    <br>
@endsection
