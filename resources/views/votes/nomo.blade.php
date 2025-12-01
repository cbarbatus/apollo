@extends('layouts.app')

@section('content')

    <h1>No open motions</h1>

    @if ($data['create'] == 'yes')
        <div class='container' style="background: white;">
            <br>
            <form method="post" action="/votes/create" id="create">
            @csrf
            <input hidden type="integer" name="member_id" id="member_id" value={{ $data['member_id'] }}>
            <label for="motion">Motion:</label>
            <textarea id="motion" name="motion" rows="6" cols="60">
            </textarea>
            <br><br>
        </form>
            <button type="submit" form='create' class="btn btn-warning">Create new motion</button>
            <br><br>
        </div>
    @endif

    <div class='container' style="background: white;">
        <form method="get" action="/votes/review" id="review">
        </form>
        <button type="submit" form='review' class="btn btn-go">Review closed motions</button>
        <br><br>
    </div>

<br>

 @endsection
