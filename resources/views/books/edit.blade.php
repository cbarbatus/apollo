@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <h1 class="mb-4 display-6 fw-bold">Edit Book: {{ $book->title }}</h1>

        <form id="book-edit-form" method="POST" action="/books/{{ $book->id }}">
            @csrf
            @method('PUT') {{-- Required for "Style B" Update --}}

            <div class="card shadow-sm border-0 rounded-3 bg-light">
                <div class="card-body p-4">
                    <div class="row g-3">

                        {{-- Title --}}
                        <div class="col-md-8">
                            <label class="form-label fw-bold">Book Title</label>
                            <input type="text" name="title" class="form-control shadow-sm" value="{{ old('title', $book->title) }}">
                        </div>

                        {{-- Sequence: Unified to a single, narrow field --}}
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Sequence</label>
                            <input type="number" name="sequence"
                                   class="form-control shadow-sm text-center"
                                   style="max-width: 100px;"
                                   value="{{ old('sequence', $book->sequence ?? 0) }}">
                        </div>

                        {{-- Author & Pix --}}
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Author</label>
                            <input type="text" name="author" class="form-control shadow-sm" value="{{ old('author', $book->author) }}">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold">Image URL (Pix)</label>
                            <input type="text" name="pix" class="form-control shadow-sm" value="{{ old('pix', $book->pix) }}">
                        </div>

                        {{-- Purchase Link --}}
                        <div class="col-12">
                            <label class="form-label fw-bold">Purchase/Info Link</label>
                            <input type="text" name="link" class="form-control shadow-sm" value="{{ old('link', $book->link) }}">
                        </div>

                        {{-- Remarks: Trix Integration --}}
                        <div class="col-12 mb-3">
                            <label for="remarks" class="form-label fw-bold">Remarks</label>
                            <input id="remarks" type="hidden" name="remarks" value="{{ old('remarks', $book->remarks) }}">
                            <trix-editor input="remarks" class="trix-content shadow-sm bg-white" style="min-height: 200px; border-radius: 8px;"></trix-editor>
                        </div>
                    </div>
                </div>
            </div>
        </form>
                    {{-- Footer: Balanced Alignment --}}
                    <div class="mt-4 pt-3 border-top d-flex justify-content-between align-items-center">
                        {{-- Left Side: Primary Actions --}}
                        <div class="d-flex gap-2">
                            {{-- Removed the extra > bracket below --}}
                            <x-apollo-button type="submit" form="book-edit-form">
                                Update Book
                            </x-apollo-button>

                            {{-- Use the component for Cancel so it doesn't "compete" for focus --}}
                            <x-cancel-button href="/books" />
                        </div>


                    </div>
                </div>
@endsection
