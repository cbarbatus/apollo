@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Delete Ritual ID {{$id }}: Are you sure?</h1>

        <form method="get" action="/rituals/{{ $id }}/destroy" id="sure">
            <button type="submit" form="sure" class="btn btn-danger">Confirm Delete</button>
        </form>
    </div>

@endsection
