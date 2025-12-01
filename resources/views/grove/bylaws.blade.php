@extends('layouts.app')

@section('content')
    <h1>Raven's Cry Grove Bylaws</h1>
    <div class='container' style="line-height: 1;">
        <br><br>
        <div class='liturgy' style="background-color: #e0f2ff; padding:20pt; ">
          <p> {{ $theFile }}
            <div id="example1"></div>
            <object style="width: 100%; height: 600px;" data="{{ $theFile }}"></object>
        </div>
    </div>

@endsection

