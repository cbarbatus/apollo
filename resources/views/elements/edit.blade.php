@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4 display-6 fw-bold">Edit Element: {{ $element->name }}</h1>

        <form method="POST" action="{{ url('elements/' . $element->id . '/update') }}">
            @csrf
            @method('PUT') {{-- Standard for updates --}}
            <input type="hidden" name="section_id" value="{{ $element->section_id }}">

            <div class="card shadow-sm border-0 rounded-3 bg-light">
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Title</label>
                            <input type="text" name="title" class="form-control shadow-sm @error('title') is-invalid @enderror" value="{{ old('title', $element->title) }}">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label fw-bold">Internal Name</label>
                            <input type="text" name="name" class="form-control shadow-sm @error('name') is-invalid @enderror" value="{{ old('name', $element->name) }}">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-bold">Sequence</label>
                            <input type="number" name="sequence" class="form-control shadow-sm text-center" style="max-width: 100px;" value="{{ old('sequence', $element->sequence) }}">
                        </div>
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label fw-bold">Element Content</label>

                        @if($element->section_id == 99)
                            <textarea name="item" class="form-control shadow-sm" rows="10">{{ old('item', strip_tags($element->item)) }}</textarea>
                            <small class="text-muted"><i class="bi bi-info-circle me-1"></i>Section 99 remains plain text for ritual dropdowns.</small>
                        @else
                            <input id="item" type="hidden" name="item" value="{{ old('item', $element->item) }}">
                            <trix-editor input="item" class="trix-content shadow-sm bg-white" style="min-height: 300px; border-radius: 8px;"></trix-editor>
                        @endif
                    </div>

                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold border-0" style="height: 38px; border-radius: 8px;">
                                Save Changes
                            </button>
                            <a href="{{ url('sections/' . $element->section_id . '/edit') }}" class="btn btn-outline-secondary shadow-sm px-4 d-flex align-items-center" style="height: 38px; border-radius: 8px;">
                                Cancel
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        {{-- Delete button positioned relative to the card --}}
        <div class="mt-n5 d-flex justify-content-end" style="margin-top: -54px; margin-right: 25px;">
            <x-delete-button :action="url('elements/' . $element->id)" resource="element" />
        </div>
    </div>
@endsection
