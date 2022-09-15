<noscript><img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1779250712320447&ev=PageView&noscript=1"/></noscript>
<script>
    !function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};if(!f._fbq)f._fbq=n;
        n.push=n;n.loaded=!0;n.version='2.0';n.queue=[];t=b.createElement(e);t.async=!0;
        t.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,
            document,'script','https://connect.facebook.net/en_US/fbevents.js');

    fbq('init', '1779250712320447');
    fbq('track', "PageView");
</script>

@if(Session::pull('FACEBOOK_ACCOUNT_FULL_REGISTERED'))
    <script>
        fbq('track', 'Account-ALL');
    </script>
@endif

@if(Session::has('FACEBOOK_ACCOUNT_MEMBERSHIP_PURCHASED'))
    <script>
        fbq('track', 'Sale-ALL', {value: '{!! Session::pull('FACEBOOK_ACCOUNT_MEMBERSHIP_PURCHASED') !!}', currency: 'USD'});
    </script>
@endif