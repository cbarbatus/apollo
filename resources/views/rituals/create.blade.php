@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Create a Ritual</h1>
        <br><br>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        <form method="post" action="/rituals" id="create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <br>
            <label for="year">Year:</label>
            <input type="text" name="year" id="year" size="4" required>
            <br>
            <label for="name">Ritual Name:</label>
            <select name="name" id="name">
                @foreach($ritualNames as $ritualName)
                    <option value= {{ $ritualName }}> {{ $ritualName }}</option>
                @endforeach
            </select>
            <br>
            <label for="culture">Culture:</label>
            <select name="culture" id="culture">
                @foreach($cultures as $culture)
                    <option value={{ $culture }}>{{ $culture }}</option>
                @endforeach
            </select>
            <br><br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>

    </div>
    <br>
@endsection
