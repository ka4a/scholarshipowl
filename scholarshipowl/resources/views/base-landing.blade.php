<!DOCTYPE html>
<html lang="en">
	<head>
		@if (is_production())
			@include('includes/optimizely')
		@endif
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
		<link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
		<link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
		<link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
		<link rel="manifest" href="/android-chrome-manifest.json">
		<link href='https://fonts.googleapis.com/css?family=PT+Sans:400,700,400italic,700italic|Open+Sans:400,300,600,700|Raleway:300,700,800,900' rel='stylesheet' type='text/css'>
		<meta name="msapplication-TileColor" content="#4e8eec">
		<meta name="msapplication-TileImage" content="/mstile-144x144.png">
		<meta name="theme-color" content="#ffffff">
    <meta name="csrf-token" content="{{ csrf_token() }}">

        @section("metatags")
            <meta name="description" content="{{ \CMS::description() }}" />
            <meta name="keyword" content="{{ \CMS::keywords() }}" />
            <meta name="author" content="{{ \CMS::author() }}" />
            <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
            <meta property="og:description" content="{{ \CMS::description() }}" />
            <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
            <meta name="twitter:description" content="{{ \CMS::description() }}" />
        @show

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

		@include('facebook.facebook_sdk')

        {!! HTML::style('//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css') !!}
		{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle3') !!}

        @yield('addition-style-sheet')

		<script type="application/javascript">
				var SOWLStorage = {!! \WebStorage::json() !!}
		</script>

		{!! HTML::style('assets/css/external/vue-multiselect.min.css') !!}

		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 7]>
			{!! \App\Extensions\AssetsHelper::getCSSBundle('ie7shim2') !!}
		<![endif]-->

		<!--[if lt IE 9]>
			<script src="//oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
			<script src="//oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
		@if (is_production())
			@include('includes/google-analytics')

			@if (!empty($tracking))
				@include ("tracking/" . $tracking)
			@endif
		@endif
        @include('includes.google-tag-manager')
        @include('includes.marketing.mixpanel')
        @include('common.configs')
	</head>
	@yield('getContent')
	<body class="landing-page">
		<div id="login-modal"></div>
  		@yield('before.body')
		@yield('content')

		@section('footer')
		    @if (!features()->content()->hideFooter())
				<div id="footer-vue"></div>
		    @endif
		@show()

		{!! HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') !!}
		{!! HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js') !!}
	    {!! HTML::script('/common/js/configs.js') !!}

		@yield('scripts')
	    @include('common.recurly')
	    @include('common.stripe')

    <div id="modal-vue"></div>

    <!-- External vue components -->
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundleExternal') !!}

		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
		<![endif]-->

		@if (is_production())
			@include ('includes/adroll')
		@endif
	 	@yield('before.body.end')
</body>
</html>
