@extends('layouts.app')

@section('content')

    <h1>Closed motions</h1>

    <div class='container'>

    <div class='container' style="background: white;">
        <h4>Click or touch motion number to show details </h4>
        @include('votes/partials/_list')
    </div>

    </div>

    <br>


@endsection
