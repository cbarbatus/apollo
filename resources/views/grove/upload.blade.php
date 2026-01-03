@extends('layouts.app')

@section('content')

    <div class='container my-5'>
        <h1 >Upload Files</h1>


        <p  class="col-md-8 mb-3">Files can be directed to private or public visibility.  There are
            three public directories - /img for images, /liturgy for ritual texts,
            and /contents for general public files.  Images must be .jpg, ritual texts
            must be .htm, and all private files must be .pdf or .docx only.</p>

        <p  class="col-md-4 mb-3">Files must be smaller than 2MB.</p>


        <form method='post' action='/grove/uploadFile' enctype='multipart/form-data' class="mt-4">
            {{ csrf_field() }}

            <div class="d-flex align-items-end gap-3 flex-wrap">

                <div class="flex-grow-1">
                    <label for="file" class="form-label visually-hidden">Select File:</label>
                    <input type='file' name='file' id='file' class="form-control">
                </div>

                <div class="col-auto mb-3">
                    <label for="visibility" class="form-label">Type:</label>
                    <select name="visibility" id="visibility" class="form-select">
                        <option value="grove">Private</option>
                        <option value="public">Public</option>
                        <option value="liturgy">Liturgy</option>
                        <option value="images">jpg Images</option>
                    </select>
                </div>

                <div class="col-auto mb-3">
                    <x-apollo-button type="submit">
                        Upload File
                    </x-apollo-button>
                </div>

            </div>
        </form>

    </div>
    <br>
@endsection
