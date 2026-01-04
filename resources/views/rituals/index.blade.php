@extends('layouts.app')

@section('content')

    <div class='container'>

        <h1>Raven's Cry Grove, ADF Rituals</h1>

        @auth
            {{-- Replace the "New Ritual" link with this Apollo version --}}
            <div class="row mb-4">
                <div class="col-md-4">
                    <a href="{{ url('/rituals/create') }}"
                       class="btn btn-primary shadow-sm px-4 fw-bold border-0 d-inline-flex align-items-center"
                       style="height: 38px; border-radius: 8px;">
                        <i class="bi bi-plus-lg me-2"></i>New Ritual
                    </a>
                </div>
            </div>
        @endauth

        <h3>Choose one ritual</h3>

        <form action="{{ route('rituals.index') }}" method="GET" id="one-ritual" class="d-flex align-items-end gap-2 mb-4">
            @csrf

            <select name="year" id="year" class="form-select w-auto">
                @foreach ($activeYears as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>


                <select name="name" id="name" class="form-select w-auto">
                    <option value="">-- Select Ritual --</option>
                    @foreach($activeNames as $n)
                        <option value="{{ $n }}" {{ ($n == $selectedName) ? 'selected' : '' }}>
                            {{ $n }}
                        </option>
                    @endforeach
                </select>

            <x-apollo-button type="submit" form="one-ritual" color="success">Select</x-apollo-button>
        </form>

        @if(auth()->check() && $ritual)
            <div class="row">
                <div class="col-md-6 col-lg-5">
                    <div class="card mb-4 bg-light shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted small uppercase">Manage Ritual</h5>
                            <p class="h5 mb-3">{{ $ritual->name }} {{ $ritual->year }}</p>
                            <div class="d-flex gap-2">
                                <a href="/rituals/{{ $ritual->id }}/display" class="btn btn-info btn-sm text-white">Public View</a>
                                <x-apollo-button href="{{ route('rituals.show', $ritual->id) }}">Details</x-apollo-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. THE SPIGOT (The "Good Wall" Version) --}}
        <p class="mb-1 fw-bold small text-secondary uppercase">Choose a ritual year</p>
        <div class="d-flex flex-wrap gap-1 mb-4">
            @foreach ($activeYears as $y)
                <x-apollo-button
                    href="{{ route('rituals.index', ['year' => $y]) }}"
                    color="{{ $y == $selectedYear ? 'primary' : 'light' }}"
                    size="sm"
                    {{-- Add 'border' to define the white boxes on the light background --}}
                    class="px-2 shadow-none border {{ $y == $selectedYear ? '' : 'text-primary' }}"
                    style="min-width: 45px; font-weight: 500;"
                >
                    {{ $y }}
                </x-apollo-button>
            @endforeach
        </div>
    </div>
@endsection
