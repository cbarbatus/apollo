@extends('layouts.app')

@section('content')

    <div class='container'>

        <div class="container mt-3">
            {{-- Success Message (Green) --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle-fill me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- Error Message (Red) --}}
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
        </div>

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

            <button type="submit" form="one-ritual" class="btn btn-success px-4 fw-bold">Select</button>
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
                                <a href="{{ route('rituals.show', $ritual->id) }}" class="btn btn-primary btn-sm">Details</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 3. THE SPIGOT (The Year Buttons) --}}
        <p class="mb-1">Choose a ritual year</p>
        <ul class="list-unstyled d-flex flex-wrap gap-1 mb-4">
            @foreach ($activeYears as $y)
                    <a href="{{ route('rituals.index', ['year' => $y]) }}"
                       class="btn btn-ritual-year {{ $y == $selectedYear ? 'btn-primary' : 'btn-outline-secondary' }}"
                       data-text="{{ $y }}"> {{-- This 'data-text' reserves the space --}}
                        {{ $y }}
                    </a>

            @endforeach

        </ul>



    </div>
@endsection
