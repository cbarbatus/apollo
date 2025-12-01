@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Venue Details</h1>

        <br><b>Name: </b>{{ $venue['name']}}
        <br><b>Title: </b>{{ $venue['title'] }}
        <br><b>Address: </b>{{ $venue['address'] }}
        <br><b>Map URL: </b>{{ $venue['map_link'] }}
        <br><b>Driving directions: </b>{{ htmlentities($venue['directions']) }}
        <br><br>

        <form method="get" action="/venues/{{ $venue['id']}}/edit" id="edit">
        </form>
        <button type="submit" form='edit' class="btn btn-warning">Edit</button>

        <form method="get" action="/venues/{{ $venue['id'] }}/sure" id="sure">
        </form>
            <button type="submit" form="sure" class="btn btn-danger">Delete</button>

@endsection
