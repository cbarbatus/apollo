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

        {{-- 2. Dynamic Sections --}}
        @foreach($sections as $section)
            @if ($section->id != 99)
                <div class="card mb-3 shadow-sm">
                    <div class="card-header bg-light">
                        <a href="/sections/{{ $section->id }}/on" class="text-decoration-none text-dark d-block">
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

    </div>
@endsection
