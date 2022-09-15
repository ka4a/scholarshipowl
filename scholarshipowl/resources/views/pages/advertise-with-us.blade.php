@extends('base')

@php $metaData = 'Advertise Your Award through ScholarshipOwl'; @endphp
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
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- Advertise Your Award through ScholarshipOwl head -->
<section role="region" aria-label="page-title">
    <div class="blue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center text-white">
                    <h1 class="h2 text-light" id="page-title">
                        Advertise Your Award through ScholarshipOwl
                    </h1>
                    <p class="lead mod-top-header">
                        Want to share your scholarship opportunity with <strong>thousands of deserving students</strong> from around the world? We'll help you do just that! With Scholarship Owl's award matching services, you can get paired with qualified applicants actively looking for your scholarship.
                    </p>
                    <div class="button-wrapper" id="advertise">
                        <a href="#" class="btn btn-lg btn-warning btn-block center-block text-uppercase text-center">
                            Advertise Now
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Advertise Your Award through ScholarshipOwl -->
<section role="region" aria-labelledby="advertise-your-award">
    <div class="section--advertise-your-award paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-container-narrow center-block text-center clearfix">
                    <h2 class="sr-only" id="advertise-your-award">Advertise Your Award through ScholarshipOwl</h2>

                    <h2 class="mod-heading">
                        What You Get from Our Scholarship Promotions
                    </h2>
                    <p class="divider-dashed">
                        Why should you advertise your scholarship with Scholarship Owl? Because we offer maximum exposure with minimal work on your end. With our promotional services, you can…
                    </p>

                    <h2 class="mod-heading">
                        Get Matched with Qualified Applicants
                    </h2>
                    <p class="divider-dashed">
                        We make it easy for you to sort through scholarship applicants with our scholarship matching program. We'll help you connect with college students who meet your specific application requirements so you can find the perfect winner quickly and easily. With new students signing up every day, you always have new applicants to send your way!
                    </p>

                    <h2 class="mod-heading">Generate Leads for Your Organization</h2>
                    <p class="divider-dashed">
                        If you are using your scholarship offer as a promotional tool for your business or organization, you are sure to get leads through Scholarship Owl! We have thousands of active users who log on every week to check for new scholarship options. Once you're in our database, they will be able to find you.
                    </p>
                    <h2 class="mod-heading">Expand Your Pool of Applicants</h2>
                    <p class="divider-dashed">
                        Our scholarship search tools help students find the awards they're looking for. Your scholarship may be a perfect fit! Scholarship Owl offers hassle-free scholarship applications that help students apply for a large number of awards with one simple application. This gives you a greater chance of getting a large pool of eligible applicants to sort through.
                    </p>
                    <h2 class="mod-heading">Build Relationships with Your Applicants</h2>
                    <p class="divider-dashed">
                        Every student who signs up for Scholarship Owl gets a dedicated scholarship inbox. This spam-free messaging system provides award reminders, important notifications, and email opportunities for students to reach out to scholarship committees directly. You can communicate with possible winners to conduct online interviews so you choose the perfect recipients for your awards.
                    </p>
                    <h2 class="mod-heading">Renewal Options with Automatic Applications</h2>
                    <p class="divider-dashed">
                        If you would like to offer the same scholarship several times throughout the year, you can create a recurring scholarship in our database that will automatically renew when you want it to. Qualified applicants who have already applied to your award will immediately be sent your way, so you'll instantly have students to consider for your award. If you struggle to pick just one winner the first time around, this will give you the opportunity to help other worthy applicants you missed out on before.
                    </p>
                    <h2 class="mod-heading">Start Your Scholarship Listing</h2>
                    <p class="divider-dashed">
                        We make it easy for scholarship committees to promote their awards. Start your scholarship listing today and discover just how many deserving students are actively searching for your award. With Scholarship Owl, you're sure to find the perfect fit!
                    </p>
                    <div class="col-xs-12 col-md-offset-3 col-md-6">
                        <div class="button-wrapper">
                            <a class="btn btn-lg btn-warning btn-block center-block text-uppercase text-center" href="{{ url_builder('register') }}">
                                List Your Scholarship
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
