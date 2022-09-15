@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('additionalServices') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('affiliates') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('partnerships') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle18') !!}
@endsection

@section('content')

<!-- Want to List on ScholarshipOwl? -->
<section role="region" aria-labelledby="page-title">
    <div class="list-your-scholarship-header blue-bg clearfix">
        <div class="container">
            <div class="row">
              <div class="text-container text-center text-white">
                <h1 class="h2 text-light" id="page-title">List Your Scholarship on ScholarshipOwl</h1>
                    <p class="lead mod-top-header">
                        Get massive exposure for your scholarship on one of the largest platforms online. We have thousands of students waiting to apply for awards just like yours. Promote your organization and help deserving applicants get the funding they need for college. It's a win-win solution!
                    </p>
                    <p class="mod-subhead">
                        FACT: 93% of users say they'd <em class="text-small-caps text-bold">definitely</em> refer their friends to ScholarshipOwl! Think of what that means for your scholarship…
                    </p>
              </div>
          </div>
        </div>
    </div>
</section>

<!-- How to Get Your Scholarship in Our Database -->
<section id="list_form" role="region" aria-labelledby="how-to-get-in-our-db">
    <div class="section--your-scholarship-our-database">
        <div class="container">
            <div class="row">
                <div class="text-container mod-text-container">
                    <h2 class="text-light text-center text_37" id="how-to-get-in-our-db">
                        How to Get Your Scholarship in Our Database
                    </h2>
                    <p class="lead text-center mod-text">
                        Listing your scholarship on ScholarshipOwl is fairly simple. Complete a short form on our website, and we will start promoting your award as soon as possible.
                    </p>
                </div>
                <div class="scholarship-listing-wrapper">
                    <div class="section--testimonials section--carousel-listing-form">
                        <div class="carousel carousel-text mod-instructions slide" data-ride="carousel">
                            <!-- Indicators -->
                            <ol class="carousel-indicators">
                                <li data-target=".carousel-text" data-slide-to="0" class="active">&#10148;</li>
                                <li data-target=".carousel-text" data-slide-to="1">&#10148;</li>
                                <li data-target=".carousel-text" data-slide-to="2">&#10148;</li>
                                <li data-target=".carousel-text" data-slide-to="3">&#10148;</li>
                                <li data-target=".carousel-text" data-slide-to="4">&#10148;</li>
                                <li data-target=".carousel-text" data-slide-to="5">&#10148;</li>
                             </ol>

                            <!-- Wrapper for slides -->
                            <div class="carousel-inner text-center">
                                <div class="item active">
                                    <div class="carousel-content">
                                        <p>
                                            Complete the form below including a description of your award, application requirements, and any special questions you would like applicants to respond to.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="carousel-content">
                                        <p>
                                            One of our scholarship advisers will be in touch within 48 hours to follow up and clarify any unclear information in the listing.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="carousel-content">
                                        <p>
                                            We will promote your award in the ScholarshipOwl listings so qualified applicants can reach out to you right away.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="carousel-content">
                                        <p>
                                            Watch as the applications start pouring in!
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="carousel-content">
                                        <p>
                                            Review the applications shortly after your deadline and select your winners.
                                        </p>
                                    </div>
                                </div>
                                <div class="item">
                                    <div class="carousel-content">
                                        <p>
                                            ScholarshipOwl contacts the recipients to congratulate them on their <strong>big win!</strong>
                                        </p>
                                    </div>
                                </div>
            				</div>
                        </div>
                    </div>
                    <div id="scholarship-listing-form" class="scholarship-listing-form">
                        <div class="form-wrapper text-left">
                            <section role="region" aria-labelledby="contact-form">
                                <div id="contact" class="contact-form">
                                    <div class="row">
                                        <div class="clearfix">
                                            <h2 class="sr-only">Contact form</h2>
                                        <form action="post-list-scholarship" id="contact-form" class="ajax_form">
                                            {{ Form::token() }}

                                            <div class="col-xs-12 col-md-4 col-lg-4 col-md-push-8 col-lg-push-8">
                                                <div class="row">
                                                    <div class="col-xs-12">
                                                        <div id="form-msg"></div>
                                                        <div class="input-group clearfix">
                                                            <label for="name">Your Name</label>
                                                            <input type="text" name="name" id="name" class="form-control" placeholder="Name">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-12">
                                                        <div class="input-group clearfix">
                                                            <label for="email">You Email</label>
                                                            <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12 col-sm-6 col-md-12">
                                                        <div class="input-group clearfix">
                                                            <label for="phone">You Phone number</label>
                                                            <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">
                                                        </div>
                                                    </div>
                                                    <div class="col-xs-12">
                                                        <div class="contact-submit">
                                                            <input class="btn btn-primary btn-block text-uppercase" id="ContactButton" type="submit" value="Get More Info">
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <div class="col-xs-12 col-md-8 col-lg-8 col-md-pull-4 col-lg-pull-4 message">
                                                <div class="form-group">
                                                    <label for="content">Message</label>
                                                    <textarea name="content" id="content" placeholder="Short message about you and your scholarship" class="form-control"></textarea>
                                                </div>
                                            </div>
                                        </form>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scholarship Listing Tips -->
