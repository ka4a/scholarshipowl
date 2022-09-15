@extends('layouts.front')

@section('meta-title', "Apply.me - Your One-Stop Shop For College Prep & Scholarships")

@section('meta-description', "Apply.me guides you through the process of applying to college. Admissions coaching, essay assistance, interview prep, scholarship matching & more. Join us today!")

@section('page-name', 'page-index')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <a
                            href="{{ route('front.index') }}"
                            title="">
                            <img src="imgs/icons/am.svg" alt="">
                        </a>
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Guiding Students on Their Path to Success</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        Confidently apply to colleges and scholarships with the
                        support of a professional team.<br class="hidden-xs"> Apply.Me will provide
                        you with the right tools so you can achieve your
                        academic goals.
                    </div>
                    <hr class="Util--spacer-trans-small">
                    <p class="text-center">
                        <a
                            href="https://academy.apply.me"
                            target="_blank"
                            rel="noffolow"
                            title=""
                            class="btn btn-lg btn-default btn-am-default">ACCESS THE ACADEMY</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('front.components.bloc._how-can-we-help')

    <section class="Section Section--light Why-how">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <h3 class="Util--text-primary"><u>Why?</u></h3>
                    <hr class="Util--spacer-trans-micro">
                    <ul class="Util--text-dark-secondary">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            Parents are often surprised to learn just how
                            difficult it can be to get accepted to a university
                            – and some public universities are more selective
                            than private colleges.
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            Students have minimal access to a high school
                            guidance counselor. The National Association for
                            College Admission Counseling recommends a
                            student-to-counselor ratio of 250:1
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            The NACAC reports exceeding ratios, such as 812:1 in
                            California; 624:1 in New York; and 941:1 in Arizona
                            (source: NACAC 2014 State of College Admission
                            Report)
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            The average high school student spends only 38
                            minutes with their high school guidance counselor
                            throughout all 4 years of high school.
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            College costs have risen at a staggering rate.
                            Students are in desperate need of scholarships, but
                            often don’t know how to navigate the scholarship
                            application process.
                        </li>
                    </ul>
                </div>
                <div class="col-xs-12 col-md-6">
                    <hr class="Util--spacer-trans-small visible-xs">
                    <h3 class="Util--text-primary"><u>How?</u></h3>
                    <hr class="Util--spacer-trans-micro">
                    <ul class="Util--text-dark-secondary">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            Many families hire an independent college counselor,
                            enabling students to benefit from one-on-one
                            mentoring, coaching and college application support.
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            On average, families will spend thousands of dollars
                            just to help their child through the college
                            application process. Understandably, this type of
                            investment isn’t possible for most people.
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            Apply.Me offers the same high-quality college
                            coaching mentoring services as independent college
                            counselors, but at an affordable price.
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            We pair you with your very own counselor who will
                            develop an individualized coaching program that best
                            meets your needs.
                        </li>
                        <hr class="Util--spacer-trans-micro">
                        <li>
                            <img src="/imgs/icons/checked.png" alt="">
                            Get personal academic advising, career exploration,
                            application support, essay review, interview
                            preparation, financial aid and scholarship
                            consultation, and much more.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <hr class="Util--spacer-trans-medium">
    <h2 class="text-center Util--text-primary">What We Do</h2>
    <hr class="Util--spacer-trans-small hidden-xs">

    <section class="Section Section--light">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-5">
                    <h3 class="Section__main-title Util--text-secondary">College Preparation Courses</h3>
                    <p class="Util--text-dark-secondary">
                        Our comprehensive online courses teach students how to
                        create their perfect system for staying organized and on
                        top of college-prep deadlines. Get the inside scoop on
                        how to stand out against other applicants and be well on
                        your way to creating impressive applications.
                    </p>
                    <p><a href="{{ route('front.features.courses') }}">See more &rarr;</a></p>
                    <hr class="Util--spacer-trans-small visible-xs">
                </div>
                <div class="col-xs-12 col-md-7">
                    <div class="row">
                        <div class="col-xs-12 col-sm-6">
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

                        <div class="col-xs-12 col-sm-6">
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
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-secondary">
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
                        scholarship-related questions. School selection, college
                        guidance and application management are all part of the
                        one-on-one help and support you will receive. You’ll get
                        personalized attention from a counselor, and be able to
                        access the tools you need to succeed throughout the
                        application and college decision process.
                    </p>
                    <p><a href="{{ route('front.features.admissions-coaching') }}">See more &rarr;</a></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light">
        <div class="container">
            <div class="row">
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
                <div class="col-xs-12 col-md-6 hidden-xs">
                    <p class="text-center"><img src="/imgs/icons/guidance-for-parents-lg.png" alt=""></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-secondary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 hidden-xs">
                    <p class="text-center"><img src="/imgs/index/personalized-scholarships-list.png" alt=""></p>
                </div>
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">Personalized Scholarships List</h3>
                    <p class="Util--text-dark-secondary Util--text-secondary">
                        The scholarship search process can be extremely time
                        consuming. If you’re tired of feeling like you’re on a
                        wild goose chase for scholarships, we can create a list
                        just for you, based on your specific skills, qualities,
                        hobbies, talents and characteristics. With that, your
                        Apply.Me coach can help you create and stick to a plan
                        for applying for scholarships and review your
                        scholarship application.
                    </p>
                    <p><a href="{{ route('front.features.personalized-scholarships-list') }}">See more &rarr;</a></p>
                </div>
            </div>
            <hr class="Util--spacer-trans-medium">
            <div class="row">
                <div class="col-xs-12">
                    <p class="text-right"><a href="{{ route('front.features.index') }}">See all features &rarr;</a></p>
                </div>
            </div>
        </div>
    </section>

    @include('front.components.bloc._testimonials')

    @include('front.components.bloc._faq')

@stop
