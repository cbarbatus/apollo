@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <div class="d-flex justify-content-between align-items-center">
            <h1>Edit Section: {{ $section->id }}</h1>

        </div>

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if (session('status'))
            <div class="alert alert-success">{{ session('status') }}</div>
        @endif

        <form method="POST" action="{{ url('sections/' . $section->id . '/update') }}">
            @csrf

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" id="title" name="title" class="form-control" value="{{ $section->title }}">
                </div>
                <div class="col-md-6">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" value="{{ $section->name }}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <label for="sequence" class="form-label">Sequence</label>
                    <input type="number" id="sequence" name="sequence" class="form-control" value="{{ $section->sequence }}">
                </div>
                <div class="col-md-9 d-flex align-items-end justify-content-end">
                    <x-apollo-button type="submit">Save Section Changes</x-apollo-button>
                </div>
            </div>

        </form>

        <x-delete-button
            :action="url('sections/' . $section->id)"
            resource="Section"
        />

        <hr class="my-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <a href="{{ url('elements/create?section_id=' . $section->id) }}"
               class="btn btn-success shadow-sm fw-bold border-0 px-4 py-2 d-inline-flex align-items-center justify-content-center">
                <i class="bi bi-plus-lg me-2"></i> Add New Element
            </a>
        </div>

        <table class="table table-striped border">
            <thead class="table-light">
            <tr>
                <th style="width: 80px;">Seq</th>
                <th>Name</th>
                <th>Content Preview</th>
                <th class="text-end">Actions</th>
            </tr>
            </thead>
            <tbody>
            @foreach($elements as $element)
                <tr>
                    <td>{{ $element->sequence }}</td>
                    <td><strong>{{ $element->name }}</strong></td>
                    <td class="text-muted small">
                        {{ Str::limit(str_replace('&nbsp;', ' ', strip_tags($element->item)), 50) }}
                    </td>
                    <td class="text-end">
                        <a href="{{ url('elements/' . $element->id . '/edit') }}" class="btn btn-sm btn-outline-primary">Edit</a>

                        <x-delete-button
                            :action="url('elements/' . $element->id)"
                            resource="Element"
                        />
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
