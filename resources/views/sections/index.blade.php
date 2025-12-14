@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Front Page Sections</h1>

        <x-alert-success />

        <div class="row mb-4">
            <div class="col-md-4">
                <a href="{{ url('/sections/create') }}" class="btn btn-warning">New Section</a>
            </div>
        </div>

        <table class="table table-striped">
            <thead>
            <tr style="font-weight:bold">
                <td>ID</td>
                <td>Name</td>
                <td>Title</td>
                <td>Sequence</td>
                <td colspan="2">Action</td>
            </tr>
            </thead>
            <tbody>
            @foreach($sections as $section)
                <tr>
                    <td>{{$section->id}}</td>
                    <td>{{$section->name}}</td>
                    <td>{{$section->title}}</td>
                    <td>{{$section->sequence}}</td>
                    <td><form method="get" action="/sections/{{ $section['id']}}/edit" id="edit">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-warning" >Edit</button>
                        </form>
                    </td>
                    <td>
                        <form method="get" action="/sections/{{ $section['id']}}/sure" id="sure">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-danger" >Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
