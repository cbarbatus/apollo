@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Add a Member</h1>

        <form method="post" action="/members" id="create">
            {{-- Use the Blade directive, which is cleaner than the raw input --}}
            @csrf

            {{-- Hidden fields are better placed near the top, or even handled in the controller --}}
            <input type="hidden" name="status" value="Current">
            <input type="hidden" name="category" value="Member">

            {{-- 1. NAME FIELDS (Horizontal Row) --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="first_name" class="form-label">First Name:</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="mid_name" class="form-label">Middle Name:</label>
                    <input type="text" name="mid_name" id="mid_name" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="last_name" class="form-label">Last Name:</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="rel_name" class="form-label">Religious Name:</label>
                    <input type="text" name="rel_name" id="rel_name" class="form-control">
                </div>
            </div>

            {{-- 2. CONTACT FIELDS (Horizontal Row) --}}
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="pri_phone" class="form-label">Primary Phone:</label>
                    {{-- Use the pattern attribute for phone validation hint --}}
                    <input type="tel" name="pri_phone" id="pri_phone" class="form-control" placeholder="(000) 000-0000" pattern="[0-9]{3} [0-9]{3}-[0-9]{4}">
                </div>
                <div class="col-md-4">
                    <label for="alt_phone" class="form-label">Alternate Phone:</label>
                    <input type="tel" name="alt_phone" id="alt_phone" class="form-control" placeholder="(000) 000-0000" pattern="[0-9]{3} [0-9]{3}-[0-9]{4}">
                </div>
            </div>

            {{-- 3. ADDRESS FIELD (Single Full Row) --}}
            <div class="row mb-3">
                <div class="col-md-12">
                    <label for="address" class="form-label">Address (street;city;state;zip):</label>
                    <input type="text" name="address" id="address" class="form-control" placeholder="123 Main St; Anytown; CA; 90210" required>
                </div>
            </div>

            <hr class="my-4">

            {{-- 4. ADF AND JOIN DATES (Horizontal Row) --}}
            <div class="row mb-3">
                <div class="col-md-3">
                    <label for="joined" class="form-label">Joined Grove:</label>
                    <input type="date" name="joined" id="joined" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="adf" class="form-label">ADF Number:</label>
                    <input type="number" name="adf" id="adf" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="adf_join" class="form-label">ADF Join Date:</label>
                    <input type="date" name="adf_join" id="adf_join" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="adf_renew" class="form-label">ADF Renew Date:</label>
                    <input type="date" name="adf_renew" id="adf_renew" class="form-control" required>
                </div>
            </div>

            {{-- Submit button is now wrapped in a row/col for proper spacing --}}
            <div class="row mt-4">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </div>
        </form>
    </div>
@endsection
