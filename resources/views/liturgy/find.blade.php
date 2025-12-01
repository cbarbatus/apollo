@extends('layouts.app')

@section('content')
    <div class='container'>

        <h1>Full Rituals</h1>

        Select a ritual name or a culture or both to list rituals.  You can then either look at a ritual or download the .docx file.

        <form method="post" action="/liturgy/list" id="create">
            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
            <br>
            <label for="name">Name:</label>
            <select name="name" id="name">
                <option value="0" selected></option>

                @foreach($rituals as $item)
                    <option value="{{ $item }}" >
                        {{ $item }}
                </option>
                @endforeach

            </select>

            <br>

            <label for="culture">Culture:</label>
            <select name="culture" id="culture">
                <option value="0" selected></option>
                @foreach($cultures as $item)
                    <option value="{{ $item }}" >
                        {{ $item }}
                    </option>
                @endforeach
            </select>
            <br>
        </form>
        <button type="submit" form='create' class="btn btn-go">Submit</button>
    </div>
    <br>

@endsection
