@extends('layouts.app')

@section('content')
    @php
        // Establish the same manager logic as the index page [cite: 2026-01-18]
        $isManager = auth()->user()->hasAnyRole(['admin', 'senior_druid']);

        // Check if this is a historical member (User ID ---) or active [cite: 2025-12-31]
        $isMyRecord = ($member->user_id && auth()->id() === $member->user_id);
    @endphp

    <div class="container py-4">
        <h1 class="mb-4">Edit Member: {{ $member->first_name }} {{ $member->last_name }}</h1>

            <form action="{{ route('members.update', $member->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="filter" value="{{ request('filter') }}">

                {{-- SECTION 1: Common Profile Fields (Edit Change Self) --}}
                {{-- This stays outside any @if for both Members and Managers --}}
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>First Name:</strong></label>
                        <input type="text" name="first_name" value="{{ old('first_name', $member->first_name) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Middle Name:</strong></label>
                        <input type="text" name="mid_name" value="{{ old('mid_name', $member->mid_name) }}" class="form-control">
                    </div>
                    <div class="col-md-4 mb-3">
                        <label class="form-label"><strong>Last Name:</strong></label>
                        <input type="text" name="last_name" value="{{ old('last_name', $member->last_name) }}" class="form-control">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Religious Name(s):</strong></label>
                        <input type="text" name="rel_name" value="{{ old('rel_name', $member->rel_name) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Contact Email:</strong></label>
                        <input type="email" name="email" value="{{ old('email', $member->email) }}" class="form-control">
                        <small class="text-muted">Changing this will also update your login email if you are an active member.</small>
                    </div>
                </div>

                {{-- Full Address: Reduced height to match other inputs --}}
                <div class="mb-3">
                    <label class="form-label"><strong>Full Address:</strong></label>
                    <input type="text" name="address" value="{{ old('address', $member->address) }}" class="form-control">
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Primary Phone:</strong></label>
                        <input type="text" name="pri_phone" value="{{ old('pri_phone', $member->pri_phone) }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label"><strong>Alt Phone:</strong></label>
                        <input type="text" name="alt_phone" value="{{ old('alt_phone', $member->alt_phone) }}" class="form-control">
                    </div>
                </div>



                {{-- Manager Only Section [cite: 2026-01-18] --}}
                @if($isManager)
                    <div class="col-md-12 mt-4 p-4 bg-light border rounded shadow-sm">
                        <h3 class="mb-3 text-secondary">Grove Management</h3>
                        {{-- Management Only Group --}}
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label><strong>Status:</strong></label>

                                <label><strong>Status:</strong></label>
                                <select name="status" class="form-control">
                                    {{-- The Null Option --}}
                                    <option value="" {{ is_null(old('status', $member->status)) ? 'selected' : '' }}>-- Select Status --</option>

                                    @foreach(['Current', 'Active', 'Inactive', 'Pending', 'Lapsed', 'Resigned', 'Deceased', 'Expired'] as $status)
                                        <option value="{{ $status }}"
                                            {{ (old('status', $member->status) == $status) ? 'selected' : '' }}>
                                            {{ $status }}
                                        </option>
                                    @endforeach
                                </select>

                            </div>    <div class="col-md-4 mb-3">
                                <label class="form-label"><strong>Category:</strong></label>
                                <select name="category" class="form-control">
                                    {{-- The Null Option --}}
                                    <option value="" {{ is_null(old('category', $member->category)) ? 'selected' : '' }}>-- Select Category --</option>

                                @foreach(['Member', 'Friend', 'Associate', 'Initiate', 'Dedicant', 'Clergy'] as $category)
                                        <option value="{{ $category }}"
                                            {{ (old('status', $member->category) == $category) ? 'selected' : '' }}>
                                            {{ $category }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><strong>ADF # (ID Number):</strong></label>
                                <input type="text" name="adf" value="{{ old('adf', $member->adf) }}" class="form-control">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><strong>Joined Date:</strong></label>
                                <input type="date" name="joined" value="{{ ($member->joined && strtotime($member->joined) > 0) ? \Carbon\Carbon::parse($member->joined)->format('Y-m-d') : '' }}" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><strong>ADF Join Date:</strong></label>
                                <input type="date" name="adf_join" value="{{ ($member->adf_join && strtotime($member->adf_join) > 0) ? \Carbon\Carbon::parse($member->adf_join)->format('Y-m-d') : '' }}" class="form-control">
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label"><strong>ADF Renew Date:</strong></label>
                                <input type="date" name="adf_renew" value="{{ ($member->adf_renew && strtotime($member->adf_renew) > 0) ? \Carbon\Carbon::parse($member->adf_renew)->format('Y-m-d') : '' }}" class="form-control">
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <div class="d-flex justify-content-start gap-2 mt-4">
                <x-apollo-button type="submit">Submit</x-apollo-button>
                <x-cancel-button href="{{ route('members.index', ['filter' => request('filter')]) }}">
                    Cancel
                </x-cancel-button>
            </div>


        </form>
    </div>
@endsection
