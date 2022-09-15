@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('additionalServices') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('affiliates') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- Partners header -->
<section role="region" aria-labelledby="page-title">
    <div class="section--affiliates-header blue-bg clearfix">
        <div class="container">
            <div class="row">
              <div class="text-container text-center text-white">
                <h1 class="h2 text-light" id="page-title">Become Our Partner</h1>
                <p class="lead mod-top-header">
                    Partner with ScholarshipOwl, and get rewarded. We offer a wide range of lucrative partnership opportunities and payout models, so you can select the campaign that's right for you. Promote a great product and help students get matched with scholarships. Get in touch today and lets discuss how we can work together.
                </p>
              </div>
          </div>
        </div>
    </div>
</section>

<!-- What You'll Be Promoting -->
<section role="region" aria-labelledby="what-youll-be-promoting">
    <div class="section--who-we-are-services lightBlue-bg clearfix">
        <div class="container center-block">
            <div class="row">

                <div class="text-container clearfix">

                    <header>
                        <div class="section--who-we-are-services-header text-center">
                            <h2 class="h2 mod-text-size text-center" id="what-youll-be-promoting">
                                What You'll Be Promoting
                            </h2>
                            <p class="lead">
                                ScholarshipOwl partners have easy opportunities to earn money because of the outstanding products and services we provide. To get a better understanding of what you'll be promoting, check out our core services:
                            </p>
                        </div>
                    </header>

                    <div class="col-xs-12 col-sm-6 item item-merit">
                        <div class="row">
                            <div class="col-xs-4 col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/whoweare/icon-no-essay-scholarships.png" alt="No Essay Scholarships" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Scholarship Matching
                                </h2>
                                <p>
                                    <small>
                                        Students are matched with scholarship opportunities based on their personal information. They don't have to sort through thousands of awards. We narrow their options for them.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 item item-no-essay-scholarships">

                        <div class="row">
                            <div class="col-xs-4 col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/whoweare/icon-no-essay-scholarships.png" alt="No Essay Scholarships" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Hassle-Free Scholarship Applications
                                </h2>
                                <p>
                                    <small>
                                        Students complete one scholarship application when they sign up for their account. We use this information to complete their subsequent applications so they don't have to waste time with repetitive form filing. This speeds up the application process, allowing students to apply for even more awards and earn more money for college.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-xs-12 col-sm-6 item item-need-based-scholarships">
                        <div class="row">
                            <div class="col-xs-4 col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/whoweare/icon-no-essay-scholarships.png" alt="No Essay Scholarships" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Automatic Recurring Applications
                                </h2>
                                <p>
                                    <small>
                                        We automatically send out applications for recurring scholarships so students do not have to watch for deadlines. For example, if an award is renewed once a month, we will submit a student's application every month before the deadline to improve his chances of winning.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 item item-athletic-scholarships">
                        <div class="row">
                            <div class="col-xs-4 col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/whoweare/icon-no-essay-scholarships.png" alt="No Essay Scholarships" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                   Scholarship Management Tools
                                </h2>
                                <p>
                                    <small>
                                        Students can monitor their application status, essays, upcoming award opportunities, and much more through our online management tools. These tools are available anywhere the students have access to the internet.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-xs-12 col-sm-6 item item-minority-scholarships">
                        <div class="row">
                            <div class="col-xs-4 col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/whoweare/icon-no-essay-scholarships.png" alt="No Essay Scholarships" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                   	Dedicated Award Inbox
                                </h2>
                                <p>
                                    <small>
                					   We provide each student with a spam-free scholarship inbox to communicate with providers and keep track of their applications. We have advanced filters to protect our students from promotional content.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 item item-artistic-scholarships">
                        <div class="row">
                            <div class="col-xs-4 col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/whoweare/icon-no-essay-scholarships.png" alt="No Essay Scholarships" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Education Center
                                </h2>
                                <p>
                                    <small>
                                        Students can watch videos, read tutorials, see essay and application examples, and find links to a variety of online resources through our education center. Premium members have access to a free eBook that provides a complete guide through the application process.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-xs-12 col-sm-6 item item-international-student">
                        <div class="row">
                            <div class="col-xs-4 col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/whoweare/icon-no-essay-scholarships.png" alt="No Essay Scholarships" class="img-responsive">                        </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Essay Writing Assistance
                                </h2>
                                <p>
                                    Students can see examples of scholarship essays and get editing advice from our experts so they have the best chance at success with their applications. In a recent survey, 75% of respondents confirmed that they would be interested in getting advice for successful essay writing.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How to Get Paid -->
<section role="region" aria-label="How to Get Paid">
    <div class="section--how-to-get-paid clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-container-narrow center-block text-center">
                    <h2 class="h3 text-uppercase text-warning mod-heading">
                        How to Get Paid
                    </h2>
                    <div class="text2">
                        We have several payout models to choose from in our partners program. You could choose to get paid at a certain time each month, or you could set up automated deposits once your earnings reach a pre-determined amount.  Review our flexible payout options in your online account when you sign up today.
                    </div>
		<div class="mod-padding text-uppercase text-center">
		    <h2 style="font-size: 16px">Contact us at <a href="mailto:scholarship@scholarshipowl.com">scholarship@scholarshipowl.com</a></h2>
		</div>

                </div>
            </div>
        </div>
    </div>
</section>


	@include('includes/refer')
@stop
