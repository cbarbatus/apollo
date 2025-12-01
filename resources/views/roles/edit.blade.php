@extends('layouts.app')

@section('content')

    <div class='container'>


        <h1>Role '{{$role->name}}'</h1>

        <br>
        <form method="get" action="/roles/{{$role->name}}/add" id="add">
        </form>
        <button type="submit" form='add' class="btn btn-warning">Add Permission</button>
        <br><br>


        <table class="table table-striped">
            <thead>
            <tr style="font-weight:bold">
                <td>Permission Name</td>
            </tr>
            </thead>
            <tbody>
            @foreach($pnames as $pname)
                <tr>
                    <td>{{$pname->name}}</td>
                    <td>
                        <form method="get" action="/roles/{{ $role->name }}/{{ $pname->name }}/remove" id="sure">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            @method('GET')
                            <button type="submit" class="btn btn-danger" >Remove</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <br>

        <form method="get" action="../" id="done">
        </form>
        <button type="submit" form='done' class="btn btn-success">Done</button>
        <br><br>

@endsection
