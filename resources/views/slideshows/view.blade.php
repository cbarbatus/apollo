@extends('layouts.app')

@section('content')

    <div class='container' style="background: white;">
        <h1>{{ $slideshow->year.' '.$slideshow->name }} Photos</h1>

            <div class='embed-container'>
            <iframe src="https://docs.google.com/presentation/d/e/{{ $slideshow->google_id }}/embed?start=true&loop=true&delayms=5000"
                    width=1280 height=720 allow="fullscreen">
            </iframe>
            </div>

        <br><br>
    </div>

    <br><br>
    <a href="{{ route('slideshows.index', ['year' => $slideshow->year]) }}" class="btn btn-primary">
        <i class="bi bi-arrow-left"></i> More Slideshows
    </a>


@endsection

