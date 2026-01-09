@extends('layouts.app')

@section('content')

    <div class='container'>

        <h1>Slideshows Selection</h1>

        @auth
            <div class="row mb-4">
                <div class="col-md-4">
                    <x-apollo-button href="/slideshows/create" color="primary" class="fw-bold px-4 shadow-sm">
                        New Slideshow
                    </x-apollo-button>
                </div>
            </div>
        @endauth

        <h3>Choose one slideshow</h3>

        <form action="{{ route('slideshows.index') }}" method="GET" id="oneshow" class="d-flex align-items-end gap-2 mb-4">
            @csrf
            <select name="year" id="year" class="form-select w-auto">
                @foreach ($activeYears as $year)
                    <option value="{{ $year }}" {{ request('year') == $year ? 'selected' : '' }}>
                        {{ $year }}
                    </option>
                @endforeach
            </select>

            <select name="choice_id" id="choice_id" class="form-select w-auto">
                <option value="">-- Select Slideshow --</option>
                @foreach ($activeSlideshows as $slideshow)
                    <option value="{{ $slideshow->id }}" {{ request('choice_id') == $slideshow->id ? 'selected' : '' }}>
                        {{ $slideshow->name }}
                    </option>
                @endforeach
            </select>

            <x-apollo-button type="submit" form="oneshow" color="success">Select</x-apollo-button>
        </form>

        {{-- 1. Standardized Management Card --}}
        {{-- Only show the Management Card to Admin or SeniorDruid --}}
        @if(isset($choiceId) && auth()->user()?->hasAnyRole(['admin', 'SeniorDruid']))
            <div class="row">
                <div class="col-md-6 col-lg-5">
                    <div class="card mb-4 bg-light shadow-sm border-0">
                        <div class="card-body">
                            <h5 class="card-title text-muted small uppercase">Manage Slideshow</h5>
                            <p class="h5 mb-3">{{ $selectedName }} {{ $selectedYear }}</p>
                            <div class="d-flex gap-2">
                                <x-apollo-button href="{{ route('slideshows.view', ['id' => $choiceId]) }}" color="primary">
                                    View
                                </x-apollo-button>
                                <x-apollo-button href="{{ route('slideshows.edit', $choiceId) }}" color="warning">Edit</x-apollo-button>
                                <x-delete-button :action="url('slideshows/' . $choiceId)" resource="Slideshow" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- 2. THE SPIGOT (Unified Wall of Years) --}}
        <p class="mb-1 fw-bold small text-secondary uppercase">Choose an event year</p>
        <div class="d-flex flex-wrap gap-1 mb-5">
            @foreach ($activeYears as $y)
                <x-apollo-button
                    href="{{ route('slideshows.index', ['year' => $y]) }}"
                    color="{{ $y == $selectedYear ? 'primary' : 'light' }}"
                    size="sm"
                    class="px-2 shadow-none border {{ $y == $selectedYear ? '' : 'text-primary' }}"
                    style="min-width: 45px; font-weight: 500;"
                >
                    {{ $y }}
                </x-apollo-button>
            @endforeach
        </div>

    </div>
@endsection
