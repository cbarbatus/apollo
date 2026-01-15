@extends('layouts.app')

@section('content')
    <div class="container py-4">


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
                        {{-- Added fw-extrabold and tracked-wider style --}}
                        <th class="ps-3 py-2 text-uppercase fw-extrabold text-dark"
                            style="font-size: 0.85rem; letter-spacing: 0.05em; border-bottom: 2px solid #dee2e6;">
                            Name
                        </th>
                        <th class="py-2 text-uppercase fw-extrabold text-dark" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                            Status
                        </th>
                        <th class="py-2 text-uppercase fw-extrabold text-dark" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                            Role
                        </th>
                        <th class="py-2 text-uppercase fw-extrabold text-dark" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                            Email
                        </th>
                        <th class="py-2 text-uppercase fw-extrabold text-dark" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                            ADF #
                        </th>
                        <th class="pe-3 py-2 text-end text-uppercase fw-extrabold text-dark" style="font-size: 0.85rem; letter-spacing: 0.05em;">
                            Action
                        </th>
                    </tr>
                    </thead>
                    <tbody style="line-height: 1.2;"> {{-- Tighter line height for the rows --}}
                    @foreach($members as $member)
                        @php
                            // 1. Define the manager check for THIS specific loop iteration
                            $isManager = auth()->user()->canAny(['change all', 'change members']);

                            // 2. Define ownership: Only active members have user records [cite: 2025-12-31]
                            // Compare the record's user_id to the logged-in user
                            $isMyRecord = (auth()->user()->can('change own') && $member->user_id === auth()->id());
                        @endphp
                        <tr>
                            <td class="ps-3 py-1 fw-bold text-dark" style="font-size: 1rem;">
                                {{ $member->first_name }} {{ $member->last_name }}
                            </td>
                            <td class="py-1">
                                @php
                                    // Map your statuses to Bootstrap colors
                                    $statusColor = match(strtolower($member->status)) {
                                        'current' => 'bg-success',
                                        'friend'  => 'bg-info text-dark',
                                        'inactive' => 'bg-secondary',
                                        // Resigned, Expired, etc., all get the 'danger' or a neutral red
                                        default    => 'bg-danger',
                                    };
                                @endphp

                                <span class="badge rounded-pill {{ $statusColor }} shadow-sm"
                                      style="font-size: 0.8rem; padding: 0.4em 0.8em; min-width: 80px;">
                                    {{ ucfirst($member->status) }}
                                </span>
                            </td>
                            <td class="py-1">{{ $member->category }}</td>
                            <td class="py-1 text-small">{{ $member->email }}</td>
                            <td class="py-1">{{ $member->adf }}</td>
                            <td class="pe-3 py-1 text-end">
                                {{-- Optimized logic using the variables you defined above --}}
                                @if($isManager || $isMyRecord)
                                    <a href="{{ url("/members/{$member->id}/edit") }}"
                                       class="btn btn-primary btn-sm px-3 fw-bold shadow-sm"
                                       style="border-radius: 6px; font-size: 0.7rem; padding-top: 1px; padding-bottom: 1px;">
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
    </div>
@endsection
