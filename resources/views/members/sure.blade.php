@extends('layouts.app')

@section('content')
    <div class='container py-4'>

        <h1 class="text-danger mb-4">Delete Member {{ $name }}: Are you absolutely sure?</h1>

        <div class="alert alert-warning border-0 shadow-sm mb-4" role="alert">
            <p class="fw-bold">Warning:</p>
            <p class="mb-0">This action will permanently remove or flag the member record. This cannot be easily undone.</p>
        </div>

        <form method="post" action="/members/{{ $id }}" id="delete-form">
            @csrf
            @method('DELETE')

            <p class="lead mb-4">Clicking the button below will execute the deletion.</p>

            <button type="submit" class="btn btn-danger btn-lg">
                <i class="bi bi-trash-fill me-2"></i> Confirm Permanent Delete
            </button>

            <a href="{{ url()->previous() }}" class="btn btn-secondary btn-lg ms-3">
                Cancel
            </a>

        </form>
    </div>
@endsection
