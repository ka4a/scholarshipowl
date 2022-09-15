@extends('layouts.front')

@section('meta-title', "Interview Preparation | Apply.me")

@section('meta-description', "Our coaches can provide you with valuable feedback to commonly asked interview questions. Feel confident and ace your interview!")

@section('page-name', 'page-interview-preparation')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="/imgs/icons/interview-preparation-banner.png" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Interview Preparation</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        Coaches can provide you with valuable feedback that will
                        help you improve your responses during interviews. The
                        more you practice, the more confident you will be. You
                        should feel confident walking into any interview, and a
                        coach can give you the tools to feel self-assured.
                    </div>
                    <hr class="Util--spacer-trans-small">
                    <p class="text-center">
                        <a
                            href="https://academy.apply.me/p/interview-preparation"
                            target="_blank"
                            rel="noffolow"
                            title=""
                            class="btn btn-lg btn-default btn-am-default">GET IT</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Features</li>
        <li class="active">Interview Preparation</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <p class="text-center Util--text-dark-secondary">
                        Even if you have an impressive educational background or
                        outstanding extracurricular achievements, your
                        admissions interview is critical to the success of your
                        application. Even the most qualified students sometimes
                        don’t get accepted, all because they made a poor
                        impression during their interview.
                    </p>

                    <p class="text-center Util--text-dark-secondary">
                        An interview can be stressful, especially when you
                        haven't had a lot of practice. Having interview coaching
                        can help develop a variety of skills and techniques that
                        are useful for interviews. It's also a way to prepare
                        for and feel more confident about upcoming interviews.
                    </p>

                    <p class="text-center Util--text-dark-secondary">
                        Coaches can provide you with valuable feedback that will
                        help you improve your responses during interviews. The
                        more you practice, the more confident you will be. You
                        should feel confident walking into any interview, and a
                        coach can give you the tools to feel self-assured.
                    </p>
                </div>
            </div>
            <hr class="Util--spacer-trans-medium">
            <h2 class="text-center Util--text-primary">Our coaches may also help you with other elements of the interview</h2>
        </div>
    </section>

    <section class="Section">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-6 Util--padding-large">
                    <div class="text-center">
                        <img src="/imgs/features/interview-preparation/how-to-ask-the-right-question.png" alt="How to ask the right question">
                    </div>
                    <div class="text-center">
                        <h2 class="Util--text-primary">How to ask the right questions</h2>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 Section--light-secondary Util--padding-large">
                    <div class="text-center">
                        <img src="/imgs/features/interview-preparation/how-to-dress.png" alt="How to dress">
                    </div>
                    <div class="text-center">
                        <h2 class="Util--text-primary">How to dress</h2>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12 col-md-6 Section--light-secondary Util--padding-large">
                    <div class="text-center">
                        <img src="/imgs/features/interview-preparation/facial-and-body-language-use.png" alt="Facial and body language use">
                    </div>
                    <div class="text-center">
                        <h2 class="Util--text-primary">Facial and body language use</h2>
                    </div>
                </div>
                <div class="col-xs-12 col-md-6 Util--padding-large">
                    <div class="text-center">
                        <img src="/imgs/features/interview-preparation/develop-effective-nonverbal-communication.png" alt="Develop effective nonverbal communication">
                    </div>
                    <div class="text-center">
                        <h2 class="Util--text-primary">Develop effective nonverbal communication</h2>
                    </div>
                </div>
            </div>
            <hr class="Util--spacer-trans-small">
            <p class="text-center">
                <a
                    href="https://academy.apply.me/p/interview-preparation"
                    target="_blank"
                    rel="noffolow"
                    title=""
                    class="btn btn-lg btn-default btn-am-default">GET IT</a>
            </p>
        </div>
    </section>

    @include('front.components.bloc._discover-more-features')

@stop
