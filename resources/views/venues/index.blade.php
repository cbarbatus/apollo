@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1 class="display-6 fw-bold border-bottom pb-3 mb-4">Venues</h1>

        {{-- 1. Standardized New Venue Button --}}
        <div class="row mb-4">
            <div class="col-md-4">
                <x-apollo-button href="{{ url('/venues/create') }}">
                    <i class="bi bi-plus-lg me-2"></i>New Venue
                </x-apollo-button>
            </div>
        </div>

        <div class="table-responsive card shadow-sm border-0">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                <tr class="fw-bold text-secondary small text-uppercase">
                    <th class="ps-3">ID</th>
                    <th>Name</th>
                    <th>Title</th>
                    <th>Address</th>
                    <th>Map Link</th>
                    <th>Directions</th>
                    <th class="text-end pe-3">Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($venues as $venue)
                    <tr class="align-middle">
                        <td class="ps-3 text-muted small">{{ $venue->id }}</td>
                        <td class="fw-bold">{{ $venue->name }}</td>
                        <td>{{ $venue->title }}</td>
                        <td class="small">{{ $venue->address }}</td>

                        {{-- Show start of link for typo-spotting --}}
                        <td class="small text-truncate" style="max-width: 120px;" title="{{ $venue->map_link }}">
                            {{ $venue->map_link }}
                        </td>

                        <td class="text-muted small">
                            {{ Str::limit(strip_tags($venue->directions), 40) }}
                        </td>

                        <td class="text-end pe-3">
                            <div class="d-flex justify-content-end align-items-center gap-2">

                                {{-- Edit Button: Apollo Standard Rounded --}}
                                <a href="{{ route('venues.edit', $venue->id) }}"
                                   class="btn btn-sm btn-warning shadow-sm fw-bold border-0 d-inline-flex align-items-center justify-content-center"
                                   style="height: 30px; min-width: 60px; border-radius: 8px;">
                                    Edit
                                </a>

                                {{-- Delete Component: Rounded Wrapper to prevent clipping --}}
                                <div class="d-inline-flex align-items-center shadow-sm" style="height: 30px; border-radius: 8px; overflow: hidden;">
                                    <x-delete-button
                                        :action="url('/venues/' . $venue->id)"
                                        resource="venue"
                                    />
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
