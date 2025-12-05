@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create a Text Element</h1>
        <br><br>
        <form method="post" action="/elements" id="create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <br>
            <input hidden type="integer" name="section_id" id="section_id" value="{{ $section_id }}">
            <label for="title">Title:</label>
            <input type="text" name="title" id="title" size="40">
            <br>
            <label for="name">Name:</label>
            <input type="text" name="name" id="namee" size="20">
            <br>
            <label for="sequence">Sequence:</label>
            <input type="number" name="sequence" id="sequence" size="4">
            <br>
            <label for="item">Text:</label>
            <textarea id="item" name="item" rows="4" cols="60" value=""></textarea>
       </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>

    </div>
    <br>
@endsection
