@extends('layouts.app')

@section('content')
    {{-- @var \App\Models\Ritual $ritual --}}
    {{-- @var string $litName --}}

    <div class="container">

        <div class="card border-0 shadow-sm rounded-3">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">Upload Ritual Liturgy</h4>
            </div>

            <div class="card-body p-4">
                <x-alert-success />

                <div class="mb-4">
                    <div class="alert alert-info border-0 shadow-sm">
                        <i class="bi bi-info-circle me-2"></i>
                        Liturgy for: <strong>{{ $ritual->name }} ({{ $ritual->year }})</strong><br>
                        Upload <strong>.htm</strong> for the public site, or <strong>.docx</strong> for the private archive.
                    </div>
                    @php
                        // Strip the extension so we only limit the actual name
                        $baseNameOnly = pathinfo($litName, PATHINFO_FILENAME);
                        $limitedBase = str()->limit($baseNameOnly, 45, '');
                    @endphp

                    <p class="mt-2 text-muted">The file will be automatically renamed to:<br>
                        <code class="text-danger fw-bold">{{ pathinfo($litName, PATHINFO_FILENAME) }}</code><span class="fw-bold">.htm</span>
                        <span class="text-muted mx-1">or</span>
                        <code class="text-danger fw-bold">{{ pathinfo($litName, PATHINFO_FILENAME) }}</code><span class="fw-bold">.docx</span>

                        @if(strlen($baseNameOnly) > 45)
                            <br><small class="text-warning">
                                <i class="bi bi-exclamation-triangle"></i>
                                Name was shortened to fit the 50-character limit with extension.
                            </small>
                        @endif
                    </p>
                </div>

                <form method="POST" action="{{ route('rituals.storelit') }}" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $ritual->id }}">
                    <input type="hidden" name="litName" value="{{ $litName }}">

                    <div class="mb-4">
                        <label for="file" class="form-label fw-bold">Select File (.htm or .docx):</label>
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
