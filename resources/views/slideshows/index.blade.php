@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Slideshows Selection</h1>

        <br><br>
        @if ($admin)
        <form method="get" action="/slideshows/create" id="create">
        </form>
        <button type="submit" form='create' class="btn btn-warning">New Slideshow</button>
        @else
            <?php $admin = '0'; ?>
        @endif

        <h3>Choose one slideshow</h3>
        <form method="get" action="/slideshows/{{ $admin }}/one" id="oneshow">
            @csrf
            <input type="hidden" name="admin" value="{{ $admin }}">

            <label for="year">Year:</label>
            <select name="year" id="year">
                <?php foreach ($activeYears as $year) { ?>
                <option value="{{ $year }}" >
                    <?php echo $year; ?>
                </option>
                <?php
                }
                ?>
            </select>

            <select name="name" id="name">
                <?php foreach ($activeNames as $name) { ?>
                <option value="{{ $name }}" >
                    <?php echo $name; ?>
                </option>
                <?php
                }
                ?>
            </select>

            <button type="submit" form='oneshow' class="btn btn-go">Select</button>
            <br><br>
        </form>


        <h3>Choose an event year</h3>

        @php
            // The active year is likely the 2nd segment, based on the rituals structure.
            $activeYear = request()->segment(2);
        @endphp

        <ul class="list-unstyled d-flex flex-wrap gap-3">
            @foreach ($activeYears as $year)
                @php
                    // Apply button classes and the active state logic
                    $linkClasses = ($year == $activeYear) ? 'btn-dark active' : 'btn-outline-secondary';
                @endphp
                <li class="nav-item">
                    <a class="btn btn-sm {{ $linkClasses }}"
                       href="/slideshows/{{ $year }}/{{ $admin }}/year">
                        {{ $year }}
                    </a>
                </li>
            @endforeach
        </ul>


    </div>
    <br>
@endsection
