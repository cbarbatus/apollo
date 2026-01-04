@extends('layouts.app')

@section('content')
    <div class='container py-4'>
        <h1>Create a Ritual</h1>

        <form method="post" action="{{ route('rituals.store') }}" id="createForm">
            @csrf

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="year" class="fw-bold">Year:</label>
                    <input type="number" name="year" id="year" class="form-control"
                           value="{{ old('year', date('Y')) }}" required>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="name" class="fw-bold">Ritual Name:</label>
                    <select name="name" id="name" class="form-select" required>
                        <option value="">-- Select Name --</option>
                        @foreach ($activeNames as $item)
                            <option value="{{ $item }}" {{ old('name') == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-4 mb-3">
                    <label for="culture" class="fw-bold">Culture:</label>
                    <select name="culture" id="culture" class="form-select" required>
                        <option value="">-- Select Culture --</option>
                        @foreach ($cultures as $item)
                            <option value="{{ $item }}" {{ old('culture') == $item ? 'selected' : '' }}>
                                {{ $item }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <x-apollo-button type='submit' color="primary">Submit</x-apollo-button>
            <x-cancel-button></x-cancel-button>
        </form>
    </div>
@endsection
