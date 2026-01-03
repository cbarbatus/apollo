@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; background-color: #f8f9fa;">
            <div class="card-body p-4">

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 8px;">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="alert alert-warning border-0 shadow-sm mb-4" style="border-radius: 8px; background-color: #fff3cd;">
                    <small><strong>Administrative Manual Entry:</strong> Use only for manual repairs. For regular applicants, use the Joining Form.</small>
                </div>

                <h2 class="fw-bold mb-4">Add New Member</h2>

                <form method="POST" action="{{ url('/members') }}">
                    @csrf
                    <div class="row">
                        <div class="col-md-7 border-end pe-md-4">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-bold small uppercase">First Name</label>
                                    <input type="text" name="first_name" class="form-control" value="{{ old('first_name') }}" style="border-radius: 8px;" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label text-secondary fw-bold small uppercase">Last Name</label>
                                    <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" style="border-radius: 8px;" required>
                                </div>
                            </div>

                            <div class="mt-3">
                                <label class="form-label text-secondary fw-bold small uppercase">Email Address</label>
                                <input type="email" name="email" class="form-control" value="{{ old('email') }}" style="border-radius: 8px;" required>
                            </div>

                            <p class="text-muted small mt-3 italic">
                                * System will automatically generate a User record and link it to this Member.
                            </p>
                        </div>

                        <div class="col-md-5 ps-md-4">
                            <label class="form-label text-secondary fw-bold small uppercase mb-3">Initial Role</label>
                            <select name="role" class="form-select shadow-sm" style="border-radius: 8px;">
                                <option value="member" selected>Member</option>
                                <option value="scribe">Scribe</option>
                                <option value="curator">Curator</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between">
                        <a href="{{ url('/users') }}" class="btn btn-outline-secondary px-4 fw-bold" style="height: 38px; border-radius: 8px;">Cancel</a>
                        <x-apollo-button type="submit">Create Member & User</x-apollo-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
