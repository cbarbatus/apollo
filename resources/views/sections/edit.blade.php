@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <div class="d-flex justify-content-between align-items-center">
            <h1>Edit Section: {{ $section->id }}</h1>

        </div>

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

            {{-- Input Row --}}
            <div class="row">
                <div class="col-md-2">
                    <label for="sequence" class="form-label">Sequence</label>
                    <input type="number" name="sequence" value="0" class="form-control mb-3">
                </div>
            </div>

            {{-- Action Row: Now separated to avoid the "jammed" look --}}
            <div class="d-flex gap-2 mt-2 mb-4">
                <x-apollo-button type="submit" color="primary" class="fw-bold px-4">
                    Save Section Changes
                </x-apollo-button>

                <x-cancel-button href="/sections" />
            </div>

        </form>

        <x-delete-button
            :action="url('sections/' . $section->id)"
            resource="Section"
        />

        <hr class="my-5">

        <div class="d-flex justify-content-between align-items-center mb-3">
            <x-apollo-button
                href="/elements/create?section_id={{ $section->id }}"
                color="primary"
                {{-- Using the exact Phoenix brand color to match 'Save Section Changes' --}}
                style="background-color: #0056b3 !important; border-color: #0056b3 !important;"
                class="fw-bold px-4 text-white">
                <i class="bi bi-plus-lg me-2"></i> Add New Element
            </x-apollo-button>
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
                        <div class="d-flex gap-2">
                            <x-apollo-button
                                href="/elements/{{ $element->id }}/edit"
                                color="warning"
                                size="sm"
                            >
                                Edit
                            </x-apollo-button>

                            <x-delete-button
                                :action="url('/elements/' . $element->id)"
                            />
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@endsection
