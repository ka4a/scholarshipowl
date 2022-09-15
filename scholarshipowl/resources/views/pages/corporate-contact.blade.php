@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('faq') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('contact') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('testimonials') !!}
    {!! HTML::script('assets/plugins/inputmask/jquery.inputmask.bundle.min.js') !!}
@endsection

@section('content')



<section role="region" aria-labelledby="Contact">
    <div id="contact-us" class="blue-bg">
        <div class="container center-block">
            <div class="row">
                <div class="text-container text-center text-white">
                    <h1 class="h2 text-light" id="page-title">Corporate Contact ScholarshipOwl</h1>
                </div>
            </div>
        </div>
    </div>
</section>

<section role="region" aria-labelledby="contact-form">
    <div id="contact" class="contact-form">
        <div class="container">
            <div class="row">
                <div class="text-container clearfix">
                    <div class="col-sm-12 col-md-6">
                        <h2><span>{{ company_details()->getCompanyName() }}</span></h2>
                        <p>{!! setting("content.address") !!}</p>
                        <p>Call us: {!! setting("content.phone") !!}</p>
                        <p>Email: <a href="mailto:contact@scholarshipowl.com">contact@scholarshipowl.com</a> </p>
                        <p><a href="http://mlt.bizdirlib.com/node/23903">Verify corporate entity of <span>{{ company_details()->getCompanyName() }}</span> here</a> </p>
                        <p><span>{{ company_details()->getCompanyName() }}</span> is a registered company in Malta</p>
                    </div>
                    <div class="col-sm-12 col-md-6 sas-partner">
                        <h2>US distribution franchisee</h2>
                        <p>{{ company_details()->getAddress1() }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
@include('includes.testimonials')
	@include('includes/refer')

@stop

@section('footer')
    @include('includes/footer-contact')
@endsection
