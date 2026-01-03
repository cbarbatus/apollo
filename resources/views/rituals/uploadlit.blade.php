@extends('layouts.app')

@section('content')
    <div class="container">
        {{-- This tag replaces BOTH red boxes in your screenshot with one clean one --}}
        <x-alert-danger />

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">Upload Ritual Liturgy</h4>
            </div>

            <div class="card-body p-4">
                <x-alert-success />

                <div class="mb-4">
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="bi bi-code-slash me-2"></i>
                        Liturgy for: <strong>{{ $ritual->name }} ({{ $ritual->year }})</strong><br>
                        Files must be <strong>.htm</strong> for public readability.
                    </div>
                    <p class="mt-2 text-muted">The file will be automatically renamed to:
                        <code class="text-danger fw-bold">{{ $litName }}</code>
                    </p>
                </div>

                <form method="POST" action="{{ route('rituals.storelit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $ritual->id }}">
                    <input type="hidden" name="litName" value="{{ $litName }}">

                    <div class="mb-4">
                        <label for="file" class="form-label fw-bold">Select .htm File:</label>
                        <input type="file" name="file" class="form-control form-control-lg border-0 bg-light shadow-sm" required>
                    </div>

                    <div class="d-flex gap-2">
                        <x-apollo-button type="submit">
                            Upload Liturgy
                        </x-apollo-button>
                        <a href="{{ route('rituals.index', ['year' => $ritual->year]) }}" class="btn btn-secondary px-4 border-0">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
