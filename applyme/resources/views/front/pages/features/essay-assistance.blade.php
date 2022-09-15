@extends('layouts.front')

@section('meta-title', "Essay Assistance | Apply.me")

@section('meta-description', "Craft a professional, successful essay with the help of our team. We will review your 500-word essay so you can feel confident & improve your chances!")

@section('page-name', 'page-essay-assistance')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="/imgs/icons/essay-assistance-banner.png" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Essay Assistance</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        Crafting a perfect essay improves your chances of
                        getting accepted for college or winning an award.
                    </div>
                    <hr class="Util--spacer-trans-small">
                    <p class="text-center">
                        <a
                            href="https://academy.apply.me/p/essay-assistance"
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
        <li class="active">Essay Assistance</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <p class="Util--text-dark-secondary">
                        Essays are a big part of getting into college. Most
                        school and scholarship applications will require one or
                        more essays. Being able to put together a strong,
                        compelling essay that persuades the person reading it is
                        really important. However, many students are not sure
                        what to write about or aren’t confident in their own
                        writing skills.
                    </p>

                    <p class="Util--text-dark-secondary">
                        We will assist you with a 500-word essay so that you can
                        hand in a professional, successful essay that you can
                        feel confident about.
                    </p>
                </div>
            </div>
            <hr class="Util--spacer-trans-small">
            <h2 class="text-center Util--text-primary">Essay Tips</h2>
        </div>
    </section>

    <section class="Section Section--light-secondary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="media">
                        <div class="media-left hidden-xs">
                            <img class="media-object" src="/imgs/icons/quote.png" alt="All essays consist of the same three parts">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading"><em>All essays consist of the same three parts</em></h2>
                            <hr class="Util--spacer-trans-micro">
                            <p class="Util--text-dark-secondary">
                                Throughout college, there are many instances
                                where you may be required to write an essay –
                                your application, exam questions, small writing
                                prompts, etc. All essays consist of the same
                                three parts: an introduction with a thesis, a
                                body paragraph or body paragraphs that support
                                the thesis, and a concluding paragraph that
                                summarizes the overall essay.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="media">
                        <div class="media-body">
                            <h2 class="media-heading text-right"><em>Catch attention with a unique application</em></h2>
                            <hr class="Util--spacer-trans-micro">
                            <p class="Util--text-dark-secondary">
                                It is important to understand that college or
                                scholarship committee is looking for specific
                                students to meet their criteria. Understand the
                                goals and true purpose of the organization to
                                better respond to the essay at hand. Catch the
                                scholarship committee’s attention with a unique
                                application. Devise a way to make your story
                                stand out and you will get a better reception.
                            </p>
                        </div>
                        <div class="media-right hidden-xs">
                            <img class="media-object" src="/imgs/icons/quote.png" alt="How to dress">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-secondary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="media">
                        <div class="media-left hidden-xs">
                            <img class="media-object" src="/imgs/icons/quote.png" alt="All essays consist of the same three parts">
                        </div>
                        <div class="media-body">
                            <h2 class="media-heading"><em>Demonstrate your strength through struggle</em></h2>
                            <hr class="Util--spacer-trans-micro">
                            <p class="Util--text-dark-secondary">
                                If an essay prompts you for a personal story,
                                think about an event that has changed your life
                                for the better. While adding your personal
                                story, do not assume that a sad story will make
                                the committee sympathize with you. Write your
                                essay in a way that demonstrates your strength
                                through struggle. These committees generally
                                admire perseverance and determination more so
                                than grief and sorrow.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <div class="media">
                        <div class="media-body">
                            <h2 class="media-heading text-right"><em>Be honest about your achievements</em></h2>
                            <hr class="Util--spacer-trans-micro">
                            <p class="Util--text-dark-secondary">
                                While it may be tempting to fabricate your
                                accomplishments and experiences, it is not a
                                good idea. Many committees run background checks
                                to verify that the information you provided is
                                accurate. If they catch you in a lie, they will
                                immediately dismiss your application. Be honest
                                about your achievements, even if they’re not as
                                outstanding as you had hoped.
                            </p>
                        </div>
                        <div class="media-right hidden-xs">
                            <img class="media-object" src="/imgs/icons/quote.png" alt="How to dress">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-primary Util--padding-top-none">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">
                    <h2 class="text-center Util--text-primary">Essay Tips: Word Limit</h2>
                    <hr class="Util--spacer-trans-medium">
                    <p class="Util--text-dark-secondary">
                        A professor or college entry application may asks for a
                        word limit in their required essay. As a general rule,
                        try to stay as close to limited words as possible
                        without going too far over or under. Write the first
                        draft from start to finish without any pauses. This will
                        make the writing sound fluid, and you can make
                        adjustments after that. Avoid over-editing your work.
                        Ideally, you should take a long pause between editing
                        sessions so you can clear your head and come back with a
                        fresh perspective. Try not to think about the word count
                        too much and don’t throw fluff sentences in your essay.
                        Professors and scholarship committees see right through
                        those. Instead, think of an additional sentence to
                        enhance the support in your body paragraphs. If you feel
                        like you have concisely and sufficiently answered the
                        question below the word count, trust your gut. Most
                        instructors will value quality over quantity.
                    </p>
                    <hr class="Util--spacer-trans-small">
                    <p class="text-center">
                        <a
                            href="https://academy.apply.me/p/essay-assistance"
                            target="_blank"
                            rel="noffolow"
                            title=""
                            class="btn btn-lg btn-default btn-am-default">GET IT</a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    @include('front.components.bloc._discover-more-features')

@stop
