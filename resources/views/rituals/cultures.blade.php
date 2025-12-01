@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Edit Ritual Cultures</h1>
        <br>
        <form method="post" action="/elements/{{ $element->id }}" id="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            @method('put')
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="namee" size="20" value="{{ $element->name }}">
            <br>
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
