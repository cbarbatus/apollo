@extends('layouts.app')

@section('content')
    <div class='container my-5'>
        <h1>Create a Ritual</h1>

        <form method="post" action="/rituals" id="create">
            @csrf

            <div class="col-md-4 mb-3">
                <label for="year" class="form-label">Year:</label>
                <input type="text" class="form-label" name="year" id="year" class="form-control" size="4" required>
            </div>

            <div class="row">
                <div class="col-md-2 mb-3">
                    <label for="name" class="form-label">Ritual Name:</label>
                    <select name="name" id="name" class="form-control">
                        @foreach($ritualNames as $ritualName)
                            <option value= {{ $ritualName }}> {{ $ritualName }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 mb-3">
                    <label for="culture" class="form-label">Culture:</label>
                    <select name="culture" class="form-control" id="culture">
                        @foreach($cultures as $culture)
                            <option value={{ $culture }}>{{ $culture }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <button type="submit" form='create' class="btn btn-go">Submit</button>
        </form>
@endsection
