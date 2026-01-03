@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Edit Ritual Parameter</h1>

        <form method="post" action="/rituals/{{ $element->id }}/updateParameter" id="edit">
            @csrf
            @method('put')

            <div class="col-md-2 mb-3">
                <label for="name">Name:</label>
            <input type="text" name="name" id="name" class="form-control fw-bold" size="10" value="{{ $element->name }}" readonly>
            </div>

            <div class="col-md-6 mb-3">
                <label for="item">Text:</label>
                <textarea id="item" name="item" class="form-control" rows="2" cols="60"
                      value="{{ $element->item }}">{{ html_entity_decode($element->item) }}
                </textarea>
            </div>
            <x-apollo-button type="submit" form="edit" color="warning">
                Submit
            </x-apollo-button>
        </form>
    </div>
@endsection
