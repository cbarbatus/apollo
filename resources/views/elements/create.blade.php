@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Create a Text Element</h1>

        <form method="post" action="/elements" id="create">
            @csrf

            <input type="hidden" name="section_id" id="section_id" value="{{ $section_id }}">

            {{-- Title Field --}}
            <div class="col-md-4 mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" id="title" class="form-control" maxlength="40" required>
            </div>

            <div class="row">
                {{-- Name Field (Shortened to 2 columns on medium screens and up) --}}
                <div class="col-md-2 mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" maxlength="20" required>
                </div>

                {{-- Sequence Field (Shortened to 1 columns on medium screens and up) --}}
                <div class="col-md-1 mb-3">
                    <label for="sequence" class="form-label">Sequence:</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" value="0">
                    {{-- The 'form-control' class is retained, but the 'col-md-2' limits its width --}}
                </div>
            </div>

            {{-- Text/Item Field with Trix (Width limited to 8 columns) --}}
            <div class="row">
                <div class="col-md-8 mb-3">
                    <label for="element-item" class="form-label">Text (Content):</label>

                    <input
                        id="element-item"
                        type="hidden"
                        name="item"
                        value=""
                    >
                    {{-- The 'col-md-8' limits the width of the Trix editor --}}
                    <trix-editor input="element-item" class="form-control" style="min-height: 200px;"></trix-editor>
                </div>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Submit</button>
        </form>
    </div>

@endsection
