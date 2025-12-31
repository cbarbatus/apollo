@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- 1. Restore the H1 Title --}}
        <h1 class="display-6 fw-bold mb-4">System Access Control</h1>

        {{-- 2. Create the Message Area for success/warnings --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 8px;">
                {{ session('success') }}
            </div>
        @endif

    </div>

        <x-alert-success />

        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="bg-light">
                    <tr class="text-secondary fw-bold" style="font-size: 0.85rem; text-transform: uppercase; letter-spacing: 1px;">
                        <th class="ps-4" style="width: 80px;">ID</th>
                        <th>Full Name</th>
                        <th>Email Address</th>
                        <th class="text-end pe-4" style="width: 150px;">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td class="ps-4 text-muted">{{ $user->id }}</td>
                            <td class="fw-bold">{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>
                                {{-- Pulling names from the roles relationship --}}
                                @foreach($user->roles as $role)
                                    <span class="badge bg-success shadow-sm" style="border-radius: 6px; font-weight: normal; font-size: 0.75rem;">
            {{ $role->name }}
        </span>
                                @endforeach
                            </td><td class="text-end pe-4">
                                {{-- Apollo Blue Edit: 32px to match Section/Element style --}}
                                <a href="{{ url('/users/' . $user->id . '/edit') }}"
                                   class="btn btn-primary btn-sm shadow-sm fw-bold border-0 px-3 d-inline-flex align-items-center"
                                   style="height: 32px; border-radius: 8px;">
                                    Edit
                                </a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>

@endsection
