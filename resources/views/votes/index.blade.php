@extends('layouts.app')

@section('content')

    <h1>Vote on open motion</h1>

    <div class='container' style="background: white;">
        @if ( !$data['current'] )
            {{ 'There are no open motions.' }}
       @else
            <h4>Motion ID {{ $data['current']->id }} by {{ $data['name'] }} made {{ $data['current']->motion_date }} </h4>

            <blockquote> {{ $data['current']->motion }} </blockquote>

            <form method="post" action="/votes/voted" id="yea">
                @csrf
                @method('put')
                <input hidden type="integer" name="vote" id="vote" value='yea'>
                <input hidden type="integer" name="member_id" id="member_id" value={{ $data['member_id'] }}>
                <input hidden type="integer" name="motion_id" id="motion_id" value={{ $data['motion_id'] }}>
            </form>
            <button type="submit" form='yea' class="btn btn-go">YEA</button> I vote in favor of the motion.
            <br><br>
            <form method="post" action="/votes/voted" id="ok">
                @csrf
                @method('put')
                <input hidden type="integer" name="vote" id="vote" value='ok'>
                <input hidden type="integer" name="member_id" id="member_id" value={{ $data['member_id'] }}>
                <input hidden type="integer" name="motion_id" id="motion_id" value={{ $data['motion_id'] }}>
            </form>
            <button type="submit" form='ok' class="btn btn-go">OK</button> I can live with the motion.
            <br><br>

            <form method="post" action="/votes/voted" id="nay">
                @csrf
                @method('put')
                <input hidden type="integer" name="vote" id="vote" value='nay'>
                <input hidden type="integer" name="member_id" id="member_id" value={{ $data['member_id'] }}>
                <input hidden type="integer" name="motion_id" id="motion_id" value={{ $data['motion_id'] }}>
            </form>
            <button type="submit" form='nay' class="btn btn-warning">NAY</button> I can NOT live with the motion as stated.
            <br><br>

        @if ($data['create']=='yes')
            <form method="post" action="/votes/voted" id="close">
                @csrf
                @method('put')
                <input hidden type="integer" name="vote" id="vote" value='close'>
                <input hidden type="integer" name="member_id" id="member_id" value={{ $data['member_id'] }}>
                <input hidden type="integer" name="motion_id" id="motion_id" value={{ $data['motion_id'] }}>
            </form>
            <button type="submit" form='close' class="btn btn-danger">Close</button> Close voting on the motion.
                <br><br>
            @endif

            <hr>



            <h4>Current tally of votes on motion {{  $data['current']->id }} </h4>

            <table border=2px style="empty-cells: show;">
                <COL>
                <COL>
                <TR>
                    <TH>NAME</TH>
                    <TH>VOTE</TH>
                </TR>
                @foreach ($data['votes'] as $vote)
                            <tr>
                                <td style='width:200px'>{{ $vote['name'] }}
                                </td>
                                <td style='width:40px'>
                                    {{ $vote['vote']->vote }}
                                </td>
                @endforeach
            </table>
    <br>
        @endif

    </div>
<br>
@endsection
