@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Delete Book ID {{$id }}: Are you sure?</h1>

        <form method="post" action="/books/{{ $id }}/destroy" id="sure">
            @csrf
            @method('DELETE')
            <button type="submit" form="sure" class="btn btn-danger">Confirm Delete</button>
        </form>

    </div>
    <br>
@endsection
