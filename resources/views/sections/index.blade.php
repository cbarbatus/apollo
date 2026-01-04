@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="display-6 fw-bold mb-0">Front Page Sections</h1>
            <a href="{{ url('/sections/create') }}" class="btn btn-primary shadow-sm px-4 fw-bold border-0" style="height: 38px; border-radius: 8px; display: flex; align-items: center;">
                + New Section
            </a>
        </div>

        <x-alert-success />

        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                    <tr class="text-secondary fw-bold" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">
                        <th class="ps-4" style="width: 80px;">ID</th>
                        <th>Internal Name</th>
                        <th>Title</th>
                        <th class="text-center" style="width: 120px;">Sequence</th>
                        {{-- Fixed width for the actions column to remove the white gap --}}
                        <th class="text-end pe-4" style="width: 200px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($sections as $section)
                            <tr>
                                <td class="ps-4 text-muted">{{ $section->id }}</td>
                                <td class="fw-bold">{{ $section->name }}</td>
                                <td>{{ $section->title }}</td>
                                <td class="text-center">{{ $section->sequence }}</td>
                                <td class="text-end pe-4">
                                    <div class="d-flex gap-2">
                                        {{-- Main Edit Button --}}
                                        <x-apollo-button
                                            href="/sections/{{ $section->id }}/edit"
                                            color="warning"
                                            size="sm"
                                            class="fw-bold border-0 px-3"
                                        >
                                            Edit
                                        </x-apollo-button>

                                        {{-- Jump to Elements Button --}}
                                        <x-apollo-button
                                            href="/sections/{{ $section->id }}/edit#section-elements"
                                            size="sm"
                                            class="px-3"
                                            style="background-color: #6c757d; border: none; color: #ffffff !important;"
                                        >
                                            Elements
                                        </x-apollo-button>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
