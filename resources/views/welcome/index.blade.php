@extends('layouts.app')

@section('content')

    {{-- 1. Contact Alert --}}
    @if ($contacts)
        <div class="alert alert-danger text-center fw-bold mb-4" role="alert">
            There are contacts awaiting reply.
        </div>
    @endif

    <div class="container py-4">
        <header class="text-center mb-5">
            <h1 class="fs-3 text-dark fw-bold">Serving the L.A. Pagan community since 1999!</h1>
            <p class="fs-5 text-dark">
                Raven's Cry Grove is an inclusive, anti-racist community...
            </p>
        </header>

        <img alt="cover" src="/img/webpage_cover.webp" class="img-fluid d-block mx-auto border-4 border-dark mb-5">
        <p class="text-center fw-bold mb-4">Click or touch to show a section.</p>

        @foreach($sections as $section)
            @if ($section->id != 99)
                {{-- MOVE THE ID TO THE OUTERMOST DIV --}}
                <div id="section-{{ $section->id }}" class="card mb-3 shadow-sm">

                    <div class="card-header bg-light">
                        {{-- If it's already on, maybe make the title just text, or keep it as a toggle? --}}
                        <a href="/sections/{{ $section->id }}/{{ $section->showit ? 'off' : 'on' }}"
                           class="text-decoration-none text-dark d-block">
                            <h5 class="mb-0">{{ $section->name }}</h5>
                        </a>
                    </div>

                    @if ($section->showit)
                        <div class="card-body">
                            <a href="/sections/{{ $section->id }}/off"
                               class="btn btn-sm btn-secondary float-end mb-3 border-0 shadow-sm"
                               style="color: white !important;">
                                CLOSE SECTION
                            </a>

                            @if($section->elements->isEmpty())
                                <p class="text-muted italic">Information for this section is coming soon.</p>
                            @else
                                @foreach($section->elements as $element)
                                    <div class="mt-3">
                                        {!! $element->item !!}
                                    </div>
                                @endforeach
                            @endif
                        </div>

                        <div class="card-footer text-end bg-light">
                            <a href="/sections/{{ $section->id }}/off"
                               class="btn btn-sm btn-secondary border-0"
                               style="color: white !important;">
                                CLOSE SECTION
                            </a>
                        </div>
                    @endif {{-- End showit --}}
                </div>
            @endif {{-- End id != 99 --}}
        @endforeach


        <hr class="my-5">

        {{-- 4. Contact and Social Media Links --}}
        <div class="text-center">
            <a href="/contact" class="text-decoration-none d-block mb-3">
                <span class="text-dark fs-4 fw-bold">Contact Us</span>
            </a>

            <div class="d-inline-flex justify-content-center">
                <a href="https://www.facebook.com/ravenscrygrove/" target="_blank" rel="noopener noreferrer">
                    {{-- Consider using an SVG or a modern icon if possible --}}
                    <img src="/img/facebook_button.gif" alt="Follow Us on Facebook">
                </a>
            </div>
        </div>
    </div>

@endsection
