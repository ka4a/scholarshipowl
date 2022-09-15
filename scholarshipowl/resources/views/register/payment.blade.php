@extends('base')

@section("styles")
  {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle19') !!}
@endsection

@section("scripts")
    {!! HTML::script('https://mottie.github.io/tablesorter/js/jquery.tablesorter.js') !!}
    {!! HTML::script('assets/plugins/checkboxes/jquery.checkboxes.min.js') !!}
@endsection

@section("scripts2")
    {!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll-probe.js') !!}
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle17') !!}
@endsection

@section('content')
    <section role="region" aria-labelledby="payment-title">
        <div id="registered" class="blue-bg clearfix">
            <div class="container">
                <div class="row">
                    <div class="text-container text-center text-white">
                        <h2 class="text-large text-light">
                            Apply to all Scholarships automatically
                        </h2>
                        <p class="payment-subtitle">
                            <strong>{{ $user->getProfile()->getFullName() }}</strong>, we found <strong>{{ $eligibility_count }}</strong> scholarship matches for you, with more to be discovered every month!
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <section class="register-payment-cont">
        <div class="container">
            <div class="payment-packages">
                <div class="text-center packages-intro">
                    <p class="text-bold mod-subtitle text-uppercase text-blue">
                        Choose one of the options to start
                    </p>

                </div>
                @include('includes.upgrade-form')
            </div>
            <div class="disclaimer-secure-upgrade">
                <p class="text-center disclaimer">
                  @include('includes/texts/explanatory')
                </p>
            </div>

        </div>
    </section>

    @if (is_production())
        @if ($offerId == "30")
            <?php
            $entityAccount = \EntityManager::findById(\App\Entity\Account::class, $user->getAccountId());
            \HasOffers::info(
                    "HasOffers pixel: ".
                    "URL: ".Request::path()."; ".
                    "Account details: ".print_r(logHasoffersAccount($entityAccount), true)."; ".
                    "TransactionId: ".$marketingSystemAccount->getHasOffersTransactionId()."; ".
                    "AffiliateId: ".$marketingSystemAccount->getHasOffersAffiliateId()."; ".
                    "GoalId: 0"
            );
            ?>
            <img src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=18" width="1" height="1" style="display:none;" />
        @endif

        @if ($offerId == "32")
            <?php
            $entityAccount = \EntityManager::findById(\App\Entity\Account::class, $user->getAccountId());
            \HasOffers::info(
                    "HasOffers iframe: ".
                    "URL: ".Request::path()."; ".
                    "Account details: ".print_r(logHasoffersAccount($entityAccount), true)."; ".
                    "TransactionId: ".$marketingSystemAccount->getHasOffersTransactionId()."; ".
                    "AffiliateId: ".$marketingSystemAccount->getHasOffersAffiliateId()."; ".
                    "GoalId: 20"
            );
            ?>
            <iframe src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=20&transaction_id={{$marketingSystemAccount->getHasOffersTransactionId()}}" scrolling="no" frameborder="0" width="1" height="1" ></iframe>
        @endif
    @endif
@stop
