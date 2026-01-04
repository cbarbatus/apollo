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

                                {{-- Update $newmember to $member --}}
                                <td class="text-nowrap">
                                    <div class="d-flex align-items-center gap-2">

                                        {{-- 1. Accept Button --}}
                                        {{-- 1. Standardized Small Accept Button --}}
                                        <div class="d-flex align-items-center gap-2">
                                            {{-- 1. Accept Button --}}
                                            <form action="{{ route('members.accept', $member->id) }}" method="POST" class="m-0 d-flex align-items-center">
                                                @csrf
                                                <x-apollo-button
                                                    type="submit"
                                                    color="success"
                                                    size="sm"
                                                    class="py-0 px-3"
                                                    style="height: 31px; line-height: 1;"
                                                >
                                                    Accept
                                                </x-apollo-button>
                                            </form>

                                            {{-- 2. Scrub Trigger --}}
                                            <x-apollo-button
                                                type="button"
                                                color="danger"
                                                size="sm"
                                                class="py-0 px-3"
                                                style="height: 31px; line-height: 1;"
                                                data-bs-toggle="modal"
                                                data-bs-target="#scrubModal{{ $member->id }}"
                                            >
                                                Scrub
                                            </x-apollo-button>
                                        </div>
                                        {{-- 3. The Custom Popup (Reusable Component) --}}
                                        <x-confirmation-modal
                                            id="scrubModal{{ $member->id }}"
                                            :action="route('members.deletejoin', $member->id)"
                                            title="Scrub Applicant?"
                                            buttonText="Yes, Scrub"
                                        >
                                            Are you sure you want to permanently remove <strong>{{ $member->first_name }}</strong> from the Grove records?
                                        </x-confirmation-modal>

                                    </div>
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
