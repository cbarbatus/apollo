@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- Phoenix Card: border-0 and shadow-sm for depth --}}
        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">Upload Announcement Picture right here</h4>
            </div>

            <div class="card-body p-4">
                {{-- Standardized Apollo Success/Message Alert --}}
                <x-alert-success />

                <div class="mb-4">
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="bi bi-info-circle me-2"></i>
                        Picture file must be <strong>.jpg</strong> and less than <strong>2MB</strong>. <br>
                        It will be automatically renamed to: <code>{{ $picname }}</code>
                    </div>
                </div>

                {{-- Updated Action: Points to the new named route --}}
                <form method="POST" action="{{ route('announcements.storepic') }}" enctype="multipart/form-data">
                    @csrf
                    {{-- Mirroring the controller's expectation for 'id' and 'picname' --}}
                    <input type="hidden" name="id" value="{{ $announcement->id }}">
                    <input type="hidden" name="picname" value="{{ $picname }}">

                    <div class="mb-4">
                        <label for="file" class="form-label fw-bold">Select Picture File:</label>
                        <input type="file" name="file" class="form-control form-control-lg border-0 bg-light shadow-sm" required>

                    say what?
                    </div>

                    <div class="d-flex gap-2">
                        {{-- Standardized Phoenix Buttons --}}
                        <x-apollo-button type="submit">
                            Upload Picture
                        </x-apollo-button>
                        <x-cancel-button href="{{ route('announcements.index') }}"/>

                        <a href="{{ route('announcements.index') }}" class="btn btn-secondary px-4 border-0">
                            Cancel
                        </a>
                        <x-cancel-button href="{{ route('announcements.index') }}"/>
                        <x-cancel-button href="{{ route('venues.index') }}" />
                        <x-cancel-button href="{{ route('venues.index') }}" />
                        what??
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
