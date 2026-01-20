@extends('layouts.app')

@section('content')
    <div class="container py-4">
        {{-- Constrained to 700px and centered --}}
        <div class="card shadow-sm border-0 mx-auto" style="border-radius: 12px; background-color: #f8f9fa; max-width: 700px;">
            <div class="card-body p-4">
                {{-- Refined Heading --}}
                <h1 class="display-6 fw-bold mb-1" style="color: #333;">User Roles</h1>

                {{-- The "Identity Confirmation" --}}
                <p class="text-secondary mb-4">
                    Managing permissions for: <strong>{{ $user->name }}</strong> <span class="mx-1">|</span> {{ $user->email }}
                </p>

                <form action="{{ url('/users/' . $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    {{-- Identity kept in background for the Request, but invisible to UI --}}
                    <input type="hidden" name="name" value="{{ $user->name }}">
                    <input type="hidden" name="email" value="{{ $user->email }}">

                    <div class="bg-white p-4 mb-4" style="border-radius: 12px; border: 1px solid #e9ecef;">
                        <label class="form-label text-secondary fw-bold small text-uppercase mb-3">Assigned Roles</label>

                        <div class="d-flex flex-column gap-3">
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
                    </div>

                    {{-- Navigation Actions --}}
                    <div class="d-flex justify-content-between align-items-center pt-3 border-top">

                        <div class="row">
                            <div class="col-md-12">
                                <x-apollo-button type="submit">Submit</x-apollo-button>
                                <x-cancel-button></x-cancel-button>
                            </div>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
