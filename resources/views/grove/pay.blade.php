@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Dues for full grove year</h1>
        <br>
        Member: $70 includes ADF membership renewal<br>
<br>If you have access to Zelle, you can send your membership fee or donation by Zelle to finance@ravenscrygrove.org (to save us a few dollars in fees that Paypal charges) <br><br>
        To use Paypal instead, you can scan this Paypal QR code<br/><br/>
<img alt="QR Code" src="/img/qrcode.png">
<br/><br/>
or use one of these buttons <br/><br/>

        <br>
        Use Paypal preset to $70: <br/><br/>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick">
            <input type="hidden" name="hosted_button_id" value="SQUEKWG9TF94Y">
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
            <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
        </form>
        <br>
        Or, you can use Paypal to make a payment/donation of any amount: <br><br>
        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick" />
            <input type="hidden" name="hosted_button_id" value="76CYM7TT2DL7U" />
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
        </form>
    </div>
<br>

@endsection

