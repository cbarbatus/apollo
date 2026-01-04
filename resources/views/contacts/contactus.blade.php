@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1 class="mb-4">Contact Us</h1>

        <form name="frmContact" id="frmContact" method="post" action="/contacts/submit">
            @csrf
            <input type="hidden" name="form_load_time" value="{{ time() }}">

            <div style="display:none !important; visibility:hidden;" aria-hidden="true">
                <label for="middle_name">Do not fill this out</label>
                <input type="text" name="middle_name" id="middle_name" tabindex="-1" autocomplete="off">
            </div>

            <div class="row mb-3">
                <label for="name" class="col-sm-2 col-form-label">Name</label>
                <div class="col-sm-10">
                    {{-- NESTED COL: Limit the input width to 50% of the available space --}}
                    <div class="col-md-6 p-0">
                        <input type="text" name="name" id="name" class="form-control" required>
                    </div>
                </div>
            </div>

            <div class="row mb-3">
                <label for="email" class="col-sm-2 col-form-label">Email</label>
                <div class="col-sm-10">
                    {{-- NESTED COL: Limit the input width to 50% of the available space --}}
                    <div class="col-md-6 p-0">
                        <input type="email" name="email" id="email" class="form-control" required>
                    </div>
                </div>
            </div>


            <div class="row mb-3">
                <label for="message" class="col-sm-2 col-form-label">Message</label>
                <div class="col-sm-10">
                    {{-- Textarea remains full-width (100% of the col-sm-10) --}}
                    <textarea name="message" id="content" rows="6" class="form-control" required></textarea>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-sm-10 offset-sm-2">
                    <x-apollo-button type="submit">Submit</x-apollo-button>
                </div>
            </div>
        </form>
        {{-- The old PHP and JavaScript blocks should remain removed --}}

    </div>
    <br>
@endsection
