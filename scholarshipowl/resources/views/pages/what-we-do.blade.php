@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('carousel4steps') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<section role="region" aria-labelledby="page-title">
  <div class="blue-bg clearfix">
		<div class="container">
			<div class="row">
				<div class="text-container text-center text-white">

					<h1 class="h2 text-light" id="page-title">
						What We Do
					</h1>
					<p class="lead mod-top-header">
                        ScholarshipOwl is an innovative service that searches for scholarships relevant to you and helps get you applied to all of them with only one registration form. The ScholarshipOwl program creates a list of eligible scholarships based on the details you give us when you register.
                    </p>
					<p class="lead mod-top-header">
                        We then go one step further and save you the time of applying to each scholarship by doing it all at once, <b>with just ONE FORM.</b>
					</p>

				</div>
			</div>
		</div>
	</div>
</section>

<!-- How to Apply -->
<section role="region" aria-labelledby="how-to-apply">
	<div class="section--how-to-apply paleBlue-bg clearfix">
		<div class="container">
			<div class="row">
				<div class="text-container center-block">

					<h2 class="h2 text-light text-center" id="how-to-apply">
						How to Apply
					</h2>
					<p class="text-medium text-center mod-text">
						Applying for scholarships with ScholarshipOwl is easy. In less than 15 minutes, you can enter most of the information you need for hundreds of scholarship applications.
					</p>
					<p class="text-medium text-center">
						Here's how to <strong>apply in 4 simple steps:</strong>
					</p>

				<!-- carousel -->
				<div class="carousel slide carousel_4_steps center-block" data-ride="carousel">

					<ol class="carousel-indicators">
						<li data-target=".carousel_4_steps" data-slide-to="0" class="active"><span class="indicator">1</span></li>
						<li data-target=".carousel_4_steps" data-slide-to="1" class=""><span class="indicator">2</span></li>
						<li data-target=".carousel_4_steps" data-slide-to="2" class=""><span class="indicator">3</span></li>
						<li data-target=".carousel_4_steps" data-slide-to="3" class=""><span class="indicator">4</span></li>
					  </ol>
					<div class="carousel-inner">
						<div class="item clearfix active">
							<figure>
								<img src="assets/img/whatwedo/slider1.png" alt="Fill out Your Profile" class="center-block img-responsive">
							</figure>

							<div class="carousel-caption">
								<div class="text-medium semibold">Fill out Your Profile</div>
								<div>
									<small>
										We need to know your name, age, where you're from and some school info. This will give us the info we need to match you to all the relevant scholarships. Complete each section of your profile accordingly, so that we can complete your scholarship applications.
									</small>
								</div>
							</div>
						</div>
						<div class="item clearfix">
							<figure>
								<img src="assets/img/whatwedo/slider2.png" alt="Review Your Award Offers" class="center-block img-responsive">
							</figure>
							<div class="carousel-caption">
								<div class="text-medium semibold">Review Your Award Offers</div>
								<div>
									<small>
										Once we know who you are, we will find a variety of scholarships you may be interested in. Look over each one of them to determine which ones you want to apply to. If you want to go for all of them the decision is all yours!
									</small>
								</div>
							</div>
						</div>
						<div class="item clearfix">
							<figure>
								<img src="assets/img/whatwedo/slider3.png" alt="Fill out Special Information" class="center-block img-responsive">
							</figure>
							<div class="carousel-caption">
								<div class="text-medium semibold">Fill out Special Information</div>
								<div>
									<small>
										Some scholarships require non-standard information. In those situations, we will ask for additional details on your end. This is usually an essay that is specific to a scholarship or organization. Complete any additional information we ask for, and we'll use that for your applications.
									</small>
								</div>
							</div>
						</div>
						<div class="item clearfix">
							<figure>
								<img src="assets/img/whatwedo/slider4.png" alt="Let ScholarshipOwl Go to Work for You" class="center-block img-responsive">
							</figure>
							<div class="carousel-caption">
								<div class="text-medium semibold">Let ScholarshipOwl Do all the Work for You</div>
								<div>
									<small>
                                        We'll use all of the data you have given us to apply for the scholarships you’ve chosen. We complete applications as soon as you tell us to, so you don't have to worry about missing deadlines. All you have to do is wait for a response from the scholarship board to see what you've won.
									</small>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- /carousel -->
				</div>
			</div>
		</div>
	</div>
</section>

<!-- Apply Now -->
<section role="region" aria-labelledby="apply-now">
	<div id="sign_up_now" class="lightBlue-bg clearfix">
		<div class="container center-block">
			<div class="row">
				<div class="text">
					<h2 class="sr-only" id="apply-now">Apply Now</h2>
					<div id="sign-up-btn" class="button-wrapper">
						<div class="btn btn-lg btn-block btn-warning mod-padding text-uppercase text-center">
							<a id="sign_up_now_btn" href="{{ url_builder('register') }}" class="">Apply Now</a>
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
						</div>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>


@include('includes/refer')
@stop
