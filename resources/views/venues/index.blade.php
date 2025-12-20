@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Venues</h1>

        <x-alert-success />

        {{-- 1. Button Placement: Use a consistent row/col structure for spacing and alignment --}}
        <div class="row mb-4">
            <div class="col-md-4">
                {{-- Form action is a GET request, so it doesn't strictly need a <form> wrapper if just a link --}}
                <a href="{{ url('/venues/create') }}" class="btn btn-warning">New Venue</a>
            </div>

            {{-- Optional: Add a search bar or filter here --}}
        </div>

        <div class="table-responsive">
            <table class="table table-striped table-hover">

                <thead>
                <tr class="fw-bold">
                    <td>ID</td>
                    <td>Name</td>
                    <td>Title</td>
                    <td>Address</td>
                    <td>Map URL</td>
                    <td>Directions</td>
                    <td colspan="2">Action</td>
                </tr>
                </thead>
                <tbody>
                @foreach($venues as $venue)
                    <tr>
                        <td>{{ $venue->id }}</td>
                        <td>{{ $venue->name }}</td>
                        <td>{{ $venue->title }}</td>
                        <td>{{ $venue->address }}</td>
                        <td>{{ $venue->map_link }}</td>
                        {{-- Laravel/Blade Best Practice for showing a truncated, safe string --}}
                        <td>{{ Str::limit(strip_tags($venue->directions), 60) }}</td>

                        {{-- EDIT ACTION: Use a link for GET requests, which is cleaner --}}
                        <td>
                            <a href="{{ url("/venues/{$venue->id}/edit") }}" class="btn btn-sm btn-warning">Edit</a>
                        </td>

                        {{-- DELETE ACTION: Use a small form for POST/DELETE method, which is RESTful --}}
                        <td>
                            <x-delete-button
                                :action="url('/venues/' . $venue->id)"
                                resource="venue"
                            />
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
