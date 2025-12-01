@extends('layouts.app')

@section('content')
    <div class='container'>
        <h1>Edit a Ritual</h1>
        <br><br>
        <form method="post" action="/rituals/{{ $ritual->id }}" id="edit">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            @method('put')
            <label for="year">Year:</label>
            {{ $ritual->year }}
            <br>

            <label for="name">Name:</label>
            {{ $ritual->name }}
            <select name="name" id="name">
            @foreach ($ritualNames as $item)
                <option value="{{ $item }}" @selected($ritual->name == $item)>
                    {{ $item }}
                </option>
            @endforeach
            </select>
            <br>

            <label for="culture">Culture:</label>
            {{ $ritual->culture }}
            <select name="culture" id="culture">
            @foreach ($cultures as $item){
                <option value="{{ $item }}" @selected($ritual->name == $item)>
                    {{ $item }}
                </option>
            @endforeach
            </select>

            <br><br>
        </form>
        <button type='submit' form='edit' class="btn btn-go">Submit</button>
        <br>


    </div>
    <br>
@endsection
