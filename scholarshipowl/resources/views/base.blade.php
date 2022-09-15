<!DOCTYPE html>

<html lang="en">
	<head>
        {{--@if (is_production())--}}
            @include('includes/marketing/pushnami')
        {{--@endif--}}

        <meta charset="utf-8">
        @section("metatags")
            <meta name="description" content="{{ \CMS::description() }}" />
            <meta name="keyword" content="{{ \CMS::keywords() }}" />
            <meta name="author" content="{{ \CMS::author() }}" />
            <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
            <meta property="og:description" content="{{ \CMS::description() }}" />
            <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
            <meta name="twitter:description" content="{{ \CMS::description() }}" />
        @show
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
        <meta name="csrf-token" content="{{ csrf_token() }}">

		@yield("tags")
        <meta property="fb:app_id" content="666760003415916" />
        <meta property="og:site_name" content="ScholarshipOwl" />
        <meta property="og:url" content="{{ Request::url() }}" />

        <meta name="twitter:card" content="summary" />
        <meta name="twitter:site" content="@ScholarshipOwl" />

        @section('metatitle')
            <title>{{ \CMS::title() }}</title>
            <meta property="og:title" content="{{ \CMS::title() }}" />
            <meta name="twitter:title" content="{{ \CMS::title() }}" />
        @show

        <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700|Raleway:300,700,800" rel="stylesheet">

        {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle1') !!}

        @if (isset($user))
            {!! \App\Extensions\AssetsHelper::getCSSBundle('paymentPopUp') !!}
        @endif

        @yield("styles")
        <!-- SASS -->
        {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle2') !!}

        @include('includes.marketing.mixpanel')
        @include('includes.google-tag-manager')

        {!! HTML::script('https://apis.google.com/js/client.js?onload=checkAuth') !!}

        @include('facebook.facebook_sdk')
        @include('includes.twttr')

		@if (is_production())
            @include('includes/google-analytics')

			@if (!empty($tracking))
				@include ("tracking/" . $tracking)
			@endif
		@endif

        @include('common.configs')
        @include('common.webstorage')
	</head>

	<body class="@if(is_mobile()) root-mobile @if(features()->content()->hideFooter()) no-footer @endif @endif">
	{{--{{ Form::radio('recurring_application', \App\Entity\Profile::RECURRENT_APPLY_ON_DEADLINE, \Auth::user()->getProfile()->getRecurringApplication() === \App\Entity\Profile::RECURRENT_APPLY_ON_DEADLINE) }}--}}
        @yield('before.body')

		@section('header')
            <style>
                #vue-header {
                    min-height: 58px;
                    background-color: white;
                    box-shadow: 0 2px 3px rgba(0,0,0,.25);
                    width: 100%;
                    z-index: 100;
                }
            </style>
            <div id="vue-header"></div>
        @show

        @if (!isset($user) && isset($social) && $social)
            @include('includes/social')
        @endif

		<main role="main" aria-label="main content" id="main" tabindex="-1">

            @if (count($errors) > 0)
                <div class="alert alert-danger" style="margin-bottom: 0;">
                    <ul class="container">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
		</main>

        <div id="login-modal"></div>
        @section('footer')
            @if (!features()->content()->hideFooter())
				<div id="footer-vue"></div>
            @endif
        @show()


        @if(isset($missionStatuses))
            @foreach($missionStatuses as $missionId => $missionStatus)
                @if($missionStatus == "completed" && $missionAccountNotified[$missionId] == 0)
                    <input type="hidden" class="MissionCompleted" data-mission-id="{{ $missionId }}">
                @endif
            @endforeach
        @endif

        @if (isset($user))
            @if (!$isMobile)
                @include('payment.popup.payment-popup')
            @else
                @include('payment.mobile.payment-popup-mobile')
            @endif

            @yield("popups")

            @include ('includes.invite-friends-popup')
        @endif

		@if(isset($popups) && isset($user))
			@foreach($popups as $popupId => $popup)
				@if($popup->getPopupDisplay() != 0)
					<input type="hidden" class="PopupDisplay" data-popup-id="{{ $popup->getPopupId() }}" data-popup-display="{{ $popup->getPopupDisplay() }}" data-popup-type="{{ $popup->getPopupType() }}" data-popup-delay="{{ $popup->getPopupDelay() }}" data-popup-display-times="{{ $popup->getPopupDisplayTimes() }}" data-source-page="{{ Request::path() }}" data-trigger-upgrade="{{ $popup->isTriggerUpgrade() }}" data-ext-dialogue-text="{{ $popup->getPopupExitDialogueText() }}">
				@endif
			@endforeach
			<input type="hidden" name="popupActionCompleted" id="popupActionCompleted" value="0" />
			@include('includes.raf-exit-popup')
            <!-- pormotion pop up -->
			@include('includes.package-exit-popup')
		@endif

        @if (is_production())
			@include ('tracking/google/google_remarketing')
		@endif

        @if(isset($showHasoffersIframe) and $showHasoffersIframe == true)
            <?php
                $entityAccount = \EntityManager::findById(\App\Entity\Account::class, $user->getAccountId());
                \HasOffers::info(
                        "HasOffers sale: ".
                        "URL: ".Request::path()."; ".
                        "Account details: ".print_r(logHasoffersAccount($entityAccount), true)."; ".
                        "TransactionId: ".$marketingSystemAccount->getHasOffersTransactionId()."; ".
                        "AffiliateId: ".$marketingSystemAccount->getHasOffersAffiliateId()."; ".
                        "GoalId: 0"
                );
            ?>
            @if(\Session::get('HO_FLAG_FREETRIAL', false))
                <iframe src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=40" scrolling="no" frameborder="0" width="1" height="1"></iframe>
            @else
                <iframe src="https://scholarship.go2cloud.org/aff_l?offer_id=32" scrolling="no" frameborder="0" width="1" height="1"></iframe>
            @endif
        @endif

        @if (isset($user) && isset($testGroups))
            @foreach ($testGroups as $testId => $testGroup)
                <input type="hidden" id="ABTest{{ $testId }}" data-test-id="{{ $testId }}" value="{{ $testGroup }}" />
            @endforeach
		@endif

        <!-- Include cookie policy disclaimer -->
        @include('includes.cookie-disclaimer')

        @include('common.recurly')
        @include('common.stripe')

        <!-- Plugins JS -->
        {!! \App\Extensions\AssetsHelper::getJSBundle('bundle1') !!}

        <!-- Scripts JS -->
        @yield("scripts")

        <!-- Core JS -->
        {!! \App\Extensions\AssetsHelper::getJSBundle('bundle2') !!}

        @include('common.modal-vue')

        <!-- External vue components -->
        {!! \App\Extensions\AssetsHelper::getJSBundle('bundleExternal') !!}

        <!-- Scripts 2 JS -->
        @yield("scripts2")

        <!-- Coregs JS -->
        @if (isset($coregs) && !$coregs->isEmpty())
            @foreach($coregs as $coreg)
                {!! $coreg->getJs() !!}
            @endforeach
        @endif

        @if(is_production())
            @include('facebook.facebook_pixels')
        @endif

        @include('common.sentry')

        @yield('before.body.end')
	</body>
</html>
