@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Create a New Book Entry</h1>

        <form method="post" action="/books" id="create">
            @csrf

            <div class="col-md-4 mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" id="title"  size="40">
            </div>

            <div class="col-md-4 mb-3">
                <label for="author" class="form-label">Author:</label>
                <input type="text" name="author" class="form-control" id="author"  size="40">
            </div>

            <div class="col-md-4 mb-3">
                <label for="link" class="form-label">goodreads Link:</label>
                <input type="text" name="link" class="form-control" id="link" size="80">
            </div>

            <div class="col-md-4 mb-3">
                <label for="pix" class="form-label">Cover picture link:</label>
                <input type="text" name="pix" class="form-control" id="pix" size="120">
            </div>

            <div class="col-md-4 mb-3">
                <label for="remarks" class="form-label">Remarks:</label>
                <input type="text" name="remarks" class="form-control" id="remarks" size="60">
            </div>

            <div class="col-md-4 mb-3">
                <label for="sequence" class="form-label">Sequence:</label>
                <input type="text" name="sequence" class="form-control" id="sequence" size="4">
            </div>

            <button type="submit" form='create' class="btn btn-go">Submit</button>
        </form>

    </div>
    <br>

@endsection
