@if (is_production() || request()->get('enable-tag-manager'))

@section('before.body')
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TJRBS3" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@parent
@stop
@section('before.body.end')
@parent
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TJRBS3');</script>
<!-- End Google Tag Manager -->
<?php
if (account()) {
    /** @var \App\Entity\Repository\SubscriptionRepository $subscriptionRepository */
    $subscriptionRepository = \EntityManager::getRepository(\App\Entity\Subscription::class);
    $subscription = $subscriptionRepository->getTopPrioritySubscription(account());
    $packagePrice = $subscription ? $subscription->getPrice() : 0;
    $isMember =  $subscription? ($subscription->isFreemium() || $subscription->hasCredits()) : false;
}
?>
<script>
dataLayer.push({
    @if (account())
    'email' : '{!! account()->getEmail() !!}',
    'account' : '{!! account()->getAccountId() !!}',
    'member' : {!! $isMember ? 'true' : 'false' !!},
    'packagePrice' : {!! $packagePrice !!},
    @endif
    'affid' : "{!! \App\Services\HasOffersService::getCookieAffiliateId() !!}",
    'isMobile' : {!! is_mobile() ? 'true' : 'false' !!},
    'fset' : '{!! \App\Entity\FeatureSet::config() !!}',
    'SRV' : '{!! env('APP_SRV', 'undefined') !!}',
    'url' : window.location.pathname
});

window.triggerGTMSubscriptionEvents = function(data) {
    if (!window.dataLayer) {
        window.dataLayer = [];
    }

    window.dataLayer.push({
        event: 'onSubscriptionAdded_Any',
        hasOffersTransactionId: data.hasOffersTransactionId || '',
        hasOffersAffiliateId: data.hasOffersAffiliateId || '',
        isMobile: data.isMobile ? 'true' : 'false',
        accountId: data.accountId,
        email: data.email,
        packageId: data.packageId,
        packagePrice: data.packagePrice
    });

    if (!data.isFreeTrial && !data.isFreemium) {
        window.dataLayer.push({
            event: 'onSubscriptionAdded_Charged',
            hasOffersTransactionId: data.hasOffersTransactionId || '',
            hasOffersAffiliateId: data.hasOffersAffiliateId || '',
            isMobile: data.isMobile ? 'true' : 'false',
            accountId: data.accountId,
            email: data.email,
            packageId: data.packageId,
            packagePrice: data.packagePrice
        });
    }

    if (data.isFreemium) {
        window.dataLayer.push({
            event: 'onSubscriptionAdded_Freemium',
            hasOffersTransactionId: data.hasOffersTransactionId || '',
            hasOffersAffiliateId: data.hasOffersAffiliateId || '',
            isMobile: data.isMobile ? 'true' : 'false',
            accountId: data.accountId,
            email: data.email,
            packageId: data.packageId,
            packagePrice: data.packagePrice
        });
    }

    if (data.isFreeTrial) {
        window.dataLayer.push({
            event: 'onSubscriptionAdded_FreeTrial',
            hasOffersTransactionId: data.hasOffersTransactionId || '',
            hasOffersAffiliateId: data.hasOffersAffiliateId || '',
            isMobile: data.isMobile ? 'true' : 'false',
            accountId: data.accountId,
            email: data.email,
            packageId: data.packageId,
            packagePrice: data.packagePrice
        });
    }
};
</script>
@stop
@endif