<section role="region" aria-labelledby="scholarship-listing-tips">
    <div id="scholarship-listing-tips" class="section--scholarship-listing-tips lightBlue-bg clearfix">
        <div class="container center-block">
            <div class="row">
                <div class="text-container clearfix">
                    <header>
                        <div class="scholarship-listing-tips-header text-center">
                            <h2 class="h2 mod-text-size text-light text-blue" id="scholarship-listing-tips">
                                Scholarship Listing Tips
                            </h2>
                            <p class="lead">
                                We want you to get the most exposure possible. <br />Follow the tips below to maximize the attention your scholarship gets.
                            </p>
                        </div>
                    </header>

                    <div class="col-xs-12 col-sm-6 listing-tips item-merit">
                        <div class="row">
                            <div class="col-xs-4 mod-col-xs col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/list-your-scholarship/renewable_ico.png" alt="Make Your Scholarship Renewable" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Make Your Scholarship Renewable
                                </h2>
                                <p>
                                    <small>
                                        Premium members on Scholarship Owl get the benefits of our autosubmission system, which automatically applies them to renewable scholarships. If you plan on offering awards every semester or even every month, making your award renewable will maximize your applicants and keep people interested in your organization.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 listing-tips item-no-essay-scholarships">

                        <div class="row">
                            <div class="col-xs-4 mod-col-xs col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/list-your-scholarship/requirements_ico.png" alt="Choose Your Requirements Wisely" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Choose Your Requirements Wisely
                                </h2>
                                <p>
                                    <small>
                                        Think about what really matters most in your winning applicant. Good grades and perfect school attendance may not be the most important requirements to look for. Don't make your listing so specific that no one applies. Choose the values that best reflect your organization as a whole, and use those to guide your selection process.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-xs-12 col-sm-6 listing-tips item-need-based-scholarships">
                        <div class="row">
                            <div class="col-xs-4 mod-col-xs col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/list-your-scholarship/goal_ico.png" alt="Explain Your Organization's Goals" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Explain Your Organization's Goals
                                </h2>
                                <p>
                                    <small>
                                        In your listing, briefly describe your organization's core values so applicants can tailor their essays to meet your needs. This will help students write more effective, powerful essays that will speak to you directly. It will also ensure that the perfect candidates apply each and every time.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 listing-tips item-athletic-scholarships">
                        <div class="row">
                            <div class="col-xs-4 mod-col-xs col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/list-your-scholarship/deadline_ico.png" alt="Pick a Good Deadline" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                  Pick a Good Deadline
                                </h2>
                                <p>
                                    <small>
                                        Students often look for scholarships toward the end of a school semester as they begin preparing for the months to come. Set your scholarship deadlines in April-March or November-December to get the most applicants possible. Keep your listings up for several months so you can reach out to as many candidates as you can.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="col-xs-12 col-sm-6 listing-tips item-minority-scholarships">
                        <div class="row">
                            <div class="col-xs-4 mod-col-xs col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/list-your-scholarship/listing_ico.png" alt="Keep Your Listings Concise" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                   	Keep Your Listings Concise
                                </h2>
                                <p>
                                    <small>
                					   College students have to read pages of information every single day. When it comes time to apply for scholarships, they just want to scan through the options and find the ones that work best for them. Keep your scholarship listings concise so applicants can scan and assess them quickly.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-sm-6 listing-tips item-artistic-scholarships">
                        <div class="row">
                            <div class="col-xs-4 mod-col-xs col-md-3">
                                <figure class="pull-right">
                                    <img src="assets/img/list-your-scholarship/essay_ico.png" alt="Don't Be Afraid of Essays!" class="img-responsive">
                                </figure>
                            </div>
                            <div class="col-xs-8 col-md-9">
                                <h2 class="h5 mod-text-size text-blue text-semibold">
                                    Don't Be Afraid of Essays!
                                </h2>
                                <p>
                                    <small>
                                        In a recent Scholarship Owl survey, 80% of respondents said they would definitely send more essays if it helped them apply to more scholarships. As long as the funding is available, people will willingly write essays to earn it. Don't be afraid to create a custom essay prompt if it helps you identify the best candidate for your award.
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-offset-4 col-md-4">
                        <div class="button-wrapper">
                            <a data-scroll id="scroll_to_cont_form" class="btn btn-lg btn-warning btn-block center-block text-uppercase text-center" href="#how-to-get-in-our-db">
                                List Now
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
