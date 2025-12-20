@extends('layouts.app')

@section('content')
    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>Whoops! Something went wrong:</strong>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="container">
        <h1>Edit Element: {{ $element->id }}</h1>

        <form method="POST" action="{{ url('elements/' . $element->id . '/update') }}">
            @csrf
            <input type="hidden" name="section_id" value="{{ $element->section_id }}">

            <div class="row mb-3">
                <div class="col-md-5">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ $element->title }}">
                </div>
                <div class="col-md-5">
                    <label for="name" class="form-label">Internal Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $element->name }}">
                </div>
                <div class="col-md-2">
                    <label for="sequence" class="form-label">Sequence</label>
                    <input type="number" id="sequence" name="sequence" class="form-control" value="{{ $element->sequence }}">
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 mb-3">
                    <label for="trix-item-input">Element Content:</label>

                    <input
                        type="hidden"
                        name="item"
                        id="trix-item-input"
                        value="{{ $element->item }}"
                    >
                    @if($element->section_id == 99)
                        <textarea name="item" class="form-control" rows="10">{{ strip_tags($element->item) }}</textarea>
                        <small class="text-muted">Note: Section 99 must remain plain text for ritual dropdowns.</small>
                    @else
                        <trix-editor input="item"></trix-editor>
                        <input id="trix-item-input" type="hidden" name="item" value="{{ $element->item }}">
                    @endif
                </div>
            </div>
            <div class="mt-3">
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="{{ url('sections/' . $element->section_id . '/edit') }}" class="btn btn-outline-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
@endsection
