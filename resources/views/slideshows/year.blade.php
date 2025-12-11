@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Slideshow Events for {{ $year }}</h1>
        <br><br>
        <ul>
            @foreach ($slideshows as $slideshow)
            <li>
                @if ($admin)
                    <a  class="text-decoration-none text-dark ritual-link" href="/slideshows/{{ $slideshow->id }}/edit"> {{ $slideshow->name }} </a>
                @else
                    <a  class="text-decoration-none text-dark ritual-link" href="/slideshows/{{ $slideshow->id }}"> {{ $slideshow->name }} </a>
                @endif
            </li>
            @endforeach
        </ul>
        <br><br>
        <a href="/slideshows/0/list"><button class="btn btn-go">More Years</button></a>
    </div>
    <br>
@endsection
