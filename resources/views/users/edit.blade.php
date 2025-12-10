@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Edit a User</h1>

        <form method="post" action="/users/{{ $user->id }}/update" id="edit">
            @csrf
            @method('put')

            <div class="col-md-4 mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" readonly name="name" id="name" class="form-control" value="{{ $user->name }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="email" class="form-label">Email:</label>
                <input type="text" readonly name="email" id="email" class="form-control" value="{{ $user->email }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="role">Roles:</label>
            </div>

            @foreach($roles as $role)
                <input type="checkbox" name="role[]" value={{ $role->name }} {{ $checks[$role->name] }}> {{ $role->name }} <br/>
            @endforeach

            <br><br>
        </form>
        <button type="submit" form='edit' class="btn btn-go">Submit</button>
    </div>
    <br>
@endsection
