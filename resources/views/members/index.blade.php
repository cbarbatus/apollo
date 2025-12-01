@extends('layouts.app')

@section('content')
    <h1 class="mb-4">Members</h1>

    @canany(['change all','change members'])
        <div class="d-flex flex-wrap gap-2 mb-4">
            <a href="/members/create" class="btn btn-warning">New Member</a>

            <a href="/members/full" class="btn btn-warning">Show All</a>
        </div>

        <div class="card p-3 mb-4">
            <h5 class="card-title">Restore Member</h5>
            <form method="post" action="/members/restore" id="restore">
                @csrf <button type="submit" form='restore' class="btn btn-info fw-bold">Restore Member</button>
                <label for="first_name">First Name:</label>
                <input type="text" name="first_name" id="first_name" required>
                <label for="last_name">Last Name:</label>
                <input type="text" name="last_name" id="last_name" required>
            </form>
        </div>
    @endcan


    @if ( !($members->count()) )
        <p class="alert alert-info">You have no members.</p>
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
                        <th>User</th>
                    @endcan
                    <th>Joined</th>
                    <th>Email</th>
                    @canany(['change all','change members'])
                        <th>ADF #</th>
                        <th>ADF Join</th>
                        <th>ADF Renew</th>
                    @endcan
                    <th>Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($members as $member)
                    {{-- Assuming $user is passed to the view --}}
                    @if (( $member->first_name[0] != '_' ) || (Auth::user()->can('change members')) || ($member->user_id == $user->id) )
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
                                @canany(['change all','change members'])
                                    <td>{{$member->adf}}</td>
                                    {{-- Force Y-m-d format on adf_join --}}
                                    <td>{{ $member->adf_join}}</td>
                                    {{-- Force Y-m-d format on adf_renew --}}
                                    <td>{{ $member->adf_renew}}</td>
                                @endcan
                            <td>
                                {{-- Check if the user has permission to edit --}}
                                @if ( (isset($change_own) && $change_own && ($member->user_id == $user->id)) || (isset($change_members) && $change_members) || (isset($change_all) && $change_all))
                                    {{-- Replaced form with simple anchor tag for navigation --}}
                                    <a href="/members/{{ $member['id'] }}/edit" class="btn btn-sm btn-warning">Edit</a>
                                @endif
                            </td>
                        </tr>
                    @endif
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
@endsection
