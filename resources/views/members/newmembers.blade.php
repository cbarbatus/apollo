@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="container">
            {{-- Apollo Standard Alerts --}}
            <h1>New (Pending) Members</h1>

        <div class="alert alert-info border-0 shadow-sm mb-4" role="alert">
            <p class="fw-bold mb-1">Reminder:</p>
            <ul>
                <li>Before approval of a new member, verify they have submitted all needed information.</li>
                <li>Also, verify they have paid or arranged dues.</li>
            </ul>
        </div>

        @if ( !($newmembers->count()) )
            <h4 class="mt-5 text-center text-muted">There are no pending members. 🎉</h4>
        @else
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr class="fw-bold">
                        @canany(['change all','change members'])
                            <th>ID</th>
                        @endcan
                        <th>Name</th>
                        <th>Category</th>
                        @canany(['change all','change members'])
                            <th>Status</th>
                            <th>User ID</th>
                        @endcan
                        <th>Joined</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>ADF ID</th>
                        <th colspan="2">Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($newmembers as $member)
                        {{-- The underscore check is kept as it's the system's current logic for pending status --}}
                        @if ( $member->first_name[0] == '_' )
                            <tr>
                                @canany(['change all','change members'])
                                    <td>{{$member->id}}</td>
                                @endcan

                                <td>{{$member->first_name}} {{$member->mid_name}} {{$member->last_name}}</td>
                                <td>{{$member->category}}</td>
                                @canany(['change all','change members'])
                                    <td>{{$member->status}}</td>
                                    <td>{{$member->user_id}}</td>
                                @endcan
                                <td>{{$member->joined}}</td>
                                <td>{{$member->email}}</td>
                                <td>{{$member->pri_phone}}</td>
                                <td>{{$member->adf}}</td>
                                <td>
                                    {{-- Update $newmember to $member --}}
                                    <td class="text-nowrap">
                                        {{-- Container to prevent stacking --}}
                                        <div class="d-flex align-items-center gap-2">

                                            {{-- Accept Form --}}
                                            <td class="text-nowrap">
                                                <div class="d-flex align-items-center gap-2">

                                                    {{-- 1. Standardized Accept Button --}}
                                                    <form action="{{ route('members.accept', $member->id) }}" method="POST" class="m-0">
                                                        @csrf
                                                        <button type="submit" class="btn btn-sm btn-success px-3"
                                                                style="height: 30px; border-radius: 8px;">
                                                            Accept
                                                        </button>
                                                    </form>

                                                    {{-- 2. Standardized Delete Button to match Accept --}}
                                                    {{-- Standardized Delete Button with Apollo Modal Trigger --}}
                                                    <button type="button"
                                                            class="btn btn-sm btn-danger px-3"
                                                            style="height: 30px; border-radius: 8px;"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#deleteConfirm{{ $member->id }}">
                                                        Delete Applicant
                                                    </button>

                                                    {{-- The Hidden Apollo Confirmation Modal --}}
                                                    <div class="modal fade" id="deleteConfirm{{ $member->id }}" tabindex="-1" aria-hidden="true">
                                                        {{-- Added 'modal-md' to give the text room to breathe --}}
                                                        <div class="modal-dialog modal-dialog-centered modal-md">
                                                            <div class="modal-content border-0 shadow-lg" style="border-radius: 12px;">
                                                                <div class="modal-body p-4 text-center">
                                                                    <h5 class="fw-bold mb-3">Scrub Applicant?</h5>
                                                                    <p class="text-muted mx-auto"
                                                                       style="max-width: 350px; white-space: normal; word-wrap: break-word; line-height: 1.5; padding: 0 10px;">
                                                                        Are you sure you want to permanently remove <strong>{{ $member->first_name }}</strong> from the Grove records?
                                                                    </p>
                                                                    <div class="d-flex justify-content-center gap-2 mt-4">
                                                                        <button type="button" class="btn btn-sm btn-secondary px-4" data-bs-dismiss="modal" style="height: 35px; border-radius: 8px;">Cancel</button>

                                                                        <form action="{{ route('members.deletejoin', $member->id) }}" method="POST" class="m-0">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="btn btn-sm btn-danger px-4" style="height: 35px; border-radius: 8px;">Confirm Delete</button>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                        </div>
                                    </td>
                                </td>
                            </tr>
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@endsection
