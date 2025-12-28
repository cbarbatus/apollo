@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Delete Venue {{ $id }}: Are you sure?</h1>

        <div class="col-md-4 mb-3">
            <form method="post" action="/venues/{{ $id }}/destroy" id="sure">
            @csrf
            @method('DELETE')
            <button type="submit" form="sure" class="btn btn-danger">Yes, Delete It</button>
        </form>
        </div>

        <div class="col-md-4 mb-3">
            <a href="/venues" class="btn btn-secondary">Cancel</a>
        </div>
@endsection
