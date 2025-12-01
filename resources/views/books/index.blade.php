@extends('layouts.app')

@section('content')
    <div class='container py-4'>

        <h1 class="mb-5">Suggested Reading List for those Interested in Druidry</h1>

        @if ($changeok)
            <a href="/books/create" class="btn btn-warning btn-lg mb-5">New Book</a>
        @endif

        @foreach ($books as $book)
            <div class="card mb-4 shadow-sm">
                <div class="card-body d-flex gap-4">

                    <div class="flex-shrink-0">
                        <img src="{{ $book->pix}}" alt="Book Cover" style="max-height:150px; width: auto; object-fit: contain;">
                    </div>

                    <div class="flex-grow-1">
                        <h4 class="card-title mb-1">{{ $book->title }}</h4>
                        <p class="card-subtitle text-muted mb-3">by {{ $book->author }}</p>

                        <div class="mb-3">
                            <a href="{{$book->link}}" target="_blank" class="text-decoration-none">
                                View Link
                            </a>
                        </div>

                        @if ($changeok)
                            <div class="d-flex align-items-center gap-3 mt-4">

                                <a href="/books/{{ $book['id']}}/edit" class="btn btn-sm btn-warning">Edit</a>

                                <form method="post" action="/books/{{ $book['id']}}" id="delete-{{$book->id}}" onsubmit="return confirm('Are you sure you want to delete the book: {{ addslashes($book->title) }}?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach

    </div>
@endsection
