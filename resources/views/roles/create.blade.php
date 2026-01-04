@extends('layouts.app')

@section('content')
    {{-- Master Wrapper to kill the wasteland and enforce Cabin --}}
    <div style="max-width: 600px !important; margin-left: 20px !important; margin-top: 20px !important; font-family: 'Cabin', sans-serif !important;">

        <h1 style="margin-bottom: 20px !important; font-weight: 700;">Create New Role</h1>

        <div class="card shadow-sm" style="border: 1px solid #dee2e6 !important;">
            <div class="card-header" style="background-color: #f8f9fa !important; padding: 15px; border-bottom: 1px solid #dee2e6 !important;">
                <h3 style="margin: 0; font-size: 1.3rem !important; font-weight: 700; color: #333;">Role Details</h3>
            </div>

            <div class="card-body" style="padding: 25px;">
                <form method="POST" action="/roles/store" id="create">
                    @csrf {{-- Modern Laravel CSRF helper --}}

                    <div class="form-group mb-4">
                        <label for="name" style="font-weight: 600; display: block; margin-bottom: 10px;">Role Name:</label>
                        <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Moderator"
                               style="width: 100%; padding: 10px; border-radius: 5px; border: 1px solid #ced4da;" required>
                    </div>

                    <div style="display: flex; gap: 12px; margin-top: 30px;">
                        {{-- Blue Apollo Submit Button --}}
                        <x-apollo-button type="submit" color="primary">Create Role</x-apollo-button>

                        {{-- Gray Cancel Button --}}
                        <a href="{{ url('/roles') }}"
                           style="background-color: #6c757d !important; color: #ffffff !important; padding: 10px 25px !important; border-radius: 6px !important; text-decoration: none !important; font-weight: 600; display: flex; align-items: center;">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
