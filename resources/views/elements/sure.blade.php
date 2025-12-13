@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Delete Element ID {{ $id }}: Are you sure?</h1>


        {{-- Use simple $id in form action --}}
        <form method="get" action="/elements/{{ $id }}/destroy" id="sure">
            <button type="submit" class="btn btn-danger">Yes, Delete It</button>
        </form>

        <br>
        {{-- Use simple $id in link href --}}
        <a href="/elements/{{ $id }}/edit" class="btn btn-secondary">Cancel</a>
    </div>

@endsection
