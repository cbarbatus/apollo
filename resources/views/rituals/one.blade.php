@extends('layouts.app')

@section('content')

    <div class='container'>

        <h1>{{ $ritual->year }} {{ $ritual->name }} Ritual </h1>
        <br>
        <form method="get" action="/rituals/{{ $ritual['id']}}/text" id="text">
        </form>
        <form method="get" action="/rituals/{{ $ritual['id'] }}/view" id="view">
        </form>

        @if ($ritual->liturgy_base !== '') <button type="submit" form='text' class="btn btn-go">Text</button>
        @else No liturgy text
        @endif

        @if (!is_null($slideshow))
            <button type="submit" form='view' class="btn btn-go">Photos</button>
        @else
            No slideshow
        @endif


        <style>
            img:hover {
                box-shadow: 0 0 2px 1px rgba(0, 140, 186, 0.5);
            }
        </style>
        @if ($announcement === null)
            <h3> No announcement for this ritual </h3>
        @else
            <h3> Announcement Information: </h3>
        <ul>
            <li><b>Title:</b> {{ $announcement->title }}</li>
            @if ($announcement->picture_file == '')
                <li><b>Picture:</b> none</li>
            @else
            <li>Click on the image to open it full size in a new window.<br>
                <a target="_blank" href="/img/{{ $announcement->picture_file }}">
                <img src="/img/{{ $announcement->picture_file }}" alt="Ritual" style="max-width:400px">
                </a>
            </li>
            @endif
            <li><b>Summary:</b> {{ $announcement->summary }}</li>
            <li><b>When:</b> {{ $announcement->when }}</li>
            <li><b>Where:</b> {{ $announcement->venue_name }}</li>
        </ul>

        @endif
    </div>
@endsection
