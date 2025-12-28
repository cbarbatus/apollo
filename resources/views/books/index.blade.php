@extends('layouts.app')

@section('content')
    <div class='container py-4'>
        <h1 class="mb-5 display-6 fw-bold">Suggested Reading</h1>

        <x-alert-success />

        @if ($changeok)
            {{-- 1. New Book Button: Changed to Blue with 8px corners --}}
            <a href="/books/create" class="btn btn-primary shadow-sm px-4 fw-bold border-0 d-inline-flex align-items-center mb-5"
               style="height: 38px; border-radius: 8px;">
                <i class="bi bi-plus-lg me-2"></i>New Book
            </a>
        @endif

        @foreach ($books as $book)
            <div class="card mb-4 shadow-sm border-0"> {{-- Removed card border for cleaner Apollo look --}}
                <div class="card-body d-flex gap-4">

                    <div class="flex-shrink-0">
                        <img src="{{ $book->pix}}" alt="Book Cover" class="rounded shadow-sm" style="max-height:150px; width: auto; object-fit: contain;">
                    </div>

                    <div class="flex-grow-1">
                        <h4 class="card-title mb-1 fw-bold">{{ $book->title }}</h4>
                        <p class="card-subtitle text-muted mb-3 italic">by {{ $book->author }}</p>

                        <div class="mb-3">
                            <a href="{{$book->link}}" target="_blank" class="text-primary text-decoration-none small">
                                <i class="bi bi-link-45deg"></i> View Link
                            </a>
                        </div>

                        @if ($changeok)
                            {{-- 2. Aligned Actions: Forced 32px height and matching 8px radius --}}
                            <div class="d-flex align-items-center gap-2 mt-4">

                                {{-- Edit: border-0 kills the clown ring --}}
                                <a href="/books/{{ $book->id }}/edit"
                                   class="btn btn-sm btn-warning shadow-sm fw-bold border-0 d-flex align-items-center justify-content-center"
                                   style="height: 32px; min-width: 70px; border-radius: 8px; line-height: 1;">
                                    Edit
                                </a>

                                {{-- Delete: Manual style to match Edit height exactly --}}
                                <form method="get" action="/books/{{ $book->id }}/sure" class="m-0">
                                    <button type="submit"
                                            class="btn btn-sm btn-danger shadow-sm fw-bold border-0 d-flex align-items-center justify-content-center"
                                            style="height: 32px; min-width: 70px; border-radius: 8px; line-height: 1;">
                                        Delete
                                    </button>
                                </form>

                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endsection
