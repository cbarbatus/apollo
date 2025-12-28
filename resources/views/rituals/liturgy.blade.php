@extends('layouts.app')

@section('content')

    <h1>Selected Ritual Text</h1>

    <div class='container' style="line-height: 1;">
        <br><br>
        <div class='liturgy' style="background-color: white; padding:20pt; ">
            {!! $content !!}
        </div>
    </div>

@endsection

