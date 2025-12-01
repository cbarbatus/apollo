<table border=2px style="empty-cells: show;">
    <COL>
    <COL>
    <TR>
        <TH>MOTION</TH>
        <TH>DESCRIPTION</TH>
    </TR>

    @foreach ($motions as $motion)
        <tr>
            <td style='width:40px; text-align: center;'>
                 <a href="/votes/look/{{ $motion->id}}"> {{ $motion->id}} </a>
            </td>
            <td style='width:380px'>
                {!! substr($motion->item, 0, 50) !!}...
            </td>
    @endforeach

</table>
<br>




