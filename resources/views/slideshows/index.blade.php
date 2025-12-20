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

        <h1>Slideshows Selection</h1>

        @auth
            <div class="my-4">
                <form method="get" action="/slideshows/create" id="create"></form>
                <button type="submit" form="create" class="btn btn-warning">New Slideshow</button>
            </div>
        @endauth

        <h3>Choose one slideshow</h3>

        <form action="{{ route('slideshows.index') }}" method="GET" id="oneshow" class="d-flex align-items-center gap-2 mb-4">
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
                @foreach ($activeNames as $name)
                    <option value="{{ $name }}" {{ request('name') == $name ? 'selected' : '' }}>
                        {{ $name }}
                    </option>
                @endforeach
            </select>

            <button type="submit" form="oneshow" class="btn btn-success">Select</button>
        </form>

        @if(isset($choiceId))
            <div class="card border-primary shadow-sm mb-4 mx-auto" style="max-width: 600px;">
                <div class="card-body py-3 text-center">
                    <h5 class="card-title">Selected: <strong>{{ $selectedName }} {{ $selectedYear }}</strong></h5>
                    <div class="d-flex justify-content-center gap-2 mt-3">
                        <a href="{{ route('slideshows.show', $choiceId) }}" class="btn btn-primary">View</a>
                        <a href="{{ route('slideshows.edit', $choiceId) }}" class="btn btn-warning">Edit</a>

                        {{-- The Cleanup Button --}}
                        <form action="{{ route('slideshows.destroy', $choiceId) }}" method="POST"
                              onsubmit="return confirm('Are you sure you want to delete this specific entry?');"
                              style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endif

        <h3>Choose an event year</h3>
        <ul class="list-unstyled d-flex flex-wrap gap-3">
            @foreach ($activeYears as $year)
                <li class="nav-item">
                    {{-- REMOVED 'admin' => $admin from the route array below --}}
                    <a class="btn btn-sm btn-outline-secondary"
                       href="{{ route('slideshows.index', ['year' => $year]) }}">
                        {{ $year }}
                    </a>
                </li>
            @endforeach
        </ul>

        {{-- Give the horizontal rule some vertical margin (top and bottom) --}}
        <hr class="my-5">

        <div class="results-container pb-5">
            @if($slideshows->isNotEmpty())
                {{-- Heavy Header to match the 'Year Choice' buttons --}}
                <h3 class="fw-bolder text-dark mb-4 ms-1">
                    Slideshows Found for {{ request('year') ?? 'All Years' }}
                </h3>

                <div class="list-group shadow-sm border-0">
                    @foreach($slideshows as $slideshow)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3 border-start-0 border-end-0">
                            <div>
                                {{-- Lighter Name for elegance and balance --}}
                                <span class="fs-5 fw-light text-secondary">{{ $slideshow->name }}</span>
                                {{-- Small Pill Badge for the year --}}
                                <span class="badge rounded-pill bg-light text-muted border ms-2 fw-normal">{{ $slideshow->year }}</span>
                            </div>

                            <a href="{{ route('slideshows.show', $slideshow->id) }}" class="btn btn-primary px-4 shadow-sm">
                                View Slideshow
                            </a>
                        </div>
                    @endforeach
                </div>
            @elseif(request('year'))
                <h3 class="mt-5">Slideshows Found for {{ request('year') }}</h3>
                <div class="list-group shadow-sm">
                    @foreach($slideshows as $slideshow)
                        <div class="list-group-item d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="fs-5 fw-bold">{{ $slideshow->name }}</span>
                                <span class="badge rounded-pill bg-light text-dark border ms-2">{{ $slideshow->year }}</span>
                            </div>
                            <a href="{{ route('slideshows.show', $slideshow->id) }}" class="btn btn-primary">
                                View Slideshow
                            </a>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>
@endsection
