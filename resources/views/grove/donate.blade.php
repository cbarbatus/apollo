@extends('layouts.app')

@section('content')

    <div class='container'>
        <h1>Donate to suport Raven's Cry Grove, ADF</h1>
        Use Paypal or Zelle to make a donation.  Donations help pay for venue rentals, ritual supplies, and our donations to Sempervirens Fund
        and to Pagan Pride Los Angeles / Orange County.  Raven's Cry Grove, ADF is recognized as a 501(c)(3) charitable organization."If you have access to Zelle, you can send your membership fee by Zelle to finance@ravenscrygrove.org (to save us a few dollars in fees that Paypal charges)"
        <br>
        Use Zelle to make a donation:<br>
        If you have access to Zelle, you can send your membership fee by Zelle to finance@ravenscrygrove.org (to save us a few dollars in fees that Paypal charges<br><br>
        Use Paypal to make a donation:<br>
        Donations help pay for venue rentals, ritual supplies, and our donations to Sempervirens Fund
        and to Pagan Pride Los Angeles / Orange County.  Raven's Cry Grove, ADF is recognized as a 501(c)(3) charitable organization.
        <br><br>

        <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
            <input type="hidden" name="cmd" value="_s-xclick" />
            <input type="hidden" name="hosted_button_id" value="76CYM7TT2DL7U" />
            <input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif" border="0" name="submit" title="PayPal - The safer, easier way to pay online!" alt="Donate with PayPal button" />
            <img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
        </form>

    </div>
<br>

@endsection

