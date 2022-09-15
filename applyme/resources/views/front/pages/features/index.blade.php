@extends('layouts.front')

@section('meta-title', "Features | Apply.me")

@section('meta-description', "Your higher education journey starts here! Enrich and improve your college goals by accessing the resources you need to succeed.")

@section('page-name', 'page-features')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="/imgs/icons/features-banner.png" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Features</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        Your higher education journey starts here!<br class="hidden-xs"> Enrich
                        and improve your college goals by accessing the
                        resources you need to succeed.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Features</li>
    </ol>

    @include('front.components.bloc._how-can-we-help')

    <section class="Section Section--light-primary Featured-courses Util--padding-top-none">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h2 class="Section__main-title text-center Util--text-primary">Featured Courses</h2>
                </div>
            </div>
            <hr class="Util--spacer-trans-small hidden-xs">
            <div class="row">
                <div class="col-xs-12 col-sm-4">
                    <div class="panel panel-default College-preparation-program">
                        <a href="https://applymeacademy.teachable.com/p/college-preparation-program">
                            <div class="panel-heading text-center">
                                <img src="/imgs/features/college-preparation-program.png" alt="" height="128">
                            </div>
                        </a>
                        <div class="panel-body">
                            <h4 class="text-center">
                                <a href="https://applymeacademy.teachable.com/p/college-preparation-program">College Preparation Program</a>
                            </h4>
                            <p class="text-center small text-muted">Comprehensive online course for high school students who are preparing for college.</p>
                            <hr class="Util--spacer-trans-micro">
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-xs-8">
                                    <img src="/imgs/photos/jordan-schanda-micro.jpg" alt="">
                                    <span class="small">Jordan Schanda</span>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="https://applymeacademy.teachable.com/p/college-preparation-program">$279</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4">
                    <div class="panel panel-default Scholarships-101">
                        <a href="https://applymeacademy.teachable.com/p/scholarships-101">
                            <div class="panel-heading text-center">
                                <img src="/imgs/features/scholarships-101.png" alt="" height="128">
                            </div>
                        </a>
                        <div class="panel-body">
                            <h4 class="text-center">
                                <a href="https://applymeacademy.teachable.com/p/scholarships-101">Scholarships 101</a>
                            </h4>
                            <p class="text-center small text-muted">Online course designed for both college and high school students seeking scholarships.</p>
                            <hr class="Util--spacer-trans-micro">
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-xs-8">
                                    <img src="/imgs/photos/luis-trujillo-micro.jpg" alt="">
                                    <span class="small">Luis Trujillo</span>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="https://applymeacademy.teachable.com/p/scholarships-101">$279</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-4">
                    <div class="panel panel-default College-planning-for-parents">
                        <a href="https://applymeacademy.teachable.com/p/college-planning-for-parents-mini-course">
                            <div class="panel-heading text-center">
                                <img src="/imgs/features/college-planning-for-parents.png" alt="" height="128">
                            </div>
                        </a>
                        <div class="panel-body">
                            <h4 class="text-center">
                                <a href="https://applymeacademy.teachable.com/p/college-planning-for-parents-mini-course">College Planning for Parents</a>
                            </h4>
                            <p class="text-center small text-muted">Help your child and find out what no one tells you about the college planning process.</p>
                            <hr class="Util--spacer-trans-micro">
                        </div>
                        <div class="panel-footer">
                            <div class="row">
                                <div class="col-xs-8">
                                    <img src="/imgs/photos/jordan-schanda-micro.jpg" alt="">
                                    <span class="small">Jordan Schanda</span>
                                </div>
                                <div class="col-xs-4 text-center">
                                    <a href="https://applymeacademy.teachable.com/p/college-planning-for-parents-mini-course">$49</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <h2 class="text-center Util--text-primary">What We Do</h2>
    <hr class="Util--spacer-trans-small">

    <section class="Section Section--light-secondary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">College Preparation Courses</h3>
                    <p class="Util--text-dark-secondary">
                        Our comprehensive online courses teach students how to
                        create an ideal system for staying organized and on top
                        of application deadlines. Get the inside scoop on how to
                        create an application that stands out, and learn tips
                        and strategies that set you apart from the other
                        applicants.
                    </p>
                    <p><a href="{{ route('front.features.courses') }}">See more &rarr;</a></p>
                </div>
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm">
                    <p class="text-center"><img src="/imgs/icons/courses-lg.png" alt=""></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/HplRXdvyFeI/?rel=0&amp;modestbranding=1"></iframe>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">College Admissions Coaching</h3>
                    <p class="Util--text-dark-secondary">
                        Work with an experienced college admissions counselor
                        and get answers to all your college and
                        scholarship-related questions. School selection,
                        college guidance and application management are all part
                        of the one-on-one help and support you will receive.
                        You’ll get personalized attention from a counselor,
                        and be able to access the tools you need to succeed
                        throughout the application and college decision process.
                    </p>
                    <p><a href="{{ route('front.features.admissions-coaching') }}">See more &rarr;</a></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-secondary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">Essay Assistance</h3>
                    <p class="Util--text-dark-secondary">
                        We have a vast assortment of essay writing tools, tips,
                        and tutorials to help your application stand out. Use
                        our editing services and personal essay coaching to
                        ensure your applications reflect your true talents and
                        potential.
                    </p>
                    <p><a href="{{ route('front.features.essay-assistance') }}">See more &rarr;</a></p>
                </div>
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm">
                    <p class="text-center"><img src="/imgs/icons/essay-assistance-lg.png" alt=""></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm">
                    <p class="text-center"><img src="/imgs/icons/interview-preparation-lg.png" alt=""></p>
                </div>
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">Interview Preparation</h3>
                    <p class="Util--text-dark-secondary">
                        A bad interview performance can hurt your chances of
                        getting into your dream college. A little bit of
                        preparation goes a long way. We help students become
                        well-equipped for their upcoming college admissions
                        interviews and help them gain important skills that will
                        benefit their academic and professional development.
                    </p>
                    <p><a href="{{ route('front.features.interview-preparation') }}">See more &rarr;</a></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-secondary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">Personalized Scholarships List</h3>
                    <p class="Util--text-dark-secondary Util--text-secondary">
                        The scholarship search process is extremely time
                        consuming. If you’re tired of feeling like you're on a
                        wild goose chase for scholarships, we’ll create a list
                        just for you, based on your specific skills, qualities,
                        hobbies, talents and characteristics.
                    </p>
                    <p><a href="{{ route('front.features.personalized-scholarships-list') }}">See more &rarr;</a></p>
                </div>
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm">
                    <p class="text-center"><img src="/imgs/icons/personalized-scholarships-list-lg.png" alt=""></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm">
                    <p class="text-center"><img src="/imgs/icons/guidance-for-parents-lg.png" alt=""></p>
                </div>
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">Guidance for Parents</h3>
                    <p class="Util--text-dark-secondary">
                        Sometimes students lack the motivation or organizational
                        skills to navigate the college application and admissions
                        process. This is quite common, and parents sometimes
                        don’t know who to talk with to get additional support
                        and guidance. We love talking with parents, and are
                        happy to answer your questions about the application
                        process, financial aid, scholarships and more.
                    </p>
                    <p><a href="{{ route('front.features.guidance-for-parents') }}">See more &rarr;</a></p>
                </div>
            </div>
        </div>
    </section>

    @include('front.components.bloc._testimonials')

    @include('front.components.bloc._faq')

@stop
