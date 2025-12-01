@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Delete Book ID {{$id }}: Are you sure?</h1>
        <br><br>
        <form method="get" action="/books/{{ $id }}/destroy" id="sure">
        </form>
            <button type="submit" form="sure" class="btn btn-danger">Confirm Delete</button>
    </div>
    <br>
@endsection
