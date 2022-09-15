@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('upgradeMobile') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('calcButton') !!}
@endsection

@section('content')

<div class="mobile-header blue-bg">
    <div class="container">
        <div class="row">
            <div class="text text-center">
                <h2 class="title">Upgrade Your Membership</h2>
           </div>
        </div>
    </div>
</div>

<section id="selectPayment" class="paleBlue-bg">
	<div id="packages" class="container mobile">
		<div class="row">
            {{ Form::token() }}
            {{ Form::hidden("package", "") }}
            {{ Form::hidden("price", "") }}
            {{ Form::hidden("account_id", $user->getAccountId()) }}
            <p class="description upgrade-intro text-center">
                <span id="GeneralPackageMessage">{!! \App\Entity\FeaturePaymentSet::popupTitleDisplay() !!}</span>
            </p>

            <?php $i = 0; ?>
            @foreach ($packages as $packageId => $package)
            @php

                $packageContactClass = $package->isContactUs() ? 'package-contact' : '';
                $packageExpirationClass = $package->getExpirationType().'Package';
                $activeClass = $package->getIsMobileMarked() ? 'active' : '';
                $collapseClass = $i > 0 && $mobileSpecialOfferOnly ? 'collapse' : '';
            @endphp
            <div class="checkout text-center package paid-package-plus-padd {{ $packageContactClass }} {{ $packageExpirationClass }} {{ $activeClass }} {{ $collapseClass }}">
                <div class="mod-price">
                    @if ($package->getIsMobileMarked())
                    <div class="ribbon-wrapper-brick">
                        <div class="ribbon-brick">Most popular</div>
                    </div>
                    @endif
                    <div class="price">
                        <span class="priceType text-uppercase">{{ $package->getName() }}</span>
                        <div class="priceAmmount">
                            @if((int) $package->getDiscountPrice() !== 0 )
                                <span class="dolar">$</span>
                                <span class="ammount">
                                    {!!  intval($package->getDiscountPrice()) !!}
                                </span>
                            @else

                                @if ((int)$package->getPrice() !== 0)
                                    <span class="dolar">$</span>
                                    <span class="ammount">
                                        @if ($package->isExpirationTypeRecurrent() && $package->getPricePerMonth())
                                            {{ $package->getPricePerMonth() }}
                                            <sup class="billed-period">/mo</sup>
                                        @else
                                            {{ (int) $package->getPrice() }}
                                        @endif
                                    </span>
                                @else
                                    <span class="ammount">FREE</span>
                                @endif
                            @endif
                        </div>
                        @if ($package->getExpirationPeriodValue() !== 1 || $package->getExpirationPeriodType() !== \App\Entity\Package::EXPIRATION_PERIOD_TYPE_MONTH)
                        <div class="description">
                            {{ $package->getRecurrentTypeMessageFull() }}
                        </div>
                        @endif
                    </div>
                </div>
                <div class="mod-select-button">
                    <div class="selectButton upgrade-payment-cont">
                        <a class="{{ $package->isFreemium() ? 'GetFreemiumButton' : 'payment-opener' }} btn btn-success btn-block text-uppercase upgrade-payment-button  @if ($package->getIsMobileMarked()) {{ 'active' }} @endif"
                           data-id="{{$i}}"
                           data-package-id="{{ $package->getPackageId() }}"
                           data-tracking-params="{{ $tracking_params }}"
                           data-package-name="{{ $package->getName() }}"
                           @if ($package->isContactUs()) data-package-type="contact us" @endif
                           data-contact-us-link="{{ $package->getContactUsLink() }}"
                           data-package-billing-agreement="{{ $package->getBillingAgreement() }}"
                           data-package-price="{{ $package->getPrice() }}"
                           data-package-free-trial="{{ $package->isFreeTrial() ? 'true' : 'false' }}"
                            style="{{ $package->getButtonCssForPackage() }}">
                            @if ($package->getButtonTextForPackage())
                                {{ $package->getButtonTextForPackage() }}
                            @elseif($package->isFreemium())
                                CONTINUE
                            @else
                                Upgrade
                            @endif
                        </a>
                    </div>
                    <div class="description">{{ $package->getDisplayMessage() }}</div>
                </div>
                <ul class="list-unstyled clearfix hide-me-a-bit checkout-container" id="has-id-{{$i}}">
                    @if (\App\Entity\PaymentMethod::isCheckout())
                        @if($i === 0)
                            @include('payment.braintree.checkout', ['package' => $package])
                        @endif
                    @else
                        @if ($package->isExpirationTypeRecurrent() && !($package->getG2SProductId() && $package->getG2STemplateId()))
                            <li class="col-xs-12">
                        @else <li class="col-xs-6"> @endif
                                <div class="btn btn-default btn-lg btn-block gradient" role="button">
                                    @include('includes.paypal-mobile');
                                </div>
                            </li>
                        @if (!$package->isExpirationTypeRecurrent() || ($package->getG2SProductId() && $package->getG2STemplateId()))
                            <li class="col-xs-6">
                                <button class="btn btn-default btn-lg btn-block gradient @if (empty($missing_data)) {{ 'PaymentFormButton' }} @else {{ 'MissingPaymentDataButton' }} @endif" role="button" data-package-id="{{ $package->getPackageId() }}" data-tracking-params="{{ $tracking_params }}">
                                    <div class="btn-inner-container">
                                        <span class="ccText text-center">
                                            Credit Card
                                        </span>
                                    </div>
                                </button>
                            </li>
                        @endif
                    @endif
                </ul>
                <div class="panel-group">
                    <div id="Package_{{ $packageId }}" aria-labelledby="Package_{{ $packageId }}">
                        <div class="panel-body">
                            <ul class="list-group">
                                 @foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
                                    <li class="list-group-item">
                                        {!! $item !!}
                                    </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <?php $i++; ?>
            @endforeach
    	</div>
    	@if($mobileSpecialOfferOnly)
        <div class="row">
            <div class="text-center ExploreMoreButton">
                <a href="#" class="ExploreMoreButton">More membership options</a>
            </div>
        </div>
        @endif
        @include('includes/texts/deserve-it-disclaimer')
        <div class="clearfix"></div>
        <div class="disclaimer-mobile">
            @if (setting_on('disclaimer.enabled'))
                <p class="text-center disclaimer">{!! setting('disclaimer.text') !!}</p>
            @endif
        </div>
        <div id="paymentForm" class="wrapper mobile">
            <div class="row">
                <div class="col-xs-12 col-sm-10 col-sm-offset-1">
                    <div id="Gate2ShopTop">&nbsp;</div>
                    <div class="row" id="Gate2ShopForm"></div>
                </div>
            </div>
        </div>

        <div class="row">
                <div class="payment">
                    <div class="upsale-wrapper center-block">
                        <div class="col-sm-6 col-md-12">
                            <div class="upsale">
                                <div class="bars">
                                    <div class="text text-uppercase">
                                        <span class="text1-b">
                                            <strong>
                                            Over 90% of respondents rated the sign up process as easy and intuitive.
                                            </strong>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-6 col-md-12">
                            <div class="upsale">
                                <div class="bars">
                                    <div class="text text-uppercase">
                                        <strong>83% of respondents wrote:</strong>
                                    </div>
                                    <div class="text1 text-uppercase">
                                        <span class="text1-a">
                                            "Being automatically applied to multiple scholarships is a huge advantage "
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
	</div>
</section>


@if (!empty($error))
	@if ($error == "payment_fail" || $error == "paypal")
		<div id="NotificationBody">
			<p>There was an error processing your payment.</p>
		</div>

		<div id="NotificationFooter">
			<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>
		</div>
	@endif
@endif

@include ('includes/popup')
@include ('includes/mobile-missions-popup')
@include('includes/marketing/mixpanel_pageview')
@stop
