@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Delete Role {{ $name }}: Are you sure?</h1>
        <br><br>
        <form method="get" action="/roles/{{ $name }}/destroy" id="sure">
        </form>
        <button type="submit" form="sure" class="btn btn-danger">Confirm Delete</button>

@endsection
