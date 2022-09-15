@extends('base')

@php $metaData = 'ScholarshipOwl Partners'; @endphp
@section('metatitle')
    <title>{{ $metaData }}</title>
    <meta property="og:title" content="{{ $metaData }}" />
    <meta name="twitter:title" content="{{ $metaData }}" />
@endsection

@section("metatags")
    <meta name="description" content="{{ $metaData }}" />
    <meta name="keyword" content="{{ \CMS::keywords() }}" />
    <meta name="author" content="{{ \CMS::author() }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ $metaData }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
    <meta name="twitter:description" content="{{ $metaData }}" />
@endsection

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('tips') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('partnerships') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('toolTips') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle16') !!}
@endsection

@section('content')

<!-- ScholarshipOwl Partners header -->
<section role="region" aria-labelledby="page-title">
    <div class="top-header clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center text-white">
                    <h1 class="page-title text-light" id="page-title" style="margin: 10px 0 20px 0">
                        ScholarshipOwl Partners
                    </h1>
                    <p class="lead mod-top-header">
                        Why walk when you can soar? At ScholarshipOwl, we'll do everything we can to make your education take flight! That's why we've partnered with some amazing added-value service providers to help you get through college successfully. Check out these exclusive offers to improve your education from start to finish.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ScholarshipOwl Services -->
<section role="region" aria-labelledby="scholarshipowl-services">
    <div id="servicesWeOffer" class="section--services-we-offer paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-justify">
                    <h2 class="h3 mod-text-size text-light text-center" id="scholarshipowl-services">
                        ScholarshipOwl Services
                    </h2>
                    <p class="lead text-center mod-first-child">
                        Don't forget about the spectacular services we offer here at ScholarshipOwl! <br />
                        Sign up today and take advantage of these great tools:
                        <ul class="text-justify">
                            <li>
                                <strong>Hassle-Free Scholarship Applications:</strong> With one simple form, we will have all of the information we need to apply for scholarships for you! Just let us know what you want to apply for, and we'll take care of the rest.
                            </li>
                            <li>
                                <strong>Scholarship Matching and Sourcing:</strong> Get matched with hundreds of scholarships you may qualify for, or search for specific scholarships you're interested in. We'll connect you with the awards you need to graduate debt-free.
                            </li>
                            <li>
                                <strong>Webinars:</strong> Attend special webinars so you can learn live from industry experts here to help you succeed in college.
                            </li>
                            <li>
                                <strong>Essay Writing Tutorials:</strong> Learn what it takes to write a winning scholarship essay through our articles, videos, and online tutoring sessions. Use our editing services to ensure that your essays are clean and error-free.
                            </li>
                            <li>
                                <strong>Spam-Free Dedicated Email Inbox:</strong> Communicate with scholarship providers directly through a spam-free inbox in your ScholarshipOwl account.
                            </li>
                            <li>
                                <strong>Scholarship Management Tools:</strong> Monitor your application status online and watch for upcoming deadlines on the awards you want to apply to.
                            </li>
                            <li>
                                <strong>Education Center:</strong> Get answers to all of your burning questions through our free education center, complete with video tutorials, advice articles, links to online resources, and our exclusive scholarship eBook!
                            </li>
                            <li>
                                <strong>Automatic Recurring Scholarship applications:</strong> Once you sign up for an account with ScholarshipOwl, we will automatically apply you to recurring scholarships you qualify for. You don't have to worry about the deadline. We'll fill out your apps for you to improve your chances of getting money for college.
                            </li>
                        </ul>
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Become a ScholarhisOwl Partner -->
<section role="region" aria-label="Become a ScholarshipOwl Partner">
    <div class="section--become-scholarshipowl-partner lightBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container">
                    <h2 class="h3 mod-text-size text-center text-light">
                        Become a ScholarshipOwl Partner
                    </h2>
                    <p>
                        Have a great service you want to share with our students? Sign up as a ScholarshipOwl partner and generate tons of high-quality traffic for your business. We love connecting deserving students with helpful resources. Fill out the form below to get started today.
                    </p>

                    <div id="become-scholarship-parnter" class="become-partner">
                        <div class="form-wrapper text-left">
                            <legend class="text-center text-light">ScholarshipOwl parter Signup</legend>
                            @include('register/register-form')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@stop
