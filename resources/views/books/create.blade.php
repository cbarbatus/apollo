@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Create a New Book Entry</h1>

        <form method="post" action="/books" id="create">
            @csrf

            <div class="card shadow-sm border-0 rounded-3">
                <div class="card-body p-4">
                    <div class="row g-3">
                        {{-- Title --}}
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Book Title</label>
                            <input type="text" name="title" class="form-control shadow-sm" value="{{ old('title', $book->title ?? '') }}">
                        </div>

                        {{-- Sequence: Narrowed for numeric input --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sequence</label>
                            <input type="number" name="seq" class="form-control shadow-sm text-center" style="max-width: 100px;" value="{{ old('seq', $book->seq ?? 0) }}">
                        </div>

                        {{-- Author --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Author</label>
                            <input type="text" name="author" class="form-control shadow-sm" value="{{ old('author', $book->author ?? '') }}">
                        </div>

                        {{-- Image URL (Pix) --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Image URL (Pix)</label>
                            <input type="text" name="pix" class="form-control shadow-sm" value="{{ old('pix', $book->pix ?? '') }}">
                        </div>

                        {{-- Purchase Link --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Purchase/Info Link</label>
                            <input type="text" name="link" class="form-control shadow-sm" value="{{ old('link', $book->link ?? '') }}">
                        </div>

                        {{-- Remarks: Now using Trix --}}
                        <div class="col-12 mb-3">
                            <label for="remarks" class="form-label fw-bold">Remarks</label>
                            <input id="remarks" type="hidden" name="remarks" value="{{ old('remarks', $book->remarks ?? '') }}">
                            <trix-editor input="remarks" class="trix-content shadow-sm bg-white" style="min-height: 150px; border-radius: 8px;"></trix-editor>
                        </div>
                    </div>

                    {{-- Sequence: Narrowed for numeric input --}}
                    <div class="col-md-4">
                        <label class="form-label fw-bold">Sequence</label>
                        <input type="number" name="sequence"
                               class="form-control shadow-sm text-center"
                               style="max-width: 100px;"
                               value="{{ old('sequence', $book->sequence ?? 0) }}">
                    </div>

                    <div class="mt-4 pt-3 border-top">
                        <button type="submit" class="btn btn-primary shadow-sm px-4 fw-bold" style="border-radius: 8px;">
                            {{ isset($book) ? 'Update Book' : 'Create Book' }}
                        </button>
                        <a href="/books" class="btn btn-outline-secondary ms-2" style="border-radius: 8px;">Cancel</a>
                    </div>
                </div>
            </div>   </form>

    </div>
    <br>

@endsection
