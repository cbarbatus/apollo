@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1 class="display-6 fw-bold border-bottom pb-3 mb-4">Edit Venue: {{ $venue->name }}</h1>

        <form method="post" action="{{ route('venues.update', $venue->id) }}" id="edit-venue-form">
            @csrf
            @method('PUT')

            <div class="row g-4">
                {{-- Name --}}
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label fw-bold">Internal Name (Slug):</label>
                    <input type="text" name="name" id="name" class="form-control shadow-sm" value="{{ old('name', $venue->name) }}">
                </div>

                {{-- Title --}}
                <div class="col-md-6 mb-3">
                    <label for="title" class="form-label fw-bold">Public Title:</label>
                    <input type="text" name="title" id="title" class="form-control shadow-sm" value="{{ old('title', $venue->title) }}">
                </div>

                {{-- Address --}}
                <div class="col-12 mb-4">
                    <label for="address" class="form-label fw-bold">Full Address:</label>
                    <textarea name="address" id="address" class="form-control shadow-sm" rows="3">{{ old('address', $venue->address) }}</textarea>
                </div>
            </div>
            {{-- Map Link --}}
            <div class="col-12 mb-3">
                <label for="map_link" class="form-label fw-bold">Map Link:</label>
                <input type="text" name="map_link" id="map_link" class="form-control shadow-sm"
                       value="{{ old('map_link', $venue->map_link) }}">
            </div>

            {{-- Driving Directions with Trix --}}
            <div class="col-12 mb-4">
                <label for="directions" class="form-label fw-bold">Driving Directions:</label>

                {{-- Hidden input that actually sends the data to Laravel --}}
                <input id="directions" type="hidden" name="directions" value="{{ old('directions', $venue->directions) }}">

                {{-- The Trix Editor UI --}}
                <trix-editor input="directions" class="trix-content shadow-sm bg-white" style="min-height: 200px;"></trix-editor>
            </div>

            {{-- Apollo Standard Buttons - INSIDE the form --}}
            <div class="mt-4 pt-4 border-top d-flex gap-3">
                <x-apollo-button type="submit">
                    <i class="bi bi-save me-2"></i>Update Venue
                </x-apollo-button>

                <x-cancel-button href="{{ route('venues.index') }}" />
            </div>
        </form>
    </div>
@endsection
