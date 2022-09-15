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
	<meta name="msapplication-TileColor" content="#4e8eec">
	<meta name="msapplication-TileImage" content="/mstile-144x144.png">
	<meta name="theme-color" content="#ffffff">

	<title>ScholarshipOwl - hundreds of scholarships one click away</title>
	{!! HTML::script('//code.jquery.com/ui/1.11.0/themes/smoothness/jquery-ui.css') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle30') !!}
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 7]>
		{!! HTML::style('assets/css/fontello-ie7.css') !!}
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
</head>
<body>

<article>
	<div id="landing-page" class="scholarship-eligibility-test">

		<div class="container-fluid">
			<div class="row">
				<header id="blue" class="page-header">
					<div class="container">
						<div class="row">
							<div class="topWrapper">
								<div class="topWrapperInner">
									<div class="top">
										<a href="{{ url_builder('/') }}" class="logo pull-left"></a>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="container">
						<div class="row">
                              <div class="youtube-sch">
                                  <div class="embed-responsive embed-responsive-16by9">
                                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Y0eE7Tr8PcI?autoplay=1" frameborder="0" allowfullscreen></iframe>
                                  </div>
                              </div>
							<div class="form-wrapper">
								@include('register/register-form-landing')
							</div>
						</div>
					</div>
				</header>
			</div>
		</div>


		<a name="content"></a>


		<div class="container">
			<div class="row">
				<div id="applyUnlimited" class="text1">
					Apply for <strong class="bold">Hudreds of Scholarships</strong> <span class="linebreak">with just one application!</span>
				</div>

				<div id="aboutUs" class="container">
					<div class="row">


						<div class="col-md-6 pull-left">
							<h3>Who We Are</h3>
							<p>ScholarshipOwl is a site designed to give students
								an easy way to pay for their education. We know how
							 	hard it is to pay for college, especially with the cost
							 	of attendance on the rise. Whether you need $100 or
							 	$100,000 to cover your expenses, we're here to help you
							 	<span class="bold">gain access to that money</span> and
							 	put it toward your schooling. All you have to do is
							 	<span class="bold">fill out one form, one time.</span>
							</p>
						</div>
						<div class="col-md-6 pull-right">
							<h3>Our System at a Glance</h3>
							<p>We have created a program that allows college and high
                                  school students to <span class="bold">apply for hundreds of scholarships
                                  with just one set of information.</span>
								You don't have to spend hours on the computer typing
								out your personal information and scholarship essays.
								We do all of the work for you. Our goal is to help you
								get the money you're looking for.
							</p>
						</div>
					</div>

				<div id="checkYourChances" class="button-wrapper clearfix">
					<div class="row">
						<div class="big-orange-button">
							<button class="" id="sign_up_now_btn" type="submit" name="signUpBtn" onclick="scrollTo($('#landingRegForm1'))">
								Apply now
									<div class="arrow-btn">
										<div class="arrow">
											<span class="a1"></span>
											<span class="a2"></span>
											<span class="a3"></span>
											<span class="a4"></span>
											<span class="a5"></span>
											<span class="a6"></span>
											<span class="a7"></span>
											<span class="a8"></span>
											<span class="a9"></span>
											<span class="a10"></span>
											<span class="a11"></span>
										</div>
									</div>
							</button>
						</div>
					</div>
				</div>

				</div>




			</div>
		</div>

	</div>
@include('includes/testimonials-lp')
</article>



		<div id="socialBottom" class="socialBottom center-block clearfix">
			<a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=500');return false;" href="https://www.facebook.com/share.php?u=https://www.facebook.com/pages/Scholarship-Owl/235886926604530" class="social_fb"></a>
			<a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://twitter.com/share?text=I%20just%20applied%20for%20scholarships%20with%20@ScholarshipOwl.%20Come%20try%20it%20out%20&amp;url=https://scholarshipowl.com" class="social_tw"></a>
			<a onclick="javascript:window.open('https://plus.google.com/share?url='+encodeURIComponent(location.href), '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://plus.google.com/u/0/103578248102416216044/about" class="social_gplus"></a>
		</div>

		<div id="dialog" title="Confirmation Required">
		  Are you sure about this?
		</div>

		{!! HTML::script('//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js') !!}
		{!! HTML::script('assets/js/bootstrap.min.js') !!}
		{!! HTML::script('assets/js/bootstrap-select.min.js') !!}
		{!! HTML::script('assets/js/jquery.mCustomScrollbar.min.js') !!}
		{!! HTML::script('assets/js/jquery.mousewheel.min.js') !!}
		{!! HTML::script('assets/js/jquery.bootstrap-autohidingnavbar.min.js') !!}
		{!! HTML::script('assets/js/jquery.checkboxes.min.js') !!}
		{!! HTML::script('assets/js/user.js') !!}
		{!! HTML::script('common/js/core.js') !!}
		{!! HTML::script('assets/js/classes/common.js') !!}
		{!! HTML::script('assets/js/bootstrap-checkbox.js') !!}
		{!! HTML::script('assets/js/landing/default.js') !!}
		{!! HTML::script('assets/js/landing/application.js') !!}

<!--[if lt IE 9]>
	<script src="js/html5shiv.js"></script>
<![endif]-->
  @include('includes/marketing/mixpanel_pageview')
  @include ('includes/zopim')
</body>
</html>
