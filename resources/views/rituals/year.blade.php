@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Rituals for {{ $year }}</h1>
        <br><br>
        <ul class="list-unstyled">
            @foreach ($rituals as $ritual)
                <li class="mb-2">
                    @if ($admin)
                        <a class="text-decoration-none text-dark ritual-link" href="/rituals/{{ $ritual->id }}"> {{ $ritual->name }} </a>
                    @else
                        <a class="text-decoration-none text-dark ritual-link" href="/rituals/{{ $ritual->id }}/display"> {{ $ritual->name }} </a>
                    @endif
                </li>
            @endforeach
        </ul>        <br><br>
        <a href="/rituals/0/list" class="btn btn-go">More Years</a>
    </div>
    <br>
@endsection
