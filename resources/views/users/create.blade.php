@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm border-0" style="border-radius: 12px; background-color: #f8f9fa;">
            <div class="card-body p-4">
                <div class="alert alert-warning border-0 shadow-sm d-flex align-items-center mb-4" style="border-radius: 8px; background-color: #fff3cd;">
                    <i class="bi bi-exclame-triangle-fill me-3 fs-4 text-warning"></i>
                    <div>
                        <strong class="d-block">Administrative Manual Entry</strong>
                        <span class="small">Use this form only for manual repairs or special cases. For regular applicants, please use the public <strong>Joining Form</strong>.</span>
                    </div>
                </div>

                <h2 class="fw-bold mb-4">Add New Member</h2>

                <form method="POST" action="{{ url('/members') }}" id="create-member">
                    @csrf
                    <div class="row">
                        <div class="col-md-7 border-end">
                            <div class="mb-3">
                                <label for="name" class="form-label text-secondary fw-bold small uppercase">Full Name</label>
                                <input type="text" name="name" id="name" class="form-control" style="border-radius: 8px;" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label text-secondary fw-bold small uppercase">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" style="border-radius: 8px;" required>
                            </div>

                            <p class="text-muted small mt-3">
                                * This will automatically create a System User account and link it to the new Member record.
                            </p>
                        </div>

                        <div class="col-md-5 ps-md-4">
                            <label class="form-label text-secondary fw-bold small uppercase mb-3">Initial Role</label>
                            <select name="role" class="form-select" style="border-radius: 8px;">
                                <option value="member" selected>Member</option>
                                <option value="joiner">Joiner</option>
                                <option value="scribe">Scribe</option>
                            </select>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="{{ url('/users') }}" class="btn btn-outline-secondary shadow-sm fw-bold px-4" style="height: 38px; border-radius: 8px;">
                            Cancel
                        </a>
                        {{-- Apollo Blue Submit: 38px / 8px rounding --}}
                        <button type="submit" class="btn btn-primary shadow-sm px-5 fw-bold border-0" style="height: 38px; border-radius: 8px;">
                            Create Member & User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
