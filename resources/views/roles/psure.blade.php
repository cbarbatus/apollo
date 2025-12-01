@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Delete Permission {{ $name }}: Are you sure?</h1>
        <br><br>
        <form method="get" action="/roles/{{ $name }}/pdestroy" id="sure">
        </form>
        <button type="submit" form="sure" class="btn btn-danger">Confirm Delete</button>

@endsection
