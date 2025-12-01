@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Edit a User</h1>
        <br><br>
        <form method="post" action="/users/{{ $user->id }}/update" id="edit">
            @csrf
            @method('put')
            <label for="name">Name:</label>
            <input type="text" readonly name="name" id="name" value="{{ $user->name }}">
            <br>
            <label for="email">Email:</label>
            <input type="text" readonly name="email" id="email" value="{{ $user->email }}">
            <br>
            <label for="role">Roles:</label>
            <br>
            @foreach($roles as $role)
                <input type="checkbox" name="role[]" value={{ $role->name }} {{ $checks[$role->name] }}> {{ $role->name }} <br/>
            @endforeach

            <br><br>
        </form>
        <button type="submit" form='edit' class="btn btn-go">Submit</button>
    </div>
    <br>
@endsection
