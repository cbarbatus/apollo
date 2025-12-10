@extends('layouts.app')

@section('content')

    <div class='container my-5'>

        {{-- Role Heading and Action Button (Add Permission) --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1>Role '{{ $role->name }}'</h1>

            {{-- This button should link to the 'add permission' page/form --}}
            <a href="{{ url('/roles/' . $role->name . '/add') }}" class="btn btn-warning">
                Add Permission
            </a>
        </div>

        <hr class="my-4">

        {{-- Permission List Table --}}
        <h3 class="mb-3">Permissions Assigned:</h3>

        @if ($pnames->isEmpty())
            <div class="alert alert-info">This role has no permissions assigned.</div>
        @else
            <table class="table table-striped table-hover align-middle">
                <thead>
                <tr class="table-primary">
                    <th scope="col" style="width: 80%;">Permission Name</th>
                    <th scope="col" style="width: 20%;">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($pnames as $pname)
                    <tr>
                        <td>{{ $pname->name }}</td>
                        <td>
                            {{--
                            🟢 MODERNIZATION: Use a DELETE method for removal.
                               The form action should route to a destroy/remove endpoint.
                            --}}
                            <form method="POST" action="{{ url('/roles/' . $role->name . '/' . $pname->name . '/remove') }}">
                                @csrf
                                @method('DELETE')

                                {{-- Use a small button for space --}}
                                <button type="submit" class="btn btn-sm btn-danger">
                                    Remove
                                </button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif

        <hr class="my-4">

        {{-- Done Button --}}
        {{--
        🟢 The 'done' button should use the back URL or a known index route.
           We'll use a link to go up one directory (../) as per your original code.
        --}}
        <a href="../" class="btn btn-lg btn-success">Done</a>

    </div>

@endsection
