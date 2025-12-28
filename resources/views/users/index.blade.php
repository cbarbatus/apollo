@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- 1. Restore the H1 Title --}}
        <h1 class="display-6 fw-bold mb-4" style="color: #333;">Grove Membership</h1>

        {{-- 2. Create the Message Area for success/warnings --}}
        @if(session('success'))
            <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 8px;">
                {{ session('success') }}
            </div>
        @endif

        {{-- 3. The Professional Button Row (Replacing clown yellow) --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex gap-2">
                <a href="{{ url('/members/create') }}" class="btn btn-primary shadow-sm fw-bold border-0 px-4"
                   style="height: 38px; border-radius: 8px;">
                    + New Member
                </a>
                <a href="{{ url('/members?filter=all') }}" class="btn btn-outline-secondary shadow-sm fw-bold px-4"
                   style="height: 38px; border-radius: 8px;">
                    Show All Records
                </a>
            </div>

            {{-- Total Count Badge (Optional, but helpful for your 187 records) --}}
            <span class="badge bg-light text-dark border shadow-sm px-3 py-2" style="border-radius: 20px;">
            Total Records: 187
        </span>
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
    </div>
@endsection
