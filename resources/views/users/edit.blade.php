@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- The Card keeps everything from touching the edges --}}
        <div class="card shadow-sm border-0" style="border-radius: 12px; background-color: #f8f9fa;">
            <div class="card-body p-4">
                <h1 class="display-6 fw-bold mb-4" style="color: #333;">Edit User: {{ $user->name }}</h1>

                <form action="{{ url('/users/' . $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Grid layout for Identity vs Roles --}}
                    <div class="row">
                        <div class="col-md-7 border-end">
                            <div class="mb-3">
                                <label class="form-label text-secondary fw-bold small uppercase">Full Name</label>
                                <input type="text" name="name" class="form-control" value="{{ $user->name }}" style="border-radius: 8px;">
                            </div>

                            <div class="mb-3">
                                <label class="form-label text-secondary fw-bold small uppercase">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ $user->email }}" style="border-radius: 8px;">
                            </div>

                            <div class="mb-4">
                                <label class="form-label text-secondary fw-bold small uppercase">Update Password (Leave blank to keep current)</label>
                                <input type="password" name="password" class="form-control" placeholder="••••••••" style="border-radius: 8px;">
                            </div>
                        </div>

                        <div class="col-md-5 ps-md-4">
                            <label class="form-label text-secondary fw-bold small uppercase mb-3">Assigned Roles</label>
                            <div class="d-flex flex-column gap-2 p-3 bg-light" style="border-radius: 12px; border: 1px solid #e9ecef;">
                                @foreach($roles as $role)
                                    <div class="form-check custom-checkbox">
                                        <input class="form-check-input" type="checkbox" name="roles[]"
                                               value="{{ $role->name }}" id="role_{{ $role->id }}"
                                            {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                                        <label class="form-check-label fw-bold" for="role_{{ $role->id }}" style="color: #495057;">
                                            {{ ucfirst($role->name) }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            <p class="text-muted small mt-2 italic">* Only Admins can modify these roles.</p>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center mt-4 pt-3 border-top">
                        <a href="{{ url('/users') }}" class="btn btn-outline-secondary shadow-sm fw-bold d-flex align-items-center px-4"
                           style="height: 38px; border-radius: 8px;">
                            Cancel
                        </a>

                        {{-- Use your new Gold Standard Component here! --}}
                        <x-apollo-button type="submit" color="primary" class="px-5 shadow-sm fw-bold">
                            Save User Changes
                        </x-apollo-button>
                    </div>
                </form> {{-- <--- MOVED THIS UP: This closes the EDIT form --}}

                <hr class="my-5">

                {{-- DANGER ZONE: This is now its own independent section --}}
                <div class="p-3 bg-light border-start border-danger border-4" style="border-radius: 8px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="text-danger fw-bold mb-1">Danger Zone</h5>
                            <p class="small text-muted mb-0">Removing this user will revoke login access. The Member record will remain intact.</p>
                        </div>

                        {{-- Use your new Gold Standard Delete Component here! --}}
                        <x-delete-button
                            :action="route('users.destroy', $user->id)"
                            resource="user login"
                        />
                    </div>
                </div>
            </div> {{-- Card Body End --}}
        </div> {{-- Card End --}}
    </div>
@endsection
