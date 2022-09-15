@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle4') !!}
@endsection

@section("scripts2")
	{!! \App\Extensions\AssetsHelper::getJSBundle('bundle3') !!}
@endsection

@section('content')

@include('includes/eligibility')

<div id="elgb" class="section--applying-easier blue-bg">
	<div class="container">
		<div class="row">
			<div class="mod-text-container text-center">
				<div class="text-light text-white">ScholarshipOwl is here to make <strong>applying for college scholarships easier</strong>
				</div>
        <div class="provide-text">We provide you access to our proprietary scholarship management interface technology and services which help you apply to as many scholarships as possible in the least amount of time.</div>
			</div>
		</div>
	</div>
</div>

<!-- Ass seen on -->
<div class="mod-hp section--as-seen-on">
  <div class="container-fluid check-what-wrapper">
    <div class="row">
      <div class="col-xs-12 text-center">
        <h2 style="margin-top: 0.3em" class="h4 text-muted text-light">Check what others say about us</h2>
      </div>
    </div>
  </div>
    <div class="as-seen-wrapper">
        <div class="container">
            <ul class="list-inline logosUl logosHP">
                <li>
                    <a href="https://thenextweb.com/insider/2015/07/22/scholarshipowl-automates-college-scholarships-for-students/" target="_blank">
                      <div class="section-as-seen-on tnw"></div>
                    </a>
                </li>
                <li>
                  <a href="http://techzulu.com/scholarshipowl-one-done-never-miss-a-scholarship-opportunity/" target="_blank">
                    <div class="section-as-seen-on tech-zulu"></div>
                  </a>
                </li>
                <li>
                    <a href="https://vator.tv/news/2015-07-22-scholarshipowl-launches-to-link-students-with-scholarships#Vv1bkGLyP3Zzemk0.99" target="_blank">
                      <div class="section-as-seen-on vator-news"></div>
                    </a>
                </li>
                <li>
                    <a href="http://www.forbes.com/sites/annefield/2015/08/30/applying-for-private-scholarships-no-longer-a-wild-goose-chase/" target="_blank">
                      <div class="section-as-seen-on forbes"></div>
                    </a>
                </li>
                <li>
                    <a href="https://gigaom.com/2015/09/14/scholarshipowl-uses-big-data-machine-learning-to-fix-the-convoluted-scholarship-application-process/" target="_blank">
                      <div class="section-as-seen-on gigaom"></div>
                    </a>
                </li>
                <li>
                    <a href="https://www.producthunt.com/tech/scholarshipowl" target="_blank">
                      <div class="section-as-seen-on product-hunt"></div>
                    </a>
                </li>
                <li>
                    <a href="https://hellogiggles.com/website-connect-perfect-scholarship/" target="_blank">
                      <div class="section-as-seen-on hello-giggles"></div>
                    </a>
                </li>
                <li>
                    <a href="http://www.huffingtonpost.com/entry/how-to-attend-college-without-going-broke_us_58bedb0de4b0abcb02ce225b" target="_blank">
                      <div class="section-as-seen-on huffington-post"></div>
                    </a>
                </li>
                <li>
                    <a href="https://www.bizjournals.com/losangeles/news/2015/07/22/scholarshipowl-feathers-students-nests-with.html" target="_blank">
                      <div class="section-as-seen-on la-biz"></div>
                    </a>
                </li>
                <li>
                    <a href="http://www.uloop.com/news/view.php/195074/scholarshipowls-new-tool-helps-college-students-find-scholarships" target="_blank">
                      <div class="section-as-seen-on uloop"></div>
                    </a>
                </li>
                <li>
                    <a href="https://techcrunch.com/2016/06/10/scholarships-are-the-new-sweepstakes/" target="_blank">
                      <div class="section-as-seen-on tech-crunch"></div>
                    </a>
                </li>
                <li style="margin-left: 5px">
                    <a href="https://newswatchtv.com/2019/05/06/scholarshipowl-newswatch-review/" target="_blank">
                      <div class="section-as-seen-on news-watch"></div>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Testimonials -->
@include('includes/testimonials')

<!-- How It Works -->
<section role="region" aria-label="how-it-works">
	<div class="section--how-it-works text-center clearfix">
		<div class="container">
			<div class="row">
				<h2 class="text-bold mod-heading hiw-title" id="how-it-works">
					How it Works
				</h2>
				<p class="text-light mod-subtitle">
					We do all the work, <span class="linebreak-xxs">and <strong>you reap all the rewards!</strong></span>
				</p>
				<div class="hiw-steps center-block clearfix">
					<div class="hiw-step step1 center-block">
						<p class="hiw-step-heading"><strong>You fill out<br> an application</strong></p>
						<p class="text-light">Fill in 3-4 fields to see how many scholarships you are eligible for.</p>
					</div>
					<div class="hiw-step step2 center-block">
						<p class="hiw-step-heading"><strong>We search for<br>compatible scholarships</strong></p>
						<p class="text-light">Based on your data we will find scholarships that you are eligible for.</p>
					</div>
					<div class="hiw-step step3 center-block">
						<p class="hiw-step-heading"><strong>We apply for you!</strong></p>
						<p class="text-light mod-last-child">With some luck you will win the scholarship of your dreams!</p>
					</div>
				</div>
				<div class="hiw-results clearfix"></div>
			</div>
		</div>
	</div>
</section>

<!-- Sign up -->
<section role="region" aria-label="Learn More or Sign Up">
	<div class="section--sign-up-now clearfix">
		<div class="container">
			<div class="row">
				<div class="col-xs-12">
					<div class="text-container">
						<div class="save-time-text text-light">
							<h2 class="sr-only">Learn More or Sign Up</h2>
							<strong>Save time</strong> and concentrate on what's <span class="linebreak-md"><strong>really important</strong> to you</span>
						</div>

						<div class="learn-more-buttons center-block clearfix">

							<div class="col-xs-12 col-sm-5 col-md-3 mod-offset">
								<a class="btn btn-primary btn-block btn-lg text-uppercase" href="whatwedo">learn more</a>
							</div>
							<div class="col-xs-12 col-sm-2 col-md-1">
								<span class="text-uppercase text-light mod-or">or</span>
							</div>
							<div class="col-xs-12 col-sm-5 col-md-3">
								<a class="btn btn-warning btn-block btn-lg text-uppercase" href="{{ url('register') }}">signup now</a>
							</div>

						</div>

					</div>
				</div>
			</div>
		</div>
	</div>
</section>

@include('includes.browser-update')

@include('includes/marketing/mixpanel_pageview')

@include('includes/refer')

@stop
