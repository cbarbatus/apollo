@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="mb-5">
            <h1 class="display-4 fw-bold">Ritual Management</h1>
            <p class="h3 text-muted">{{ $ritual->name }} | {{ $ritual->year }} — {{ $ritual->culture }}</p>
        </div>

        <div class="card shadow-sm border-0" style="background-color: #e2f3f5; border-radius: 12px;">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center">

                    <div class="d-flex gap-2 align-items-center">
                        <a href="/rituals/{{ $ritual->id }}/display" class="btn btn-info text-white px-4 fw-bold border-0 shadow-sm">
                            Public View
                        </a>

                        @auth
                            <a href="/rituals/{{ $ritual->id }}/uploadlit" class="btn btn-primary px-4 fw-bold border-0 shadow-sm">
                                Upload
                            </a>

                            <a href="{{ route('rituals.edit', $ritual->id) }}" class="btn btn-warning px-4 fw-bold border-0 shadow-sm">
                                Edit Details
                            </a>

                            <x-delete-button
                                :action="route('rituals.destroy', $ritual->id)"
                                resource="Ritual"
                            />
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
