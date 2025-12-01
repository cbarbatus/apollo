<!doctype html>
<html>
<head>
    <title>How to Upload a File in Laravel</title>
</head>
<body>
<!-- Message -->
@if(Session::has('message'))
    <p >{{ Session::get('message') }}</p>
@endif

<!-- Form -->
<form method='post' action='/uploadFile' enctype='multipart/form-data' >
    {{ csrf_field() }}
    <input type='file' name='file' >
    <label for="visibility">Visibility:</label>
    <select name="visibility" id="visibility">
        <option value="grove">Private</option>
        <option value="public">Public</option>
        <option value="liturgy">Liturgy</option>
        <option value="images">jpg Images</option>
    </select>
    <input type='submit' name='submit' value='Upload File'>
</form>
</body>
</html>
