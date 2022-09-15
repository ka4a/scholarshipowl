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
							<h2 class="title">Semester <span class="linebreak">spending award</span></h2>
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
					</div>
				</div>
				<div class="container">
					<div class="row">
						<div class="">
							<div class="left">
								<p>
									ScholarshipOwl is proud to offer its Semester Spending Award to students applying to or currently enrolled in higher education.
								</p>
								<p>
									The award is given each semester to one deserving student for use during the semester.
								</p>
								<p>
									Whether it is books, extra tutoring or just a little more spending money for the semester, students can use all the help they can get.
								</p>
								<p>
									This is our way of helping contribute and allow you, the student the focus needed on what is most important, your education.
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
