@extends('layouts.front')

@section('meta-title', "Admissions Coaching | Apply.me")

@section('meta-description', "Get an edge on the competition with personalized admissions coaching. Find your best-fit schools, submit an outstanding application, write a memorable essay & more.")

@section('page-name', 'page-admissions-coaching')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="/imgs/icons/admissions-coaching-banner.png" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Admissions Coaching</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        The college and scholarship application process is a
                        stressful experience for both students and parents.
                        Work with your very own advisor and receive the guidance
                        you need to succeed in the entire college process. We’ll
                        help you find your best-fit schools, submit an
                        outstanding application, write a strong essay and more.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Features</li>
        <li class="active">Admissions Coaching</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/HplRXdvyFeI/?rel=0&amp;modestbranding=1"></iframe>
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <p class="text-center">
                        Get started today.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-primary Util--padding-top-none">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h3 class="Section__main-title Util--text-primary">School Selection</h3>
                    <p class="Util--text-dark-secondary">
                        Choosing a college isn’t always easy. The right college
                        for you is not necessarily the most prestigious school
                        or the school where your parents or older siblings went.
                        Public-school counselors often know little about
                        colleges outside their state and steer students to
                        states schools instead of a private colleges that could
                        include a better academic fit and significant price
                        discounts. We will help students narrow which school is
                        best for them based on their unique qualifications and
                        background, help them understand what scouts look for,
                        and how they can improve their chances of being
                        accepted.
                    </p>
                </div>
            </div>
            <hr class="Util--spacer-trans-small">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h3 class="Section__main-title Util--text-primary">Admissions Strategies</h3>
                    <p class="Util--text-dark-secondary">
                        We help students understand what goes on behind the
                        closed doors of admissions offices The college
                        admissions process is tailored to each individual and is
                        very personal. Sometimes, in order to get accepted to
                        college, a person needs to improve their grades or take
                        part in extracurricular activities. In other situations,
                        it can be good for the applicant to focus on different
                        colleges or even different programs to help improve
                        their chances.
                    </p>
                </div>
            </div>
            <hr class="Util--spacer-trans-small">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h3 class="Section__main-title Util--text-primary">Applications Management</h3>
                    <p class="Util--text-dark-secondary">
                        Applications can be complicated to fill out. In
                        particular, the Free Application for Federal Student Aid
                        (FAFSA) is especially difficult. It’s easy to understand
                        why students (and parents) feel uneasy tackling this
                        task. Still, it is absolutely essential to complete the
                        FAFSA form if you hope to win any kind of financial aid.
                        Obviously, not everyone is a FAFSA experts. Our coaches
                        are here to help you with any application you need and
                        to ensure you avoid making any mistakes while filling
                        out the numerous forms.
                    </p>
                </div>
            </div>
            <hr class="Util--spacer-trans-small">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h3 class="Section__main-title Util--text-primary">College Guidance</h3>
                    <p class="Util--text-dark-secondary">
                        Our personal coaches assist students with understanding
                        and analyzing their options when they receive multiple
                        offers from the colleges they applied for. We will help
                        you evaluate factors such as career opportunities,
                        social life, and financial aid, and how each potential
                        institution fits into their admissions acceptance
                        decisions. We will also guide students with their
                        college major selection, portfolio and resume
                        enhancement and interview skills building.
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('front.components.bloc._discover-more-features')

@stop
