@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle23') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')
<section id="payment-congratulations-head" class="congratulations-head blue-bg clearfix">
	<div class="container">
		<div class="row">
			<div class="text-container text-center text-white">
				<h2 class="text-large text-light">Congratulations!</h2>
				<div class="text-semibold mod-subtitle">We have succesfully submitted Your scholarship applications.</div>
				<div class="text-medium"></div>
			</div>
		</div>
	</div>
</section>


<section class="paleBlue-bg">
	<div class="container">
		<div class="row">
			<div id="whatsNext" class="text-left">
				<p>
					What's next:
				</p>
				<ul>
					<li>
						Work on your essays
					</li>
					<li>
						Check the status of your applications
					</li>
					<li>
						Review your info, or
					</li>
					<li>
						Select more scholarships
					</li>
				</ul>
				<a href="{{ url_builder('my-account') }}" class="more-button save-changes text-uppercase">My Account</a>
			</div>
		</div>
	</div>
</section>

<section id="congratulations-body" class="congratulations paleBlue-bg clearfix">
	<div class="container">
		<div class="row">
			<div class="clearfix">

				<figure id="payment-finish-testimonial" class="clearfix">
					<figcaption>
						<p>So far it has been pretty great and a lot easier than individually having to find the scholarships! Easy and comfortable process, was not long at all! Very well set up over all, I would and have recommended it!</p>
						<p><em>Awesome job guys!</em></p>
					</figcaption>
					<img width="234" height="234" src="assets/img/testimonials_user11.png" alt="" class="center-block img-responsive img-circle pull-left">
				</figure>

			</div>
		</div>
	</div>
</section>
@stop
