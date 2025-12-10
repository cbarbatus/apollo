@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1 class="mb-4">Edit Text Element {{ $element->id }}</h1>

        <form method="post" action="{{ route('elements.update', $element->id) }}" id="edit">
            @csrf
            @method('put') {{-- Essential: This tells Laravel to use the PUT route --}}

            <input type="hidden" name="id" value="{{ $element->id }}">
            {{-- Title Field --}}
            <div class="col-md-4 mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-control" value="{{ $element->title }}" required>
            </div>

            <div class="row">
                {{-- Name Field --}}
                <div class="col-md-2 mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="namee" class="form-control" value="{{ $element->name }}" required>
                </div>

            {{-- Sequence Field --}}
                <div class="col-md-1 mb-3">
                    <label for="sequence" class="form-label">Sequence:</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" value="0">
                    {{-- w-25 limits the width for a small input field --}}
                </div>
            </div>


            {{-- Text Field (Trix Editor Implementation) --}}
                <div class="row">
                    <div class="col-md-8 mb-3">
                        <label for="item" class="form-label">Text:</label>
                    @if ($element->name === 'donate')
                        {{-- CASE 1: Donate Section (Forces plain <textarea> for complex HTML) --}}
                        <textarea id="item" name="item" rows="15" cols="80" class="form-control">{{ $element->item }}</textarea>
                    @else
                        {{-- CASE 2: All Other Sections (Uses Trix editor) --}}
                        <input
                            id="element-item"
                            type="hidden"
                            name="item"
                            value="{{ html_entity_decode($element->item ?? '') }}"
                        >
                        <trix-editor input="element-item" class="form-control" style="min-height: 200px;"></trix-editor>
                    @endif
                    </div>
                </div>
                <button type="submit" class="btn btn-primary mt-3">Submit</button
        </form>
     </div>


@endsection
