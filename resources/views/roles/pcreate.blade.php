@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create Permissions</h1>
        <br><br>
        <form method="post" action="/roles/pstore" id="pcreate">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <label for="name">Name:</label>
            <input type="text" name="name" id="name">
            <br><br>
        </form>
        <button type="submit" form='pcreate' class="btn btn-go">Submit</button>

    </div>
@endsection
