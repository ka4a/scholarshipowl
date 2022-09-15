@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('tips') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('advertise') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('affiliates') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('ebook') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- Our promise to you -->
<section role="region" aria-labelledby="page-title">
    <div class="blue-bg clearfix" id="tips_head">
        <div class="container">
            <div class="row">
                <div class="text-container text-center text-white">
                    <h1 class="h2 text-light" id="page-title">
                        What You Get
                    </h1>
                    <p class="lead mod-top-header">
                       ScholarshipOwl is here to make applying for scholarships easier. Register for hundreds of scholarships with just one application, and manage all your applications in one convenient location. Our promise to you is to provide superior scholarship opportunities without redundant form filing.
                    </p>
                    <p class="text-semibold mod-subhead">
                    	We take the hassle out of applying for scholarships!
                    </p>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- See How Easy Scholarship Applications Can Be -->
<section role="region" aria-labelledby="see-how-easy">
    <div class="section--testimonials section--easy-applications">
        <div class="container">
            <div class="row">
                <div class="text-container">
                    <header>
                        <div class="text-center">
                            <h2 class="h4 text-center text-bold" id="see-how-easy">
                                See How Easy Scholarship Applications Can Be
                            </h2>
                            <div class="section--easy-applications-text">
                                <p>
                                    Our one-form application process eliminates the stress of scholarship applications. <span class="linebreak-md">Save time and maximize your award opportunities by letting us do all the work for you.</span> <span class="linebreak-md">A quick glance at <a href="{{ url_builder('register') }}">what we do</a></span>
                                </p>
                            </div>
                        </div>
                    </header>
                    <div class="carousel slide carousel-text mod-instructions" data-ride="carousel">
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
                                    <div>
                                        <p class="text-medium text-uppercase">
                                            Fill out Basic Profile Information
                                        </p>
                                        <p>We'll ask for your name, age, location, and college information <span class="linebreak-sm">so we can match you with as many awards as possible.</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content">
                                    <div>
                                        <p class="text-medium text-uppercase">
                                            Review Your Award Offers
                                        </p>
                                        <p>
                                            With your information on hand, we'll sort through our scholarship database to match you with awards you may qualify for. Review your award offers and select the ones you want to pursue.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content">
                                    <p class="text-medium text-uppercase">
                                        Complete Scholarship-Specific Information
                                    </p>
                                    <p>
                                    	Some scholarships require specific information that isn't needed for other awards. <span class="linebreak-sm">In this case, we will ask for extra details from you to complete your applications.</span>
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content">
                                    <p class="text-medium text-uppercase">
                                        Let Us Fill out the Forms
                                    </p>
                                    <p>
                                    	Once we have all the info we need, we will fill out your application forms for you. For recurring scholarships that come up multiple times throughout the year, we will automatically submit your applications so you never have to worry about the deadlines.
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content">
                                    <p class="text-medium text-uppercase">
                                        Monitor Your Applications Online
                                    </p>
                                    <p>
                                    	Use our scholarship management tools and dedicated application inbox <span class="linebreak-sm">to keep track of your awards online.</span>
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content">
                                    <p class="text-medium text-uppercase">
                                        Review New Award Opportunities
                                    </p>
                                    <p>
                                    	Our scholarship database is growing every month, so you'll always have new awards to look forward to. Check out all of the awesome scholarships we match you to and apply to the ones you like most.
                                    </p>
                                </div>
                            </div>
        				</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- We Are Committed to Your Success -->
<section role="region" aria-labelledby="commited-to-your-success">
    <div class="section--commited-to-success paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center">
                    <h2 class="h2 mod-text-size text-blue" id="commited-to-your-success">
                        We Are Committed to Your Success
                    </h2>
                    <p class="lead">
                        At ScholarshipOwl, we have one goal in mind: helping hard-working students get the financial aid they deserve. We are truly committed to your success. Here are just some of the awesome services we offer to put you on the fast track to a <strong>debt-free degree:</strong>
                    </p>

                    <div class="text-container center-block">
                        <h2>Read Stories from Past Winners</h2>
                        <p class="divider-dashed">
                            Learn how to compare costs, admissions requirements, degree options, and more to pick the right school for you.
                        </p>

                        <h2>Save Time on Applications</h2>
                        <p class="divider-dashed">
                        	Why should you waste hours a day filling out the same information on every scholarship application? With ScholarshipOwl, you complete one form, one time, and we take care of everything else. We will only ask for more information if the scholarship has a specific essay or prompt you need to address. In most cases though, we'll already have that covered!
                        </p>
                        <h2>Manage All Your Scholarships in One Place</h2>
                        <p class="divider-dashed">
                            Our convenient scholarship management tools make it easy to keep track of your financial aid. See how much money you've earned, watch for upcoming scholarship deadlines, see new award options, and keep track of existing applications through your ScholarshipOwl account.
                        </p>
                        <h2>Attend Special Webinars from Industry Experts</h2>
                        <p class="divider-dashed">
                            As part of our education center, we offer scholarship webinars you can attend for extra information. We also offer webinars on career planning, college selection, and more to improve your overall experience in college. If you are not able to attend the webinars live, we have additional video resources that you can review any time.
                        </p>
                        <h2>Learn What Scholarship Committees Really Expect</h2>
                        <p class="divider-dashed">
                            We have a direct relationship with some of the scholarship providers in our database. That means that we have insight into the exact qualities they want to see out of their applicants. We do our best to pass this vital information onto you so you can make your essays and applications as a whole appealing to your awarders.
                        </p>
                        <h2>Get Essay Writing Advice for Outstanding Applications</h2>
                        <p class="divider-dashed">
                            The best way to impress a scholarship committee is to blow them away with a killer essay. We have a vast assortment of essay writing tools, tips, and tutorials that you can use to make your application stand out in the pile. You can also take advantage of our editing services and personal essay coaching to ensure your apps reflect your true talents.
                        </p>
                        <h2>Communicate through a Dedicated Application Inbox</h2>
                        <p class="divider-dashed">
                            You won't have to worry about spam and promotional mail flooding your personal inbox. We offer a dedicated application inbox for scholarships, with advanced filtering to keep the junk out of sight. Use this inbox to communicate with scholarship committees and work with our knowledgeable advisors to boost your applications across the board.
                        </p>
                        <h2>Automatically Apply to Recurring Scholarships</h2>
                        <p class="divider-dashed">
                            When scholarships come up more than once during your active membership, we can automatically send out your information after the renewal. That means that you won't have to monitor the deadlines and watch for the scholarship to come up again. We take care of that for you. Just because you don't win the first time doesn't mean you won't win at all.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Birds of a Feather…Win Together! -->
<section role="region" aria-labelledby="win-together">
    <div class="lightBlue-bg clearfix">
        <div class="container center-block">
            <div class="row">
                <div class="text-container">
    				<div class="col-xs-12 text-center">
                        <h2 class="h4 text-semibold" id="win-together">Birds of a Feather…Win Together!</h2>
                          <p>Sign up for ScholarshipOwl and see just how easy scholarship applications can really be. We have the tools, advice, and expertise you need to find financial aid for college. We are committed to helping every college student get access to free support for their degrees. Graduate debt-free with a little help from an adorable little owl.</p>
    				</div>
                    <div class="col-xs-12 col-md-offset-4 col-md-4">
                        <div class="button-wrapper">
                            <a href="{{ url_builder('register') }}" class="btn btn-lg btn-warning btn-block center-block text-uppercase text-center">
            					Register
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
