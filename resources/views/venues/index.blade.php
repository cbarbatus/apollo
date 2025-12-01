@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Venues</h1>

        <br>
        <form method="get" action="/venues/create" id="create">
        </form>
        <button type="submit" form='create' class="btn btn-warning">New Venue</button>
        <br><br>


        <table class="table table-striped">
            <thead>
            <tr style="font-weight:bold">
                <td>ID</td>
                <td>Name</td>
                <td>Title</td>
                <td>Address</td>
                <td>Map URL</td>
                <td>Directions</td>
                <td colspan="2">Action</td>
            </tr>
            </thead>
            <tbody>
            @foreach($venues as $venue)
                <tr>
                    <td>{{$venue->id}}</td>
                    <td>{{$venue->name}}</td>
                    <td>{{$venue->title}}</td>
                    <td>{{$venue->address}}</td>
                    <td>{{$venue->map_link}}</td>
                    <td>{{substr(html_entity_decode($venue->directions), 0, 60)}}</td>
                    <td><form method="get" action="/venues/{{ $venue['id']}}/edit" id="edit">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-warning" >Edit</button>
                        </form>
                    </td>
                    <td>
                        <form method="get" action="/venues/{{ $venue['id']}}/sure" id="sure">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-danger" >Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
<br>
@endsection
