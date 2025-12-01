@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create Text Section </h1>
        <br>
        <form method="post" action="/sections/store" id="create">
            @csrf
            <br>
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" size="40" value="">
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="namee" size="20" value="">
            <br>
            <label for="sequence">Sequence:</label>
            <input type="number" name="sequence" id="seqence" size="4" value="">
            <br><br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>
        <br>

    </div>
@endsection
