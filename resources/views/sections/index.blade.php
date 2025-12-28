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
                                <div class="d-flex justify-content-end gap-2">
                                    <a href="{{ url('/sections/' . $section->id . '/edit') }}"
                                       class="btn btn-warning btn-sm shadow-sm fw-bold border-0 px-3"
                                       style="height: 32px; border-radius: 8px; display: flex; align-items: center;">
                                        Edit
                                    </a>
                                    <a href="{{ url('/sections/' . $section->id . '/edit#section-elements') }}"
                                       class="btn btn-outline-secondary btn-sm shadow-sm px-3"
                                       style="height: 32px; border-radius: 8px; display: flex; align-items: center; color: #6c757d;">
                                        Elements
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>                  @foreach($sections as $section)
                        <tr>
                            <td class="ps-4 text-muted">{{ $section->id }}</td>
                            {{-- MISSION: Information only --}}
                            <td class="fw-bold">{{ $section->name }}</td>
                            <td>{{ $section->title }}</td>
                            <td class="text-center">{{ $section->sequence }}</td>

                            <td class="text-end pe-4">
                                <div class="d-flex justify-content-end gap-2">
                                    {{-- MISSION: Edit Section Settings (Top of Page) --}}
                                    <a href="{{ url('/sections/' . $section->id . '/edit') }}"
                                       class="btn btn-warning btn-sm shadow-sm fw-bold border-0 d-flex align-items-center px-3"
                                       style="height: 32px; border-radius: 8px;">
                                        Edit
                                    </a>

                                    {{-- MISSION: Manage Content (Jump to Table) --}}
                                    <a href="{{ url('/sections/' . $section->id . '/edit#section-elements') }}"
                                       class="btn btn-outline-secondary btn-sm shadow-sm d-flex align-items-center px-3"
                                       style="height: 32px; border-radius: 8px; color: #6c757d;">
                                        Elements
                                    </a>
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
