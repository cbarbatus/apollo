@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Unified Alert Components --}}
        <x-alert-success />
        <x-alert-danger />

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">Edit Ritual Schedule</h4>
            </div>

            <div class="card-body p-4">
                <form method="POST" action="{{ route('schedule.update', $element->id) }}" id="edit">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="schedule-item" class="form-label fw-bold">Schedule Text (Displays on Homepage):</label>
                        <input id="schedule-item" type="hidden" name="item" value="{{ html_entity_decode($element->item ?? '') }}">
                        {{-- Trix editor with Apollo-style shadow --}}
                        <trix-editor input="schedule-item" class="form-control bg-light shadow-sm" style="min-height: 400px;"></trix-editor>
                    </div>

                    <div class="d-flex gap-2">
                        <x-apollo-button type="submit">
                            Update Schedule
                        </x-apollo-button>
                        <x-cancel-button href="/"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
