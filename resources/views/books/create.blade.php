@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create a New Book</h1>
        <br><br>
        <form method="post" action="/books" id="create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <br>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title"  size="40">
            <br>
            <label for="author">Author:</label>
            <input type="text" name="author" id="author"  size="40">
            <br>
            <label for="link">goodreads Link:</label>
            <input type="text" name="link" id="link" size="80">
            <br>
            <label for="pix">Cover picture link:</label>
            <input type="text" name="pix" id="pix" size="120">
            <br>
            <label for="remarks">Remarks:</label>
            <input type="text" name="remarks" id="remarks" size="60">
            <br>
            <label for="sequence">Sequence:</label>
            <input type="text" name="sequence" id="sequence" size="4">
            <br><br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>
    </div>
    <br>

@endsection
