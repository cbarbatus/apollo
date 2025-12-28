@extends('layouts.app')

@section('content')
    <div class="container py-4">


            <h1 class="display-6 fw-bold mb-4">Grove Membership</h1>

        {{-- 1. Management Tools --}}
        @if(auth()->user()->canAny(['change all', 'change members', 'change_members']))
            <div class="d-flex gap-2 mb-4">
                <a href="{{ url('/members/create') }}" class="btn btn-primary shadow-sm fw-bold px-4">+ New Member</a>

                    @if($full) {{-- This is the 'showAll' variable we passed from the controller --}}
                    <a href="{{ url('/members') }}" class="btn btn-outline-secondary">
                        <i class="fa fa-filter"></i> Show Current Only
                    </a>
                    @else
                        <a href="{{ url('/members?filter=all') }}" class="btn btn-secondary">
                            Show All Records
                        </a>
                    @endif

            </div>
        @endif

        {{-- 2. The Table --}}
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light text-secondary small uppercase fw-bold">
                <tr>
                    @if(auth()->user()->canAny(['change all', 'change members', 'change_members']))
                        <th class="ps-3">ID</th>
                        <th>User</th>
                    @endif
                    <th>Name</th>
                    <th>Category</th>
                    <th>Email</th>
                    <th>ADF #</th>
                    @if(auth()->user()->canAny(['change all', 'change members', 'change_members']))
                        <th>ADF Join</th>
                        <th>ADF Renew</th>
                    @endif
                    <th class="text-end pe-3">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($members as $member)
                    <tr>
                        @if(auth()->user()->canAny(['change all', 'change members', 'change_members']))
                            <td class="ps-3 text-muted small">{{ $member->id }}</td>
                            <td>{{ $member->user_id ?? '0' }}</td>
                        @endif

                        <td class="fw-bold">{{ $member->first_name }} {{ $member->last_name }}</td>
                        <td><span class="badge bg-light text-dark border">{{ $member->category }}</span></td>
                        <td class="small">{{ $member->email }}</td>
                        <td class="font-monospace small">{{ $member->adf }}</td>

                        @if(auth()->user()->canAny(['change all', 'change members', 'change_members']))
                            <td>{{ $member->adf_join }}</td>
                            <td>{{ $member->adf_renew }}</td>
                        @endif

                            <td class="text-end pe-3">
                                @php
                                    // 1. Fetch current user
                                    $user = auth()->user();

                                    // 2. Use data_get to bypass IDE property checks
                                    $currentUserId = data_get($user, 'id');

                                    // 3. Match the logic exactly to your working Phoenix code
                                    $canChangeOwn = $user->can('change own');
                                    $isManager = $user->canAny(['change all', 'change members', 'change_members']);

                                    // Match the user_id from the member row to the logged-in user's id
                                    $isMyRecord = ($canChangeOwn && $member->user_id == $currentUserId);
                                @endphp

                                @if($isManager || $isMyRecord)
                                    <a href="{{ url('/members/' . $member->id . '/edit') }}"
                                       class="btn btn-primary btn-sm px-3 shadow-sm fw-bold">
                                        Edit
                                    </a>
                                @endif
                            </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
