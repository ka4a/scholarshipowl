@extends('layouts.front')

@section('meta-title', "FAQ | Apply.me")

@section('meta-description', "Find answers to our most frequently asked questions.")

@section('page-name', 'page-faq')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <h1 class="Banner__title text-center">FAQ</h1>
                    <hr class="Util--spacer-trans-small">
                    <div class="Banner__text text-center">
                        Need help?<br>
                        Check out our frequently asked questions to see if you
                        can find the answer
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">FAQ</li>
    </ol>

    <section class="Section Section--light-primary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h2 class="Util--text-primary">What is Apply.Me?</h2>
                    <p class="Util--text-dark-secondary">
                        Apply.Me is an online resource designed to help students
                        with their dream of obtaining a higher-education degree.
                        We are here to assist you with the complicated and
                        stressful process related to all-thing college. From
                        choosing the right school to finding scholarships, our
                        dedicated team of professionals will help you with every
                        step of the way.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">Who is Apply.Me for?</h2>
                    <p class="Util--text-dark-secondary">
                        Apply.me was created to help students in college or
                        university. Whether you are still in high school,
                        currently enrolled in college, a graduate student or a
                        parent of a college-aged student, we will work together
                        and give you one-on-one attention.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">How much does it cost to sign up?</h2>
                    <p class="Util--text-dark-secondary">
                        We offer a variety of plans and package at an attractive
                        price. For more information, please visit our <a href="{{ route('front.pricing') }}" class="Util--link-underlined">Plans and Packages page</a>.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">Can international students apply too?</h2>
                    <p class="Util--text-dark-secondary">
                        Yes, if you are an international student who is planning
                        on studying in the US, we can help you with every step
                        of the way.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">Where do I find scholarships?</h2>
                    <p class="Util--text-dark-secondary">
                        It can be very tricky to find scholarships to pay for
                        school. While the internet is the most obvious place to
                        start, there are thousands of scholarships available out
                        there and sifting through them all to check for
                        qualifications and requirements can become very tedious
                        and exhausting. To save you from getting overwhelmed by
                        your options, we will compile a list based on your
                        personal details and narrow down your options.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">Do I have to pay for scholarships?</h2>
                    <p class="Util--text-dark-secondary">
                        No, you do not have to pay to apply for scholarships.
                        Scholarships are free, and so is applying to
                        scholarships. We are a service that helps you pinpoint
                        exactly which scholarships you are qualified for. Every
                        student is different and has different resources. We
                        will build you a personalized list based on your unique
                        qualities, so you will have more free time to focus on
                        perfecting your application.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">How much money can I get in scholarships?</h2>
                    <p class="Util--text-dark-secondary">
                        The amount of money you can win varies by the
                        scholarship. Typically, awards range from a few hundred
                        to tens of thousands. While there is no limit to the
                        amount of money you can earn in college scholarships,
                        however, your school may put a cap on the number of
                        awards you can receive based on the estimated cost of
                        attendance. Talk to your college’s financial aid office
                        to learn more about the cost of attendance for your
                        school and potential caps on your awards.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">Can I still get scholarships even if my GPA is low?</h2>
                    <p class="Util--text-dark-secondary">
                        Many private scholarship committees do not even ask for
                        your GPA on your application since they focus more on
                        your essay and overall achievement. All you need to do
                        is apply for the awards you qualify for and see how it
                        goes. We can tailor a scholarship list to fit your
                        specific situation and needs.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">When should I start applying to scholarships?</h2>
                    <p class="Util--text-dark-secondary">
                        If you are serious about college but do not have enough
                        money to pay for tuition, you should begin applying for
                        scholarships as early as possible. Students can (and are
                        encouraged to) start searching for financial aid as
                        early as high school.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">What is the FAFSA?</h2>
                    <p class="Util--text-dark-secondary">
                        If you want to to receive a need-based government Pell
                        Grant or apply for subsidized student loans, you must
                        complete the Free Application for Federal Student Aid
                        (FAFSA). It takes about 45 minutes complete the lengthy
                        application form and you will need your parent’s
                        financial details as well. If you need help filling out
                        the FAFSA, our team of is here to help you navigate
                        through it step-by-step.
                    </p>

                    <hr class="Util--spacer-trans-small">

                    <h2 class="Util--text-primary">Is this service legitimate?</h2>
                    <p class="Util--text-dark-secondary">
                        Apply.Me is a legitimate website and company that helps
                        students every day. Check out the many success stories
                        from students and parents who benefitted from our
                        products:
                    </p>
                </div>
            </div>
        </div>
    </section>

@stop
