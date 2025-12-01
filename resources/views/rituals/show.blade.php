@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Ritual Details</h1>

        <br><b>Year: </b>{{ $ritual['year'] }}
        <br><b>Name: </b>{{ $ritual['name'] }}
        <br><b>Culture: </b>{{ $ritual['culture'] }}
        <br><br>

        <form method="get" action="/rituals/{{ $ritual['id']}}/edit" id="edit">
        </form>
        <button type="submit" form='edit' class="btn btn-warning">Edit</button>
        <br><br>

        <form method="get" action="/rituals/{{ $ritual['id']}}/uploadlit" id="uplit">
        </form>
        <button type="submit" form='uplit' class="btn btn-warning">Upload</button>
        <br><br>

        <form method="get" action="/rituals/{{ $ritual['id'] }}/sure" id="sure">
        </form>
        <button type="submit" form="sure" class="btn btn-danger">Delete</button>

        <br><br>


        <form method="get" action="/rituals/1/list" id="list">
        </form>
        <button type="submit" form="list" class="btn btn-go">List</button>
    </div>
    <br>
@endsection
