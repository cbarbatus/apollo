@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1>Create Text Section </h1>

        <form method="post" action="/sections/store" id="create">
            @csrf

            <div class="col-md-4 mb-3">
                <label for="title">Title:</label>
                <input type="text" name="title" id="title" size="40" value="">
            </div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-control" maxlength="20" required>
                </div>

                <div class="col-md-1 mb-3">
                    <label for="sequence" class="form-label">Sequence:</label>
                    <input type="number" name="sequence" id="seqence" class="form-control" maxlength="4" value=''>
                </div>
            </div>
            <button type="submit" form='create' class="btn btn-go">Submit</button>
        </form>
    </div>
@endsection
