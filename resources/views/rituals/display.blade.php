@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>{{ $ritual->year }} {{ $ritual->name }} Ritual </h1>
        <br>
        <form method="get" action="/rituals/{{ $ritual['id'] }}/text" id="text">
        </form>

        @if ( ($slideshow ?? '') != '')
        <form method="get" action="/slideshows/{{ $slideshow['id'] }}/view" id="view">
        </form>
        @endif

        @if ($lit_file != '')
            <button type="submit" form='text' class="btn btn-go">Text</button>
        @else No liturgy text
        @endif

        @if (($slideshow ?? '') != '')
            <button type="submit" form='view' class="btn btn-go">Photos</button>
        @else
            No slideshow
        @endif

        <br><br>
        @if ($announcement === null)
            <h4> No announcement for this ritual </h4>
        @else
            <h4> Announcement Information: </h4><br>
            @if ($announcement->picture_file == '')
                <b>Picture:</b> none<
            @else
            Click on the image to open it full size in a new window.<br>
                <a target="_blank" href="/img/{{ $announcement->picture_file }}">
                <img src="/img/{{ $announcement->picture_file}}" alt="Ritual" style="max-height:120px">
                </a>

            @endif
            <br><br>
            <b>Summary:</b> {!! $announcement->summary !!}
            <br><br>
            <b>When:</b> {{ $announcement->when }}
            <br><br>
            <b>Where:</b> {{ $venue_title }}

        @endif
        <br><br>
        <a href="/rituals/{{$ritual->year}}/0/year" class="btn btn-go">More Rituals</a>
    </div>
    <br>

@endsection
