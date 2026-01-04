@extends('layouts.app')

@section('content')
    <div class='container py-4'>
        <h1 class="mb-4">Join Our Grove</h1>

        <form method="post" action="/members/join" id="create">
            @csrf

            <div class="mb-4 form-check">
                <input type="checkbox" name="discuss" id="discuss" class="form-check-input" required>
                <label class="form-check-label" for="discuss">
                    <span class="fw-bold">I have discussed dues and other expectations of membership with the Senior Druid.</span>
                </label>
            </div>

            <hr class="mb-4">

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" required>
                </div>
                <div class="col-md-4">
                    <label for="rel_name" class="form-label">Religious Name</label>
                    <input type="text" name="rel_name" id="rel_name" class="form-control">
                </div>
            </div>

            <div class="mb-4">
                <label for="address" class="form-label">Address</label>
                <input type="text" name="address" id="address" class="form-control" placeholder="street address; city; state; zip">
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="pri_phone" class="form-label">Primary Phone</label>
                    <input type="tel" name="pri_phone" id="pri_phone" class="form-control" placeholder="aaa nnn-nnnn">
                </div>
                <div class="col-md-6">
                    <label for="alt_phone" class="form-label">Alternate Phone</label>
                    <input type="tel" name="alt_phone" id="alt_phone" class="form-control" placeholder="aaa nnn-nnnn">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label for="adf_id" class="form-label">ADF ID</label>
                    <input type="text" name="adf" id="adf_id" class="form-control">
                </div>
                <div class="col-md-3">
                    <label for="joined" class="form-label">Joined Date</label>
                    <input type="date" name="joined" id="joined" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
                </div>
            </div>

            <input type="hidden" name="status" value="Current">
            <input type="hidden" name="category" value="Joiner">

        </form>

        <div class="d-flex justify-content-end">
            <x-apollo-button type="submit" form='create' color="warning">Submit Application </x-apollo-button>
        </div>

    </div>
    <br>
@endsection
