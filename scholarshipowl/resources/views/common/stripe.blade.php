@if (\App\Entity\PaymentMethod::isStripe())
<script src="https://js.stripe.com/v3/"></script>
<script type="text/javascript">
    window.SOWLStripe = Stripe('{!! config('services.stripe.public_key')  !!}');
</script>
@endif