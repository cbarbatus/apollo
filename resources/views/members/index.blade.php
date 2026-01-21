@extends('layouts.app')

@section('content')
    <div class="container py-4">

        @php
            // 1. Define the manager check at the TOP of the file
            // Using $isManager to match your @if on line 66
            $isManager = auth()->user()->hasAnyRole(['admin', 'senior_druid'])
                         || auth()->user()->canAny(['change all', 'change members']);
        @endphp

            <h1 class="display-6 fw-bold mb-4">Grove Membership</h1>

        {{-- 1. Management Tools --}}
        @if(auth()->user()->canAny(['change all', 'change members', 'change_members']))
            <div class="d-flex gap-2 mb-4">
                @if($full) {{-- This is the 'showAll' variable we passed from the controller --}}
                <a href="{{ url('/members') }}" class="btn btn-outline-secondary">
                    <i class="fa fa-filter"></i> Show Current Only
                </a>
                @else
                    <x-apollo-button
                        :href="route('members.index', ['filter' => 'all'])"
                        class="btn-secondary">
                        Show All Records
                    </x-apollo-button>
                @endif
            </div>

            <div class="card p-3 mb-4 shadow-sm">
                <h5 class="card-title text-info">Restore Member</h5>
                <form method="POST" action="{{ route('members.restore') }}" id="restoreForm" class="row g-3 align-items-center">
                    @csrf
                    @method('PUT')
                    <div class="col-auto">
                        <label for="first_name" class="form-label">First Name:</label>
                        <input type="text" name="first_name" id="first_name" class="form-control" required>
                    </div>
                    <div class="col-auto">
                        <label for="last_name" class="form-label">Last Name:</label>
                        <input type="text" name="last_name" id="last_name" class="form-control" required>
                    </div>
                    <div class="col-auto pt-4">
                        <x-apollo-button type="submit" color="info" >Restore Member</x-apollo-button>
                    </div>

                </form>
            </div>
        @endif

        {{-- 2. The Table --}}
        <div class="card shadow-sm border-0" style="border-radius: 12px; overflow: hidden;">
            <div class="table-responsive shadow-sm rounded border">
                <table class="table table-hover align-middle mb-0" style="font-size: 0.9rem;">
                    <thead class="bg-light border-bottom border-2">
                    <tr>
                        <th class="ps-3 py-2 text-uppercase fw-extrabold text-dark">ID</th>
                        <th class="py-2 text-uppercase fw-extrabold text-dark">Name</th>

                        {{-- Category Column (The 'Joiner/Member' text in your screenshot) --}}
                        <th class="py-2 text-uppercase fw-extrabold text-dark">Category</th>
                        <th class="py-2 text-uppercase fw-extrabold text-dark">Status</th>

                        @if($isManager)
                            <th class="py-2 text-uppercase fw-extrabold text-dark">User</th>
                            <th class="py-2 text-uppercase fw-extrabold text-dark">Joined</th>
                            <th class="py-2 text-uppercase fw-extrabold text-dark">Email</th>
                        @endif

                        <th class="py-2 text-uppercase fw-extrabold text-dark">ADF #</th>
                        @if($isManager)
                            <th class="py-2 text-uppercase fw-extrabold text-dark">ADF Join</th>
                            <th class="py-2 text-uppercase fw-extrabold text-dark">ADF Renew</th>
                        @endif
                        <th class="pe-3 py-2 text-end text-uppercase fw-extrabold text-dark">Action</th>
                    </tr>
                    </thead>

                    <tbody style="line-height: 1.2;"> {{-- Tighter line height for the rows --}}
                    @foreach($members as $member)
                        @php
                            // Ownership check stays inside the loop
                            $isMyRecord = (auth()->user()->can('change own') && $member->user_id === auth()->id());
                            // 2. Status Color check for THIS member (This was missing!)
                            $statusColor = match(strtolower($member->status)) {
                                'current'  => 'bg-success',
                                'friend'   => 'bg-info text-dark',
                                'inactive' => 'bg-secondary',
                                default    => 'bg-danger'
                            };
                        @endphp

                        <tr>
                            <td>{{ $member->id }}</td>
                            <td>{{ $member->first_name }} {{ $member->last_name }}</td>
                            <td>{{ $member->category }}</td>
                            <td><span class="badge {{ $statusColor }}">{{ $member->status }}</span></td>

                            @if($isManager)
                                {{-- [cite: 2025-12-31] Only active members have user records --}}
                                <td>{{ $member->user_id > 0 ? $member->user_id : '---' }}</td>
                                <td class="py-1">
                                    {{ ($member->joined && strtotime($member->joined) > 0)
                                        ? \Carbon\Carbon::parse($member->joined)->format('Y-m-d')
                                        : '---' }}
                                </td>
                                <td>{{ $member->user?->email ?? $member->email }}</td>
                            @endif

                            <td>{{ $member->adf }}</td>

                            @if($isManager)
                            {{-- ADF Join Column --}}
                            <td class="py-1">
                                {{ ($member->adf_join && strtotime($member->adf_join) > 0)
                                    ? \Carbon\Carbon::parse($member->adf_join)->format('Y-m-d')
                                    : '---' }}
                            </td>

                            {{-- ADF Renew Column --}}
                            <td class="py-1">
                                {{ ($member->adf_renew && strtotime($member->adf_renew) > 0)
                                    ? \Carbon\Carbon::parse($member->adf_renew)->format('Y-m-d')
                                    : '---' }}
                            </td>
                            @endif

                            <td class="text-end">
                                @if($isManager || $isMyRecord)
                                    <a href="{{ route('members.edit', $member->id) }}?filter={{ $full ? 'all' : '' }}"
                                       class="btn btn-sm btn-primary">Edit</a>
                                @endif
                            </td>

                        </tr>
                    @endforeach        </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
