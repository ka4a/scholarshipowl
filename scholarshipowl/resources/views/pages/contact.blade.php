@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
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
                    <h1 class="h2 text-light" id="page-title">Contact Us</h1>
                    <p class="h4 text-semibold">
                        ScholarshipOwl wants to <span class="linebreak-xxs">hear from you!</span>
                    </p>
                    <p class="lead mod-top-header">
                        <span class="linebreak-md">If you have a comment, question, or issue related to our services, fill out the form </span> <span class="linebreak-md">on this page to reach our customer service team. We typically respond </span> <span class="linebreak-md">to emails within 24-48 hours. Someone will get back to you shortly.</span>
                    </p>
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
                    <h2 class="sr-only">Contact form</h2>
                <form action="/rest/v1/contact-form/contact-page" id="contact-form" class="ajax_form">
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
                                    <label for="email">Your Email</label>
                                    <input type="text" name="email" id="email" class="form-control" placeholder="Email">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-6 col-md-12">
                                <div class="input-group clearfix">
                                    <label for="phone">Your Phone number</label>
                                    <input type="text" name="phone" id="phone" class="form-control" placeholder="Phone">
                                </div>
                            </div>
                            <div class="col-xs-12">
                                <div class="contact-submit">
                                    <input class="btn btn-primary btn-block text-uppercase" id="ContactButton" type="submit" value="Contact us">
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-md-8 col-lg-8 col-md-pull-4 col-lg-pull-4">
                        <div class="message">
                            <div class="form-group">
                                    <label for="content">Message</label>
                                    <textarea name="content" id="content" placeholder="Please tell us what you think" class="form-control"></textarea>
                                </div>
                        </div>
                    </div>



                </form>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Quick Answers to Faqs -->
<section role="region" aria-labelledby="qick-answers">
    <div class="section--faqs paleBlue-bg clearfix">
        <div class="container mod-container">
            <div class="row">
                <div class="text-container mod-text-container">
                    <h2 class="h2 text-light text-center" id="qick-answers">
                        Quick Answers to FAQs
                    </h2>
                    <p class="text-medium text-center">
                    </p>
                    We may already have the answer to your question on our <a href="{{ url_builder('faq') }}">FAQs page</a>. Some of the most popular responses we get from our students include:
                </div>
                <div class="panel-group" id="accordion">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-semibold text-blue">
                                <a data-toggle="collapse" data-parent="#accordion" href="#collapse1">
                                    What makes ScholarshipOwl stand out from other online scholarship databases?
                                    <span class="plus-minus"></span>
                                </a>

                            </h2>
                        </div>
                        <div class="panel-collapse collapse in" id="collapse1">
                            <div class="panel-body">
                                <p>
                                    ScholarshipOwl can apply you to multiple scholarships easily and at once with ONE application. Students are able to submit their information once without having to apply to each scholarship individually. It helps students save time and to focus on their studies
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-semibold text-blue">
                                <a class="text-collapsed collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse2">
                                   Do I have to pay to apply for scholarships?
                                    <span class="plus-minus"></span>
                                </a>

                            </h2>
                        </div>
                        <div class="panel-collapse collapse" id="collapse2">
                            <div class="panel-body">
                                <p>
                                    No, you do not have to pay for a premium membership to apply for scholarships. You will still have access to most of the services we offer at ScholarshipOwl even as a free member. With that in mind, we do offer an extensive amount of bonus services and special offers to our premium members, like unlimited autosubmissions for recurring applications. If you want to take full advantage of the services we offer at ScholarshipOwl, you can upgrade your account at any time.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-semibold text-blue">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse3">
                                   How many scholarships are on this site?
                                    <span class="plus-minus"></span>
                                </a>

                            </h2>
                        </div>
                        <div class="panel-collapse collapse" id="collapse3">
                            <div class="panel-body">
                                <p>
                                    It's impossible to put a number on the scholarships we have in our database. By the time we finish typing out this sentence, several new scholarships are already making their way to students just like you. We have thousands of scholarships in our arsenal, equating to millions of dollars in financial aid.
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h2 class="panel-title text-semibold text-blue">
                                <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapse4">
                                   How can I increase my chances of winning scholarships?
                                    <span class="plus-minus"></span>
                                </a>

                            </h2>
                        </div>
                        <div class="panel-collapse collapse" id="collapse4">
                            <div class="panel-body">
                                <p>
                                    You can't win what you don't apply for. The ultimate way to improve your chances of winning scholarships is to apply to as many of them as possible. ScholarshipOwl makes it easy to apply for the funds you need for college. Fill out one general application, and we will fill out your information on all of your applications. With us doing the work for you, you're sure to boost your chances of winning!
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="button-wrapper">
                        <a href="{{ url_builder('faq') }}" class="btn btn-lg btn-warning btn-block center-block text-uppercase text-center">
                            READ MORE
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Testimonials -->
@include('../includes/testimonials')
	@include('includes/refer')
@stop