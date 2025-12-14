@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Delete Ritual {{$id }}: Are you sure?</h1>

       <div class="col-md-4 mb-3">
            <form method="get" action="/rituals/{{ $id }}/destroy" id="sure">
                <button type="submit" class="btn btn-danger">Yes, Delete It</button>
            </form>
        </div>
        <div class="col-md-4 mb-3">
            <a href="/rituals/{{ $id }}" class="btn btn-secondary">Cancel</a>
        </div>

    </div>

@endsection
