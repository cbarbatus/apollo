@extends('layouts.app')

@section('content')
    <div class="container bg-white p-4 shadow-sm rounded">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">{{ $slideshow->year }} {{ $slideshow->name }} Photos</h1>

            {{-- Navigation inside the container for better flow --}}
            <x-apollo-button
                href="{{ route('slideshows.index', ['year' => $slideshow->year]) }}"
                color="primary"
                outline="true"
                size="sm"
                class="px-3"
            >
                <i class="bi bi-arrow-left me-1"></i> More Slideshows
            </x-apollo-button>
        </div>

        <div class="ratio ratio-16x9 shadow-sm rounded overflow-hidden">
            <iframe
                src="https://docs.google.com/presentation/d/e/{{ $slideshow->google_id }}/embed?start=true&loop=true&delayms=5000"
                allowfullscreen>
            </iframe>
        </div>

        {{-- Admin-only Edit shortcut since only active members are logged in --}}
        @auth
            <div class="mt-4 pt-3 border-top text-end">
                <x-apollo-button
                    href="{{ route('slideshows.edit', $slideshow->id) }}"
                    color="warning"
                    size="sm"
                >
                    Edit This Slideshow
                </x-apollo-button>
            </div>
        @endauth
    </div>
@endsection

