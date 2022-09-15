@extends('layouts.front')

@section('meta-title', "Sitemap | Apply.me")

@section('meta-description', "")

@section('page-name', 'page-sitemap')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <h1 class="Banner__title text-center">Sitemap</h1>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Sitemap</li>
    </ol>

    <section class="Section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <ul>
                        <li><a href="{{ route('front.index') }}">Home</a></li>
                        <li><a href="">Features</a></li>
                        <li>
                            <ul class="Util--padding-left-small">
                                <li><a href="{{ route('front.features.courses') }}">Courses</a></li>
                                <li><a href="{{ route('front.features.admissions-coaching') }}">Admissions Coaching</a></li>
                                <li><a href="{{ route('front.features.essay-assistance') }}">Essay Assistance</a></li>
                                <li><a href="{{ route('front.features.interview-preparation') }}">Interview Preparation</a></li>
                                <li><a href="{{ route('front.features.personalized-scholarships-list') }}">Personalized Scholarships List</a></li>
                                <li><a href="{{ route('front.features.guidance-for-parents') }}">Guidance for Parents</a></li>
                            </ul>
                        </li>
                        <li><a href="{{ route('front.pricing') }}">Pricing</a></li>
                        <li><a href="{{ route('front.faq') }}">FAQ</a></li>
                        <li><a href="{{ route('front.about-us') }}">About Us</a></li>
                        <li><a href="{{ route('front.contact.get') }}">Contact Us</a></li>
                        <li><a href="{{ route('front.terms-of-use') }}">Terms of Use</a></li>
                        <li><a href="{{ route('front.privacy-policy') }}">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

@stop
