@extends('layouts.app')

@section('content')

    <div class='container'>
        {{-- Use simple $id variable, which is now guaranteed to be available --}}
        <h1>Delete Element ID {{ $id }}: Are you sure?</h1>
        <br>
        <br>

        {{-- Use simple $id in form action --}}
        <form method="get" action="/elements/{{ $id }}/destroy" id="sure">
            <button type="submit" class="btn btn-danger">Yes, Delete It</button>
        </form>

        <br>
        {{-- Use simple $id in link href --}}
        <a href="/elements/{{ $id }}/edit" class="btn btn-secondary">Cancel</a>
    </div>

@endsection

