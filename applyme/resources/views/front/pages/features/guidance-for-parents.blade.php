@extends('layouts.front')

@section('meta-title', "Guidance for Parents | Apply.me")

@section('meta-description', "Find out how you can help your child prepare for their college journey.")

@section('page-name', 'page-guidance-for-parents')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="/imgs/icons/guidance-for-parents-banner.png" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Guidance for Parents</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        Find out how you can help your child prepare for their
                        college journey.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Features</li>
        <li class="active">Guidance for Parents</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <p class="text-center Util--text-dark-secondary">
                        It is important for parents to be involved in the
                        college process and act as a guide when your child is
                        experiencing doubts, frustration, or indecision. While
                        the college application process will always be
                        associated with some stress, the more you know about it,
                        the better you will manage. We are here to help you
                        learn all about testing, application timelines,
                        financial aid applications, and important aspects of the
                        admissions process.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">College Planning for Parents</h3>
                    <p class="Util--text-dark-secondary">
                        Many parents have the desire and care to help their
                        child succeed but struggle with where to start. You want
                        to do everything in your power to make sure they can
                        afford the college of their dreams, but the amount of
                        information out there is downright overwhelming!
                    </p>
                    <p class="Util--text-dark-secondary">
                        You probably didn’t have to worry about all of this when
                        you were in high school. The process was so much
                        simpler: fill out an application and wait to see if you
                        were accepted. Nowadays it starts much earlier and there
                        are so many factors to consider, not to mention the
                        scary thought of college debt!
                    </p>
                    <p class="Util--text-dark-secondary">
                        This course is designed to help parents like you get
                        started on this journey, whether you have an 8th grader
                        or a rising senior. The mini-course will help you step
                        by step so that you can help your child prepare for
                        college and for scholarships.
                    </p>
                    <p><a href="https://applymeacademy.teachable.com/p/college-planning-for-parents-mini-course">See more &rarr;</a></p>
                </div>
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm">
                    <p class="text-center"><img src="/imgs/features/guidance-for-parents/mini-course.jpg" alt=""></p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 hidden-xs hidden-sm">
                    <p class="text-center"><img src="/imgs/features/guidance-for-parents/coaching.jpg" alt=""></p>
                </div>
                <div class="col-xs-12 col-md-6">
                    <h3 class="Section__main-title Util--text-secondary">Coaching</h3>
                    <p class="Util--text-dark-secondary">
                        The coaching service is generally targeted at your
                        student, because we hope he or she is taking the
                        majority of the responsibility for his or her college
                        search and application process. That said, we understand
                        that this is your investment and it's a good to minimize
                        stress and conflict between you and your student related
                        to college admissions. We encourage parents together
                        with their student to book a session with our highly
                        skilled coaches.
                    </p>
                    <p><a href="{{ route('front.features.admissions-coaching') }}">College Admissions Coaches &rarr;</a></p>
                </div>
            </div>
        </div>
    </section>

    @include('front.components.bloc._discover-more-features')

@stop
