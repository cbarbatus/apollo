@extends('layouts.app')

@section('content')
    <div class='container py-4'>
        <h1>Edit a Ritual</h1>
        <br>

        <form method="post" action="/rituals/{{ $ritual->id }}" id="edit">
            @csrf
            @method('put')

            <div class="mb-3">
                <label class="fw-bold">Year:</label>
                <span class="ms-2">{{ $ritual->year }}</span>
                <input type="hidden" name="year" value="{{ $ritual->year }}">
            </div>

            <div class="mb-3">
                <label for="name" class="fw-bold">Name:</label>
                <select name="name" id="name" class="form-select w-auto d-inline-block ms-2">
                    @foreach ($activeNames as $item)
                        <option value="{{ $item }}" @selected($ritual->name == $item)>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="culture" class="fw-bold">Culture:</label>
                <select name="culture" id="culture" class="form-select w-auto d-inline-block ms-2">
                    @foreach ($cultures as $item)
                        <option value="{{ $item }}" @selected($ritual->culture == $item)>
                            {{ $item }}
                        </option>
                    @endforeach
                </select>
            </div>

            <br>
        </form>

        <x-apollo-button type="submit">Submit Changes</x-apollo-button>
        <x-cancel-button href="{{ route('rituals.show', $ritual->id) }}"/>

    </div>
@endsection
