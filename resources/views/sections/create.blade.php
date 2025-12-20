@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Create Text Section </h1>

        <form method="POST" action="{{ url('sections') }}">
            @csrf

            <div class="col-md-4 mb-3">
                <label for="title" class="form-label">Section Title:</label>
                <input type="text" name="title" id="title" class="form-control" size="40" value="">
            </div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="name" class="form-label">Internal Name:</label>
                    <input type="text" name="name" id="name" class="form-control" maxlength="20" required>
                </div>

                <div class="col-md-1 mb-3">
                    <label for="sequence" class="form-label">Sequence:</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" maxlength="4" value=''>
                </div>
            </div>
            <button type="submit" class="btn btn-go">Submit</button>
        </form>
    </div>
@endsection
