<form action="{!! is_production() ? "https://www.paypal.com/cgi-bin/webscr" : "https://www.sandbox.paypal.com/cgi-bin/webscr" !!}" method="post" target="_top">

    @if ($package->isExpirationTypeRecurrent())
        <input type="hidden" name="cmd" value="_xclick-subscriptions">
        <input type="hidden" name="a3" value="{!! $package->getPrice() !!}">
        <input type="hidden" name="p3" value="{!! $package->getExpirationPeriodValue() !!}">
        <input type="hidden" name="t3" value="{!! $package->getPaypalExpirationPeriodType() !!}">
        <input type="hidden" name="no_note" value="1">
        <input type="hidden" name="src" value="1">
    @else
        <input type="hidden" name="cmd" value="_xclick">
    @endif

    <input type="hidden" name="business" value="{!! $paypal['business'] !!}">
    <input type="hidden" name="item_name" value="{!! $package->getName() !!}">
    <input type="hidden" name="amount" value="{!! $package->getPrice() !!}">
    <input type="hidden" name="custom" value="{!! $package->getPackageId() . "_" . $user->getAccountId() . "_" . $tracking_params !!}">

    <!-- Recurent payments fields -->
    <input type="hidden" name="rm" value="2">
    <input type="hidden" name="no_shipping" value="1">
    <input type="hidden" name="return" value="{{ url('') }}/paypal-success">
    <input type="hidden" name="cancel_return" value="{{ url('') }}/my-account?error=paypal">

    <input class="paypal-input" type="image" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
    <img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">
</form>
