@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Upload Liturgy File</h1>

        <br><br>

        <!-- Message -->
@if(Session::has('message'))
    <p >{{ Session::get('message') }}</p>
@endif

       <!-- Form for getting file name -->
        <form method='post' action='/grove/litfile' enctype='multipart/form-data' >
            {{ csrf_field() }}
            <input type='hidden' name='litfile' value={{ $litname }}>
            <input type='hidden' name='ritid' value={{ $id }}>

            Two liturgy files should be uploaded - .htm and .docx<br>
                The .docx file is the Word file for editing and is private.<br>
                The .htm file is for public display.<br><br>
            Each file must be less than 2mb in size.<br>
            The filename will be changed to a standard form with year and ritual name.<br><br>


            <label for="file">Liturgy File:</label>
            <input type='file' name='file' >


            <input type='submit' name='submit' value='Upload File'>
        </form>
        <br><br>

</div>
<br>
@endsection
