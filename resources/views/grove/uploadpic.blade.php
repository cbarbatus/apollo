@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Upload Announcement Picture</h1>

        <br><br>

        <!-- Message -->
        @if(Session::has('message'))
            <p >{{ Session::get('message') }}</p>
        @endif

        <!-- Form for getting file name -->
        <form method='post' action='/grove/picfile' enctype='multipart/form-data' >
            {{ csrf_field() }}
            <input hidden type='text' name='picfile' value={{ $picname }}>
            Picture file must be type .jpg and less than 2mb in size<br>
            File online will be renamed {{ $picname }}<br><br>
            <label for="file">Picture File:</label>
            <input type='file' name='file' >
            <br><br>
            <input type='submit' name='submit' value='Upload Picture'>
        </form>
        <br><br>

    </div>
    <br>
@endsection
