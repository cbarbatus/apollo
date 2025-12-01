@extends('layouts.app')

@section('content')

    <div class='container'>


        <h1>Roles</h1>

        <br>
        <form method="get" action="/roles/create" id="create">
        </form>
        <button type="submit" form='create' class="btn btn-warning">New Role</button>
        <br><br>


        <table class="table table-striped">
            <thead>
            <tr style="font-weight:bold">
                <td>Name</td>
            </tr>
            </thead>
            <tbody>
            @foreach($roles as $role)
                <tr>
                    <td>{{$role->name}}</td>
                    <td><form method="get" action="/roles/{{ $role->name}}/edit" id="edit">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('GET')
                            <button type="submit" class="btn btn-warning" >Edit</button>
                        </form>
                    </td>

                    <td>
                        <form method="get" action="/roles/{{ $role->name }}/sure" id="sure">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('GET')
                            <button type="submit" class="btn btn-danger" >Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <br><br>

        <h2>Permissions</h2>

        <br>
        <form method="get" action="/roles/pcreate" id="pcreate">
        </form>
        <button type="submit" form='pcreate' class="btn btn-warning">New Permission</button>
        <br><br>


        <table class="table table-striped">
            <thead>
            <tr style="font-weight:bold">
                <td>Name</td>
            </tr>
            </thead>
            <tbody>
            @foreach($permissions as $permission)
                <tr>
                    <td>{{$permission->name}}</td>
                    <td>
                        <form method="get" action="/roles/{{ $permission->name }}/psure" id="sure">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('GET')
                            <button type="submit" class="btn btn-danger" >Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
<br>
@endsection
