@extends('layouts.app')

@section('content')

    @if (!is_null($contacts))
        <div class="alert alert-danger" role="alert">
            <p>There are contacts awaiting reply.</p>
        </div>
    @endif

    <div class="container">
        <h1 class="text-center fs-3 text-dark fw-bold custom-shadow">
            Serving the L.A. Pagan community since 1999!
        </h1>
        <p class="text-center fs-5 text-dark custom-shadow">
            Raven's Cry Grove is an inclusive, anti-racist community, welcoming and respecting all abilities, ethnicities, national origins, sexual orientations & gender identities.
        </p>

        <img alt="cover" src="/img/webpage_cover.webp"
             class="img-fluid d-block mx-auto border border-4 border-dark mb-4">

        <p>Click or touch to show a section.</p>

        @foreach($sections as $section)

            @if ( $section->id <> 99)

                <br> <a href="/sections/{!! $section->id !!}/on" class="text-decoration-none">
                    <h5> {{ $section->name  }} </h5>
                </a>

                @if ( $section->showit )
                    <br> <a href="/sections/{!! $section->id !!}/off" class="text-decoration-none"> CLOSE THIS SECTION </a>
                    @foreach( $section->elements as $element )
                        {!!  "<br><br> $element->item" !!}
                    @endforeach

                    <br> <a href="/sections/{!! $section->id !!}/off" class="text-decoration-none"> CLOSE THIS SECTION </a><br><br>

                @endif

            @endif

        @endforeach


        <br><br>
    </div>


    <div class="text-center mb-5">
        <a href="/contact" class="text-decoration-none">
            <span class="text-dark fs-4 fw-bold">Contact Us</span>
        </a>
        <br>
        <br>

        <div class="d-flex justify-content-center my-4">
            <a href="https://www.facebook.com/ravenscrygrove/">
                <img src="/img/facebook_button.gif" alt="Follow Us on Facebook">
            </a>
        </div>
        <br><br>
    </div>

@endsection
