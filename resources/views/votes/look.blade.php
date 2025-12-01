@extends('layouts.app')

@section('content')

    <h1>Closed Motion {{ $motion->id }} </h1>
    <div class='container' style="background: white;">
        <h4>Motion ID {{ $motion->id }} by {{ $motion->name }} made {{ $motion->motion_date }} </h4>
        <blockquote> {{ $motion->motion }} </blockquote>
        <hr>

      <h4>Tally of votes on motion {{  $motion->id }} </h4>

        <table border=2px style="empty-cells: show;">
            <COL>
            <COL>
            <TR>
                <TH>NAME</TH>
                <TH>VOTE</TH>
            </TR>

            @foreach ($votes as $vote)
                <tr>
                    <td style='width:200px'>{{ $vote['name'] }}
                    </td>
                    <td style='width:40px'>
                        @if ($vote['vote'])
                            {{ $vote['vote'] }}
                        @endif
                    </td>
            @endforeach
        </table>
<br>

        <form method="get" action="/votes/reopen/{{ $motion->id }}" id="reopen">
        </form>
        <button type="submit" form='reopen' class="btn btn-warning" >Reopen Motion</button>

        <hr>

        <h4>Closed motions - click motion number to show details </h4>

        @include('votes/partials/_list')

    </div>


@endsection
