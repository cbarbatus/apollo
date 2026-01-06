@extends('layouts.app')

@section('content')
    <div class="container my-5">
        <div class="card border-0 shadow-sm rounded-3" style="max-width: 600px;">
            <div class="card-header bg-primary text-white py-3">
                <h4 class="mb-0 fw-bold">Upload Files</h4>
            </div>

            <div class="card-body p-4">
                {{-- The original instructions, styled cleanly --}}
                <p class="text-muted small">
                    Files can be directed to private or public visibility. There are three public directories:
                    <strong>/img</strong> (images), <strong>/liturgy</strong> (ritual texts), and <strong>/contents</strong> (general files) [cite: 2026-01-05].
                </p>
                <p class="text-muted small mb-4">
                    Images must be <strong>.jpg</strong>, ritual texts <strong>.htm</strong>, and private files
                    <strong>.pdf</strong> or <strong>.docx</strong> only. Max size: 2MB [cite: 2026-01-05].
                </p>

                <form method='post' action='/grove/uploadFile' enctype='multipart/form-data'>
                    @csrf
                    <div class="mb-3">
                        <label class="form-label fw-bold">Select File:</label>
                        <input type='file' name='file' class="form-control border-0 bg-light shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold">Target Location:</label>
                        <select name="visibility" class="form-select border-0 bg-light shadow-sm">
                            <option value="grove">Private</option>
                            <option value="public">Public (/contents)</option>
                            <option value="liturgy">Liturgy</option>
                            <option value="images">Images (/img)</option>
                        </select>
                    </div>

                    <div class="d-flex gap-2">
                        <x-apollo-button type="submit">Upload File</x-apollo-button>
                        <x-cancel-button href="/" />
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

