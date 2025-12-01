@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-3">New (Pending) Members</h1>

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
                                    <form method="post" action="/members/{{ $member['id'] }}/accept" id="accept-{{ $member['id'] }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Accept</button>
                                    </form>

                                    {{-- OPTIONAL: Add a Reject/Delete button using the DELETE method --}}
                                    {{-- <form method="post" action="/members/{{ $member['id'] }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this pending member?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Reject</button>
                                    </form> --}}
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
