@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('testimonials') !!}
@endsection

@section('content')

<!-- See What "Owl" the Fuss Is about -->
<section role="region" aria-labelledby="page-title">
    <div class="section--additional-services-head blue-bg clearfix" id="additional-services-head">
    	<div class="container">
    		<div class="row">
    			<div class="text-container text-center text-white">

    				<h1 class="h2 text-light" id="page-title">
                        See What "Owl" the Fuss is About
                    </h1>
    				<p class="lead mod-top-header">
    					What do other people have to say about ScholarshipOwl? Good stuff, of course! We love hearing reviews from the awesome people that put us on the hunt for more scholarship opportunities. We use their feedback to constantly improve our program so you have the best chance at success.
    				</p>
                    <p class="mod-subhead">
                        83% of ScholarshipOwl users say being automatically applied to multiple scholarships is a huge advantage.
                    </p>
                    <p class="blue-bg-cta">
                        <a href="/press" class="btn btn-lg btn-primary">
                            ScholarshipOwl in the press
                        </a>
                    </p>
    			</div>
    		</div>
    	</div>
    </div>
</section>


<!-- Testimonials -->
@include('../includes/testimonials')

<section role="region" aria-labelledby="we-care">
    <div class="section--we-care-what-you-say paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-container-narrow center-block text-center">
                    <h2 class="h3 text-bold" id="we-care">
                    	We Care What You Have to Say
                    </h2>

                    <p>
                        At ScholarshipOwl, our goal is to serve our students in the best way possible, no matter what. We care about your opinion, especially if it gives us a chance to make improvements for the future. Your feedback allows us to…
                    </p>

                    <ul class="text-left mod-list">
                      <li>Provide a Better Experience for Every ScholarshipOwlet (That's You!)</li>
                      <li>Improve Our Scholarship Options and Enhance Our Application Process</li>
                      <li>Identify Our Shortcomings and Fix Them Early on</li>
                      <li>Help Scholarship Applicants in the Best Way Possible</li>
                      <li>Keep Our Program Easy to Use from Start to Finish</li>
                      <li>Expand the Nest to Help Other Students Pay for College, Just Like You!</li>
                    </ul>

                    <p>
                        <a href="{{ url_builder('contact') }}" class="">Contact us</a> at any time to tell us what you love about ScholarshipOwl, or give us tips on how we can make our program even more helpful. We are constantly looking for new ways to enrich our scholarship process and generate more funding for students across the country. Reach out today so we can make your experience the best one possible.
                    </p>
                    <p class="semibold">
                        <big>
                            83% of ScholarshipOwl users say being automatically applied to <span class="linebreak-md">multiple scholarships is a huge advantage.</span>
                        </big>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

@include('includes/refer')
@stop
