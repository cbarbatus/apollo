@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>{{ $ritual->year }} {{ $ritual->name }} Ritual </h1>
        <br>
        <form method="get" action="/rituals/{{ $ritual['id'] }}/text" id="text">
        </form>

        <div class="my-4">
            @if ($slideshow)
                {{-- Assuming a route exists to view the slideshow by ID --}}
                <a href="{{ url('/slideshows/' . $slideshow->id) }}" class="btn btn-info">
                    View Associated Slideshow
                </a>
            @else
                <span class="text-muted fst-italic">No slideshow associated with this ritual.</span>
            @endif
        </div>

        <div class="my-4">
            @if ($lit_file != '')
            <button type="submit" form='text' class="btn btn-info">View Liturgy Text</button>
        @else <span class="text-muted fst-italic">No liturgy text associated with this ritual.</span>
        @endif
        </div>

        <div class="my-5">
            @if (!$announcement )
                <span class="text-muted fst-italic">No announcement associated with this ritual.</span>
            @else
                <div class="my-5 fs-2 fw-bold">
                    Announcement Information:
                </div>

            @if ($announcement->picture_file == '')
                    <span class="text-muted fst-italic">No announcement picture associated with this ritual.</span>
            @else
                Click on the image to open it full size in a new window.<br>
                    <a target="_blank" href="/img/{{ $announcement->picture_file }}">
                    <img src="/img/{{ $announcement->picture_file}}" alt="Ritual" style="max-height:120px">
                    </a>
            @endif
        </div>

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
