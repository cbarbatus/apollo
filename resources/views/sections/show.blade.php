@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Ritual Details</h1>

        <br><b>Title: </b>{{ $announcement['title'] }}
        <br><b>Picture File: </b>{{ $announcement['picture_file'] }}
        <br><b>Summary: </b>{{ $announcement['summary'] }}
        <br><b>When: </b>{{ $announcement['when'] }}
        <br><b>Where: </b>{{ $announcement['venue_name'] }}
        <br><b>Notes: </b>{{ htmlentities($announcement['notes']) }}
        <br><br>

        <form method="get" action="/announcements/{{ $announcement['id']}}/edit" id="edit">
        </form>
        <button type="submit" form='edit' class="btn btn-warning">Edit</button>

        <form method="get" action="/announcements/{{ $announcement['id'] }}/sure" id="sure">
        </form>
            <button type="submit" form="sure" class="btn btn-danger">Delete</button>

@endsection
