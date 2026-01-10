@extends('layouts.app')

@section('content')
    <div class='container py-4'>

        <h1 class="mb-4">Edit Member: {{ $member->first_name }} {{ $member->last_name }}</h1>

        <form method="post" action="/members/{{ $member->id }}" id="edit">
            @csrf
            @method('put')

            {{-- 1. Basic Identity Section --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $member->first_name) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $member->last_name) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="mid_name" class="form-label">Middle Name</label>
                    <input type="text" name="mid_name" id="mid_name" class="form-control" value="{{ old('mid_name', $member->mid_name) }}">
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="rel_name" class="form-label">Religious Name</label>
                    <input type="text" name="rel_name" id="rel_name" class="form-control" value="{{ old('rel_name', $member->rel_name) }}">
                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $member->email) }}">
                </div>
            </div>

            <hr>

            {{-- 2. Admin / Status Section --}}
            <h2 class="h5 mt-4 mb-3">Membership Details</h2>
            @if ($change_members)
                <div class="row mb-3">
                    <div class="col-md-3">
                        <label for="status" class="form-label">Status</label>
                        <input type="text" name="status" id="status" class="form-control" value="{{ old('status', $member->status) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" name="category" id="category" class="form-control" value="{{ old('category', $member->category) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="joined" class="form-label">Joined</label>
                        <input type="text" name="joined" id="joined" class="form-control" value="{{ old('joined', $member->joined) }}">
                    </div>
                    <div class="col-md-3">
                        <label for="adf_id" class="form-label">ADF ID Number</label>
                        <input type="text" name="adf" id="adf_id" class="form-control" value="{{ old('adf', $member->adf) }}">
                    </div>
                </div>

                {{-- ADF Dates --}}
                <div class="row mb-4">
                    <div class="col-md-4">
                        <label for="adf_join" class="form-label">ADF Join Date</label>
                        {{-- Corrected ID from 'adf join' to 'adf_join' --}}
                        <input type="text" name="adf_join" id="adf_join" class="form-control" value="{{ old('adf_join', $member->adf_join) }}">
                    </div>
                    <div class="col-md-4">
                        <label for="adf_renew" class="form-label">ADF Renew Date</label>
                        {{-- Corrected ID from 'adf renew' to 'adf_renew' --}}
                        <input type="text" name="adf_renew" id="adf_renew" class="form-control" value="{{ old('adf_renew', $member->adf_renew) }}">
                    </div>
                </div>

            @else
                <input type="hidden" name="status" value="{{ $member->status }}">
                <input type="hidden" name="category" value="{{ $member->category }}">
                <input type="hidden" name="joined" value="{{ $member->joined }}">
                <input type="hidden" name="adf" value="{{ $member->adf }}">
                <input type="hidden" name="adf_join" value="{{ $member->adf_join }}">
                <input type="hidden" name="adf_renew" value="{{ $member->adf_renew }}">
            @endif

            <hr>

            {{-- 3. Contact Section --}}
            <h2 class="h5 mt-4 mb-3">Contact Information</h2>
            <div class="row mb-3">
                <div class="col-12">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $member->address) }}">
                </div>
            </div>
            <div class="row mb-4">
                <div class="col-md-6">
                    <label for="pri_phone" class="form-label">Primary Phone</label>
                    <input type="text" name="pri_phone" id="pri_phone" class="form-control" value="{{ old('pri_phone', $member->pri_phone) }}">
                </div>
                <div class="row mb-4">
                    <div class="col-md-6">
                        <label for="alt_phone" class="form-label">Alternate Phone</label>
                        <input type="text" name="alt_phone" id="alt_phone" class="form-control" value="{{ old('alt_phone', $member->alt_phone) }}">
                    </div>
                </div>

                {{-- MOVE THE BUTTON INSIDE THE FORM HERE --}}
                <div class="mt-4">
                    <x-apollo-button type="submit">
                        Save Changes
                    </x-apollo-button>
                    <x-cancel-button></x-cancel-button>
                </div>
            </div>
        </form> {{-- The form now correctly closes AFTER the button --}}

    </div>
    <br>
@endsection
