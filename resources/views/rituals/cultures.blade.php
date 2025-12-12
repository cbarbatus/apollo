@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Edit Ritual Cultures</h1>

        <form method="post" action="/elements/{{ $element->id }}" id="edit">
            @crsf
            @method('put')

            <div class="col-md-4 mb-3">
                <label for="name" class="form-label">Name:</label>
                <input type="text" class="form-control" name="name" id="name" size="20" value="{{ $element->name }}">
            </div>

            <div class="col-md-4 mb-3">
                <label for="item">Text:</label>
            <textarea id="item" name="item" rows="2" cols="60"
                      value="{{ $element->item }}">{{ html_entity_decode($element->item) }}
            </textarea>
            <br><br>
        </form>
        <button type="submit" form='edit' class="btn btn-warning">Submit</button>
        <br>
    </div>
    <br>

@endsection
