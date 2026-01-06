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
                        <code class="text-danger fw-bold">
                            {{ Str::limit($litName, 47, '')}}
                        </code>
                        @if(strlen($litName) > 50)
                            <br><small class="text-warning"><i class="bi bi-exclamation-triangle"></i> Original name was shortened to fit 50-character limit.</small>
                        @endif
                    </p>
                </div>

                <form method="POST" action="{{ route('rituals.storelit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $ritual->id }}">
                    <input type="hidden" name="litName" value="{{ $litName }}">

                    <div class="mb-4">
                        <label for="file" class="form-label fw-bold">Select .htm File:</label>
                        {{-- w-50 or col-md-6 keeps the input from stretching across the whole screen --}}
                        <div style="max-width: 500px;">
                            <input type="file" name="file" class="form-control form-control-lg border-0 bg-light shadow-sm" required>
                            <div class="form-text">Max 50 characters recommended.</div>
                        </div>
                    </div>

                    <div class="d-flex gap-2">
                        <x-apollo-button type="submit">
                            Upload Liturgy
                        </x-apollo-button>
                        <x-cancel-button href="{{ route('rituals.index', ['year' => $ritual->year]) }}"/>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
