@extends('layouts.app')

@section('content')
    <h1>Votes Admin</h1>

    <br>
    @if ($change_any)
    <form method="get" action="/votes/create" id="create">
    </form>
    <button type="submit" form='create' class="btn btn-warning" >New Vote</button>
    <br><br>
    @endif

    <div class='container' >
        @if ( !($votes->count()) )
        You have no votes
        @else
            <table class="table table-striped">
                <thead>
                <tr style="font-weight:bold">
                    <td>Motion ID</td>
                    <td>Voter ID</td>
                    <td>Vote</td>
                    <td colspan="2">Action</td>
                </tr>
                </thead>
                <tbody>
                @foreach($votes as $vote)
                    <tr>
                        <td>{{$vote->motion_id}}</td>
                        <td>{{$vote->member_id}}</td>
                        <td>{{$vote->vote}}</td>
                        <td>
                                <form method="get" action="/votes/{{ $vote['id']}}/edit" id="edit">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-warning" >Edit</button>
                                </form>
                        </td>
                        <td>
                                <form method="get" action="/votes/{{ $vote['id']}}/sure" id="sure">
                                @csrf
                                @method('GET')
                                <button type="submit" class="btn btn-danger" >Delete</button>
                                </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @endif
    </div>
<br>
@endsection
