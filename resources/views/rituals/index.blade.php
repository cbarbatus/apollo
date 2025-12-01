@extends('layouts.app')

@section('content')

    <div class='container'>

        <h1>Raven's Cry Grove, ADF Rituals</h1>

        <br><br>
        @if ($admin)
        <form method="get" action="/rituals/create" id="create">
        </form>
        <button type="submit" form='create' class="btn btn-warning">New Ritual</button>
        <br><br>
        @else
            <?php $admin = '0'; ?>
        @endif

        <h3>Choose one ritual</h3>
        <form method="get" action="/rituals/one" id="oneritual">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
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
                <?php $names=['Samhain', 'Yule', 'Imbolc', 'Spring', 'Beltaine', 'Summer', 'Lughnasadh', 'Fall', 'PaganPride'];
                foreach($names as $item){
                ?>
                <option value="{{ $item }}" >
                    <?php echo $item; ?>
                </option>
                <?php
                }
                ?>
            </select>
            <button type="submit" form='oneritual' class="btn btn-go">One Ritual</button>
            <br><br>
        </form>

                <h3>Choose a ritual year</h3>

        @php
            // Get the active year from the 2nd segment of the URL path
            $activeYear = request()->segment(2);
        @endphp

        <ul class="list-unstyled d-flex flex-wrap gap-3">
            @foreach ($activeYears as $year)
                @php
                    // Check if active: if so, use 'btn-dark' with 'active' class
                    $linkClasses = ($year == $activeYear) ? 'btn-dark active' : 'btn-outline-secondary';
                @endphp

                <li class="nav-item">
                    <a class="btn btn-sm {{ $linkClasses }}"
                       href="/rituals/{{ $year }}/{{ $admin }}/year">
                        {{ $year }}
                    </a>
                </li>
            @endforeach
        </ul>
    </div>
@endsection
