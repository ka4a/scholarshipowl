@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- Scholarships Just Got a Hooo-le Lot Easier header -->
<section role="region" aria-labelledby="page-title">
    <div class="blue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center text-white">
                    <h1 class="h2 text-light" id="page-title">
                        About ScholarshipOwl
                    </h1>
                    <p class="lead mod-top-header">
                        ScholarshipOwl is your source of relief from the dreaded application process. We put students on the fast-track to success by providing direct access to the scholarships they need the most. Take the hassle out of financial aid and maximize your chance at a free ride in college. No matter how much funding you need for school, we have the opportunities you're looking for.
                    </p>
                    <p class="mod-subhead">
                        <a href="{{ url_builder('register') }}">Fill out a single form and let us take care of the rest!</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- What Is ScholarshipOwl -->
<section role="region" aria-labelledby="what-is-sowl">
    <div class="section--what-is-schowl paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center mod-text-container">
                    <h2 class="h3" id="what-is-sowl">
                        What Is ScholarshipOwl?
                    </h2>
                    <p>
                        ScholarshipOwl is an innovative platform designed to dramatically speed up your scholarship application time. We'll match you with the award opportunities that best match your personality, grades, lifestyle, and more. Once you've narrowed down your options, we’ll take the information from your initial application and put it into the appropriate places on the scholarships of your choice.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Top 10 Reasons to Use ScholarshipOwl -->
<section role="region" aria-labelledby="top-10-reasons">
<div class="section--top-10-reasons lightBlue-bg clearfix">
    <div class="container center-block">
        <div class="row">

            <div class="text-container clearfix">

                <header>
                    <div class="col-xs-12 section--top-10-reasons-header text-center">
                        <h2 class="h3 mod-text-size text-light" id="top-10-reasons">
                            Top 10 Reasons to Use ScholarshipOwl
                        </h2>
                        <p class="lead">
                            Want to know why many people love ScholarshipOwl? <span class="linebreak-sm">Here are 10 reasons you can't deny:</span>
                        </p>
                    </div>
                </header>


                <div class="col-xs-12 col-sm-6 item item-less-work">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-less-work pull-right" alt="Less Work" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                1 – Less Work
                            </h2>
                            <p>
                                <small>
                                    All you fill out is a basic template of information. We do all the rest. If you can apply for a library card, you can apply for financial aid through ScholarshipOwl.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-more-money">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-more-money pull-right" alt="More Money" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                2 – More Money
                            </h2>
                            <p>
                                <small>
                                    With scholarship applications this easy, you can dramatically increase the number of applications you send out. That will ultimately lead to more money opportunities to pay for college.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-spam-protection">
                    <div class="row">
                    <div class="col-xs-4 col-md-3">
                        <figure>
                            <img class="sprite-responsive--icon-spam-protection pull-right" alt="Spam protection" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                        </figure>
                    </div>
                    <div class="col-xs-8 col-md-9">
                    	<h2 class="h5 mod-text-size text-semibold text-blue">
                            3 – Spam Protection
                        </h2>
                        <p>
                            <small>
                                We provide provide news and updates regularly for your scholarships in ScholarshipOwl members section.
                            </small>
                        </p>
                        <p>
                            <small>
                        	   All updates and emails from scholarships are shown here so we can shield your personal email address and protect you from unwanted messages.
                            </small>
        				</p>
                    </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-decreased-stress">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-decreased-stress pull-right" alt="Decreased Stress" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                4 – Decreased Stress
                            </h2>
                            <p>
                                <small>
                                    We make the scholarship application process as simple as it can be. Avoid the stress of filling out application after application of information and focus on more important things in life.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-better-organization">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-better-org pull-right" alt="Better Organization" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                               	5 – Better Organization
                            </h2>
                            <p>
                                <small>
                                    All your scholarship applications are stored in one place, so you can check up on them all at once. View the awards that are in review, approved, declined, or pending, all without ever leaving the computer.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-improved-security">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-improved-security pull-right" alt="Improved Security" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                6 – Improved Security
                            </h2>
                            <p>
                                <small>
                                    If you are worried about scholarship scams, you can trust that all of the scholarships on ScholarshipOwl are 100% authentic. We send all of these awards through security screenings ahead of time, so you can feel comfortable putting your information out on the web.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-similar-awards">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-similar-awards pull-right" alt="Similar Awards" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                7 – Similar Awards
                            </h2>
                            <p>
                                <small>
                                    Maximize your earning potential by applying for similar awards at the same time. Since we do all the work for you, you simply need to check which awards you want and let us take care of the rest.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-expert-advice">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-expert-advice pull-right" alt="Expert Advice" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                8 – Expert Advice
                            </h2>
                            <p>
                                <small>
                                    Get help from the scholarship consultants at ScholarshipOwl to improve your chances at getting funding for school. Our experts are here at all times to assist you.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-friendly-service">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-friendly-service pull-right" alt="Friendly Service" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                            	9 – Friendly Service
                            </h2>
                            <p>
                                <small>
                                    We take pride in helping students succeed. Every approved application is another indication that we're doing something right. This pride is reflected in our superior customer service and true dedication to our users.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-amusing-mascot">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure>
                                <img class="sprite-responsive--icon-amusing-mascot pull-right" alt="Amusing Mascot" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAG0AAABmAQMAAAD78Y3WAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABRJREFUeNpjYBgFo2AUjIJRMLIAAAX6AAGTYaffAAAAAElFTkSuQmCC">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                10 – Amusing Mascot
                            </h2>
                            <p>
                                <small>
                                    How can you say no to an adorable face like that?! Let the Scholarship Owl charm you into a slew of successful scholarship applications, and you can graduate debt free.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </div>
</div>
</section>

<!-- We do all the work for you -->
<section role="region" aria-labelledby="we-do-all-the-work">
    <div class="section--all-for-you clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center">
                    <h2 class="section--all-for-you-heading text-light mod-heading" id="we-do-all-the-work">
                        We do all the work for you
                    </h2>
                    <div class="text-medium">
                        Our goal is to help you get the financing you're looking for, no matter what it takes.
                        <span class="linebreak-md">We want you to <strong>graduate debt free</strong>.</span>
                    </div>
                    <div id="sign-up-btn" class="button-wrapper mod-button-wrapper">
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

<!-- Learn About Us -->
<section role="region" aria-labelledby="learn-about-us">
    <div class="section--learn-more-about-us paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center mod-text-container">
                    <h2 class="h4 text-bold" id="learn-about-us">
                        Learn More about Us
                    </h2>
                    <p>
                        Want to know more about who we are, what we do, and how we can help you succeed? Contact us today and speak to one of our friendly customer service experts. We will explain the process from start to finish so you can see the benefits of using ScholarshipOwl.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

	@include('includes/refer')
@stop
