@extends('layouts.app')

@section('content')
    @php
        $isManager = auth()->user()->hasAnyRole(['admin', 'senior_druid']);
        /** @var \App\Models\Member $member */
        $isMyRecord = ($member->user_id && auth()->id() === $member->user_id);
    @endphp

    <div class="container py-4">
        <h1 class="mb-4">Edit Member: {{ $member->first_name }} {{ $member->last_name }}</h1>

        <form action="{{ route('members.update', $member->id) }}" method="POST">
            @csrf
            @method('PUT')

            <input type="hidden" name="filter" value="{{ request('filter') }}">

            {{-- SECTION 1: Common Profile Fields --}}
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
                </div>
            </div>

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

            @if($isManager)
                <div class="col-md-12 mt-4 p-4 bg-light border rounded shadow-sm">
                    <h3 class="mb-3 text-secondary">Grove Management</h3>
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label"><strong>Status:</strong></label>
                            @php
                                // Define your "Gold Standard" for future entries
                                $statusStandards = ['Current', 'Pending', 'Resigned', 'Deceased', 'Expired'];

                                // Normalize current value for comparison
                                $currentStatus = old('status', $member->status);

                                // Build the collection: start with standards
                                $statusOptions = collect($statusStandards);

                                // Check if the current DB value is a "Ghost" (not in our standard list)
                                // We use strtolower to ensure 'current' doesn't get flagged as historical if 'Current' is standard
                                $isHistorical = $currentStatus && !$statusOptions->contains(function($value) use ($currentStatus) {
                                    return strtolower($value) == strtolower($currentStatus);
                                });

                                if ($isHistorical) {
                                    $statusOptions->prepend($currentStatus);
                                }
                            @endphp

                            <select name="status" class="form-control">
                                <option value="" {{ is_null($currentStatus) ? 'selected' : '' }}>-- Select Status --</option>

                                @foreach($statusOptions as $opt)
                                    @php
                                        $isOptHistorical = !in_array($opt, $statusStandards);
                                    @endphp
                                    <option value="{{ $opt }}"
                                        {{ strtolower($currentStatus) == strtolower($opt) ? 'selected' : '' }}>
                                        {{ $opt }}{{ $isOptHistorical ? ' (Historical)' : '' }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label"><strong>Category:</strong></label>
                            @php
                                // Your specific pruned standards
                                $categoryStandards = ['Member', 'Friend', 'Elder', 'Local', 'Joiner'];

                                $currentCategory = old('category', $member->category);
                                $categoryOptions = collect($categoryStandards);

                                // Detect if this member is currently assigned to a legacy category
                                $isHistoricalCat = $currentCategory && !$categoryOptions->contains(function($value) use ($currentCategory) {
                                    return strtolower($value) == strtolower($currentCategory);
                                });

                                if ($isHistoricalCat) {
                                    $categoryOptions->prepend($currentCategory);
                                }
                            @endphp

                            <select name="category" class="form-control">
                                <option value="" {{ is_null($currentCategory) ? 'selected' : '' }}>-- Select Category --</option>

                                @foreach($categoryOptions as $opt)
                                    @php
                                        $isOptHistorical = !in_array($opt, $categoryStandards);
                                    @endphp
                                    <option value="{{ $opt }}"
                                        {{ strtolower($currentCategory) == strtolower($opt) ? 'selected' : '' }}>
                                        {{ $opt }}{{ $isOptHistorical ? ' (Historical)' : '' }}
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

            <div class="d-flex justify-content-start gap-2 mt-4">
                <x-apollo-button type="submit">Submit</x-apollo-button>
                <x-cancel-button href="{{ route('members.index', ['filter' => request('filter')]) }}">
                    Cancel
                </x-cancel-button>
            </div>
        </form>
    </div>
@endsection
