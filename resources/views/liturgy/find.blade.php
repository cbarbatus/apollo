@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1 class="mb-3">Full Rituals</h1>

        @if(session('message'))
            <div class="alert alert-warning">
                {{ session('message') }}
            </div>
        @endif

        <p class="text-muted mb-4">Select a ritual name or a culture or both to list rituals. You can then either look at a ritual or download the .docx file.</p>

        <form method="post" action="/liturgy/list" id="create" class="row g-3 align-items-end">
            @csrf

            <div class="col-auto">
                <label for="name" class="form-label fw-bold small">Name:</label>
                <select name="name" id="name" class="form-select" style="min-width: 200px; border-radius: 8px;">
                    <option value="0" selected></option>
                    @foreach($rituals as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <label for="culture" class="form-label fw-bold small">Culture:</label>
                <select name="culture" id="culture" class="form-select" style="min-width: 150px; border-radius: 8px;">
                    <option value="0" selected></option>
                    @foreach($cultures as $item)
                        <option value="{{ $item }}">{{ $item }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-auto">
                <x-apollo-button type="submit" color="success" >Submit</x-apollo-button>
            </div>
        </form>
    </div>
@endsection
