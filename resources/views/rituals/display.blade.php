@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Clean, Dignified Header --}}
        <div class="mb-4">
            <h1 class="h2 fw-bold text-dark border-bottom pb-3">{{ $ritual->year }} {{ $ritual->name }} Ritual</h1>
        </div>

        {{-- The Four Media Pillars --}}
        <div class="row g-3 mb-5">
            {{-- 1. Liturgy --}}
            <div class="col-sm-6 col-md-3">
                @if ($ritual->liturgy_base)
                    <a href="{{ route('rituals.liturgy', $ritual->id) }}" class="btn btn-primary w-100 shadow-sm fw-bold">
                        View Liturgy
                    </a>
                @else
                    <div class="p-2 border rounded text-center text-muted small bg-light">No Liturgy Text</div>
                @endif
            </div>

            {{-- 2. Slideshow --}}
            <div class="col-sm-6 col-md-3">
                @if ($slideshow)
                    <a href="{{ url('/slideshows/' . $slideshow->id) }}" class="btn btn-info w-100 shadow-sm fw-bold">
                        View Slideshow
                    </a>
                @else
                    <div class="p-2 border rounded text-center text-muted small bg-light">No Slideshow</div>
                @endif
            </div>

            {{-- 3. Announcement --}}
            {{-- 3. Announcement Status (Non-button indicator) --}}
            <div class="col-sm-6 col-md-3">
                @if ($announcement)
                    <div class="p-2 border rounded text-center text-success fw-bold bg-white shadow-sm" style="border-color: #198754 !important;">
                        <i class="bi bi-check-circle-fill me-1"></i> Announcement Listed
                    </div>
                @else
                    <div class="p-2 border rounded text-center text-muted small bg-light">No Announcement</div>
                @endif
            </div>

            {{-- 4. Photos --}}
            <div class="col-sm-6 col-md-3">
                @if ($announcement && $announcement->picture_file)
                    <a target="_blank" href="/img/{{ $announcement->picture_file }}" class="btn btn-warning w-100 shadow-sm fw-bold">
                        View Photo
                    </a>
                @else
                    <div class="p-2 border rounded text-center text-muted small bg-light">No Photo</div>
                @endif
            </div>
        </div>

        {{-- Detail Section (Only shows if Announcement exists) --}}
        @if ($announcement)
            <div id="announcement-details" class="p-4 bg-white border rounded shadow-sm mb-5">
                <h4 class="fw-bold mb-3">Event Details</h4>
                <div class="mb-4">
                    <strong>Summary:</strong>
                    <div class="mt-2 text-secondary">{!! $announcement->summary !!}</div>
                </div>
                <div class="row pt-3 border-top">
                    <div class="col-md-6"><strong>When:</strong> {{ $announcement->when }}</div>
                    <div class="col-md-6"><strong>Where:</strong> {{ $venue_title }}</div>
                </div>
            </div>
        @endif

        {{-- Footer --}}
        {{-- Footer Navigation converted to a proper button --}}
        <div class="mt-5 pt-4 border-top">
            <a href="{{ route('rituals.index', ['year' => $ritual->year]) }}" class="btn btn-secondary shadow-sm px-4">
                <i class="bi bi-arrow-left me-2"></i>More {{ $ritual->year }} Rituals
            </a>
        </div>  </div>
@endsection
