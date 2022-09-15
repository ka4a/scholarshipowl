@extends('base')
@section('content')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('awards') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection


<section id="awards" class="blue-bg clearfix">
				<div class="container">
					<div class="row">
						<div class="text">
							<div class="overhead">ScholarshipOwl</div>
							<h2 class="title">Social <span class="linebreak">participation award</span></h2>
						</div>
					</div>
				</div>
			</section>

	<section id="everyone-is-eligible" class="paleBlue-bg clearfix">
				<div class="container">
					<div class="row">
						<h2 class="title">
							Everyone is eligible
						</h2>

						<div class="">
							<div class="left">
								<p>
									In 2014, Social Media has taken an even greater role in shaping our future.
								</p>
								<p>
									ScholarshipOwl recognizes the importance of this and wants to <strong class="bold">reward students who excel at using social media to their advantage</strong>.
								</p>
								<p>
									The ScholarshipOwl Social Participation Award is given to two deserving students each semester who have shown how social media, like facebook, twitter and snapchat can be used to inform and educate.
								</p>
								<p>
									<strong clas="bold">The $500 award</strong> will be given to the deserving college applicants or current students to be used toward furthering their education. Show us that you have what it takes and you could be chosen.
								</p>

								<div class="visible-xs-block visible-sm-block clearfix">

									<div id="sign-up-btn" class="button-wrapper">
										<div class="big-orange-button">
											<a id="sign_up_now_btn" href="{{ url_builder('register') }}" class="">Sign Up Now</a>
											<span class="arrow-btn">
												<span class="arrow">
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
												</span>
											</span
										></div>
									</div>

									<div class="dots"></div>
									<div class="requirements">
										<span class="bold">Requirements:</span><br>
										<ul>
											<li>
												<span>Complete the ScholarshipOwl registration. <span class="blue">*</span></span>
											</li>
											<li>
												<span>Send 5 friends to ScholarshipOwl to sign up.</span>
											</li>
										</ul>
									</div>
									<div class="dots"></div>
									<p class="notes">
										<span class="blue">*</span> Students with a full profile will automatically be re-entered for a chance to win each semester.
									</p>
								</div>
							</div>

							<div class="right visible-md-block visible-lg-block">

								<div id="sign-up-btn" class="button-wrapper">
									<div class="big-orange-button">
										<a id="sign_up_now_btn" href="{{ url_builder('register') }}" class="">Sign Up Now</a>
										<span class="arrow-btn">
											<span class="arrow">
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
											</span>
										</span>
									</div>
								</div>

								<div class="dots"></div>
								<div class="requirements">
									<span class="bold">Requirements:</span><br>
									<ul>
										<li><span>Complete the ScholarshipOwl registration. <span class="blue">*</span></span></li>
										<li><span>Send 5 friends to ScholarshipOwl to sign up.</span></li>
									</ul>
								</div>
								<div class="dots"></div>
								<p class="notes">
									<span class="blue">*</span> Students with a full profile will automatically be re-entered for a chance to win each semester.
								</p>
							</div>
						</div>
					</div>
				</div>
			</section>

	@include('includes/testimonials')
	@include('includes/refer')
@stop
