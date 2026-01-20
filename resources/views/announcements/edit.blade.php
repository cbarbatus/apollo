@extends('layouts.app')

@section('content')
    <div class="container py-5">
        {{-- The 700px Gold Standard Card --}}
        <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px; border-radius: 12px; background-color: #f8f9fa;">
            <div class="card-body p-4">
                <h1 class="display-6 fw-bold mb-1" style="color: #333;">Edit Announcement</h1>
                <p class="text-secondary mb-4">Identity: <strong>{{ $announcement->year }} {{ $announcement->name }}</strong></p>

                <form method="post" action="{{ route('announcements.update', $announcement) }}" id="edit">
                    @csrf
                    @method('put')

                    <div class="row g-3 mb-4">
                        {{-- Compact Year Input --}}
                        <div class="col-md-3">
                            <label for="year" class="form-label text-secondary fw-bold small text-uppercase">Year</label>
                            <input type="text" name="year" id="year" class="form-control" value="{{ $announcement->year }}" style="border-radius: 8px;">
                        </div>

                        {{-- Properly Sized Ritual Dropdown --}}
                        <div class="col-md-9">
                            <label for="name" class="form-label text-secondary fw-bold small text-uppercase">Ritual Name</label>
                            <select name="name" class="form-select" id="name" style="border-radius: 8px;">
                                <option value="">-- Select Ritual --</option>
                                @foreach($rituals as $ritual)
                                    <option value="{{ $ritual }}" {{ $ritual == $announcement->name ? 'selected' : '' }}>
                                        {{ $ritual }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    {{-- Venue Selection --}}
                    <div class="mb-4">
                        <label for="venue_name" class="form-label text-secondary fw-bold small text-uppercase">Venue</label>
                        <select name="venue_name" class="form-select" id="venue_name" style="border-radius: 8px;">
                            @foreach($locations as $location)
                                <option value="{{ $location->name }}" {{ $location->name == $announcement->venue_name ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Trix Summary (Full Width within the 700px card) --}}
                    <div class="mb-4">
                        <label for="summary" class="form-label text-secondary fw-bold small text-uppercase">Summary</label>
                        <input type="hidden" name="summary" id="trix-summary-input" value="{{ $announcement->summary }}">
                        <trix-editor input="trix-summary-input" class="bg-white" style="min-height: 150px; border-radius: 8px;"></trix-editor>
                    </div>

                    {{-- Trix Notes (Matches Summary Style) --}}
                    <div class="mb-4">
                        <label for="notes" class="form-label text-secondary fw-bold small text-uppercase">Notes</label>
                        <input type="hidden" name="notes" id="trix-notes-input" value="{{ $announcement->notes }}">
                        <trix-editor
                            input="trix-notes-input"
                            class="bg-white"
                            style="min-height: 150px; border-radius: 8px;">
                        </trix-editor>
                    </div>

                    {{-- Date/Time Picker --}}
                    <div class="mb-4">
                        <label for="when" class="form-label text-secondary fw-bold small text-uppercase">When</label>
                        <input type="datetime-local" name="when" id="when" class="form-control" value="{{ $announcement->when }}" style="border-radius: 8px;">
                    </div>

                    {{-- Footer Actions --}}
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">
                        <div class="row">
                            <div class="col-md-12">
                                <x-apollo-button type="submit">Submit</x-apollo-button>
                                <x-cancel-button></x-cancel-button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
