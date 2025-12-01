@extends('layouts.app')

@section('content')
    <div class='container py-4'>
        <h1 class="mb-4">Edit Book: {{ $book->title }}</h1>

        <form method="post" action="/books/{{ $book->id }}" id="edit">
            @csrf
            @method('put')

            <div class="row g-3 mb-3">
                <div class="col-md-8">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="title" id="title" class="form-control" value="{{ old('title', $book->title) }}" required>
                </div>
                <div class="col-md-4">
                    <label for="author" class="form-label">Author</label>
                    <input type="text" name="author" id="author" class="form-control" value="{{ old('author', $book->author) }}" required>
                </div>
            </div>

            <div class="row g-3 mb-3">
                <div class="col-md-6">
                    <label for="link" class="form-label">Goodreads Link</label>
                    <input type="url" name="link" id="link" class="form-control" value="{{ old('link', $book->link) }}" placeholder="e.g., https://www.goodreads.com/book/show/12345">
                </div>
                <div class="col-md-6">
                    <label for="pix" class="form-label">Cover Picture Link (URL)</label>
                    <input type="url" name="pix" id="pix" class="form-control" value="{{ old('pix', $book->pix) }}" placeholder="Direct link to image file">
                </div>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-md-10">
                    <label for="remarks" class="form-label">Remarks</label>
                    <input type="text" name="remarks" id="remarks" class="form-control" value="{{ old('remarks', $book->remarks) }}">
                </div>
                <div class="col-md-2">
                    <label for="sequence" class="form-label">Sequence</label>
                    <input type="number" name="sequence" id="sequence" class="form-control" value="{{ old('sequence', $book->sequence) }}">
                </div>
            </div>
        </form>

        <hr class="mb-4">

        <div class="d-flex justify-content-between align-items-center">

            <button type="submit" form='edit' class="btn btn-warning btn-lg">Save Changes</button>

            <a href="/books/{{ $book['id']}}/sure" class="btn btn-danger btn-lg">Delete Book</a>

        </div>
    </div>
    <br>
@endsection
