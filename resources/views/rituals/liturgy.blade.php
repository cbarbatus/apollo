@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Top Navigation: Balanced Right --}}
        <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
            <h1 class="h2 fw-bold text-dark mb-0">{{ $ritual->year }} {{ $ritual->name }} Ritual</h1>
            <x-apollo-button href="{{ route('rituals.display', $ritual->id) }}">
                Back to Overview
            </x-apollo-button>
        </div>

        {{-- The Ritual Text Container --}}
        <div class="card shadow-sm mb-5">
            <div class="card-body p-4 p-md-5" style="line-height: 1.5; background-color: white;">
                <div class="liturgy-text">
                    {!! $content !!}
                </div>
            </div>
        </div>

        {{-- Bottom Navigation: Aligned Right for Balance --}}
        <div class="mt-5 pt-4 border-top">
            <div class="d-flex justify-content-between align-items-center">
                <p class="text-muted small mb-0 italic">End of {{ $ritual->name }} Ritual Text</p>
                <x-apollo-button href="{{ route('rituals.display', $ritual->id) }}">
                    Back to {{ $ritual->name }} Overview
                </x-apollo-button>
            </div>
        </div>
    </div>
@endsection
