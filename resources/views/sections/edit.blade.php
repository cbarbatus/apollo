@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Edit Text Section {{ $section->id }}</h1>

        <form method="post" action="/sections/{{ $section->id }}/update" id="edit">
            @csrf
            @method('put')

            <div class="col-md-4 mb-3">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" size="40" value="{{ $section->title }}">
            </div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" size="20" value="{{ $section->name }}">
                </div>

                <div class="col-md-1 mb-3">
                    <label for="sequence" class="form-label">Sequence:</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" size="4" value="{{ $section->sequence }}">
                </div>
            </div>
            <button type="submit" form='edit' class="btn btn-go">Submit</button>
        </form>

        <hr class="my-5">

        <h3>Elements in Section {{ $section->id }}</h3>

        <br>
        <form method="get" action="/elements/{{ $section->id }}/create" id="create">
        </form>
        <button type="submit" form='create' class="btn btn-warning">New Element</button>
        <br><br>


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
            @foreach($elements as $element)
                <tr>
                    <td>{{$element->id}}</td>
                    <td>{{$element->name}}</td>
                    <td>{{$element->title}}</td>
                    <td>{{$element->sequence}}</td>
                    <td><form method="get" action="/elements/{{ $element['id']}}/edit" id="edit">
                            @csrf
                            @method('GET')
                            <button type="submit" class="btn btn-warning" >Edit</button>
                        </form>
                    </td>
                    <td>
                        <form method="get" action="/elements/{{ $element['id']}}/sure" id="sure">
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
    <br>
@endsection
