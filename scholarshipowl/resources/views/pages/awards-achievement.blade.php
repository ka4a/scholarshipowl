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
							<h2 class="title">Achievement award</h2>
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
									The ScholarshipOwl Achievement Award is given out each year to a deserving student who has excelled in their studies.
								</p>
								<p>
									The award is applicable for students applying for or currently enrolled in higher education.
								</p>
								<p>
									ScholarshipOwl is looking for a student who is committed to pursuing higher education as a platform to succeed in the future.
								</p>
								<p>
									We know the importance of financial help during school and we are committed to helping as much as we can.
								</p>
								<p>
									Show us that you have what it takes to achieve your goals, and you can get $1,000 toward your education. In 2014, Social Media has taken an even greater role in shaping our future.
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
