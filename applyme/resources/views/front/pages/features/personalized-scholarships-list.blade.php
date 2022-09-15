@extends('layouts.front')

@section('meta-title', "Personalized Scholarships List | Apply.me")

@section('meta-description', "Save time (and frustration) by letting us find you scholarships that you're actually eligible for. Less searching means more applying!")

@section('page-name', 'page-personalized-scholarships-list')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="/imgs/icons/personalized-scholarships-list-banner.png" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Personalized Scholarships List</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        Are you tired of feeling like you're on a wild goose
                        chase for scholarships? Do you want someone to hand you
                        a list of local scholarships that you’re actually
                        eligible for, so that you can focus on applying rather
                        than searching?
                    </div>
                    <hr class="Util--spacer-trans-small">
                    <p class="text-center">
                        <a
                            href="https://applymeacademy.teachable.com/p/personalized-scholarship-list"
                            target="_blank"
                            title=""
                            class="btn btn-lg btn-default btn-am-default">CREATE A PERSONALIZED LIST NOW</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Features</li>
        <li class="active">Personalized Scholarships List</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <p class="text-center Util--text-dark-secondary">
                        If that sounds like you, we would be more than happy to
                        support you on this journey in a one-on-one setting.
                        Based on your unique qualities and situation, we will
                        build you a scholarship list that your can apply for.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-primary Util--padding-top-none">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h2 class="Section__main-title text-center Util--text-primary">Here's how it works</h2>
                    <hr class="Util--spacer-trans-small">

                    <h3 class="Util--text-secondary">STEP 1</h3>
                    <p class="Util--text-dark-secondary">
                        You will complete a scholarship survey, which will help
                        us figure out what types of scholarships will be a good
                        fit.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h3 class="Util--text-secondary">STEP 2</h3>
                    <p class="Util--text-dark-secondary">
                        You (along with your parents) will schedule a 30-minute
                        consult session with us to chat through your goals for
                        the scholarship list, as well as to discuss your
                        specific skills and unique qualities that will make you
                        the best fit for scholarships on your list.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h3 class="Util--text-secondary">STEP 3</h3>
                    <p class="Util--text-dark-secondary">
                        We will sit down with all of this information and search
                        for the perfect scholarships that you can apply for
                        right now, or in the future (or a mix of both - it's up
                        to you).
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h3 class="Util--text-secondary">STEP 4</h3>
                    <p class="Util--text-dark-secondary">
                        You will get a customized scholarship list (of at least
                        25 scholarship opportunities) all organized in in a
                        tracking spreadsheet for convenient access and easy
                        sorting.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <p class="text-center">
                        <a
                            href="https://academy.apply.me/p/personalized-scholarship-list"
                            title=""
                            class="btn btn-lg btn-default btn-am-default">CREATE A PERSONALIZED LIST NOW</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('front.components.bloc._discover-more-features')

@stop
