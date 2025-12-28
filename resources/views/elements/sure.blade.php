@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Delete Element {{ $id }}: Are you sure?</h1>

        <div class="col-md-4 mb-3">
        {{-- Use simple $id in form action --}}
        <form method="get" action="/elements/{{ $id }}/destroy" id="sure">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-danger">Yes, Delete It</button>
        </form>
        </div>

        <div class="col-md-4 mb-3">
            <a href="/sections" class="btn btn-secondary">Cancel</a>
        </div>
    </div>

@endsection
