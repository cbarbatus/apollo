@extends('layouts.app')

@section('content')

    <div class='container'>


        <h1>Role '{{$role->name}}'</h1>

        <br>
        <form method="get" action="/roles/{{ $role->name }}/set" id="pname">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <label for="permission_name">Permission Name to Add:</label>
            <select name="permission_name" id="permission_name">
                @foreach($permissions as $permission)
                        <option value="{{$permission->name}}">{{$permission->name}}</option>
                @endforeach
            </select>
        <br>
        </form>
<br>
        <button type="submit" form='pname' class="btn btn-success">Done</button>
        <br><br>
@endsection
