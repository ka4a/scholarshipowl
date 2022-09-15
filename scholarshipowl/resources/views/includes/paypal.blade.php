<form action="{{ is_production() ? "https://www.paypal.com/cgi-bin/webscr" : "https://www.sandbox.paypal.com/cgi-bin/webscr" }}" method="post" target="_top">

    <input type="hidden" name="cmd" value="_xclick">
    <input type="hidden" name="business" value="{{ $paypal['business'] }}">

    <input type="hidden" name="item_name" value="">
    <input type="hidden" name="amount" value="">
    <input type="hidden" name="custom" value="">

    <!-- Recurent payments fields -->
    <input type="hidden" name="a3" value="">
    <input type="hidden" name="p3" value="">
    <input type="hidden" name="t3" value="">
    <input type="hidden" name="src" value="1">

    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="no_note" value="0">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="return" value="{{ url('') }}/paypal-success">
    <input type="hidden" name="cancel_return" value="{{ url('') }}/my-account?error=paypal">

    <input class="paypal-input" type="image" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
