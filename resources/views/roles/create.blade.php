@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create Roles</h1>
        <br><br>
        <form method="post" action="/roles/store" id="create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
            <br><br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>

    </div>
@endsection
