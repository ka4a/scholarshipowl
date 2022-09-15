@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<section role="region" aria-labelledby="page-title">
    <div class="section--who-we-are-head blue-bg clearfix">
        <div class="container">
            <div class="row">
              <div class="text-container text-center text-white">
                <h1 class="h2 text-light" id="page-title">
                    Who We Are
                </h1>
                <p class="lead mod-top-header">
                    ScholarshipOwl is a collection of dedicated professionals looking <strong class="bold">to make finding and applying for scholarships easier</strong>. Our site is designed to give students an easy way to pay for their education through scholarships. We know how hard it is to pay for college, especially with rising costs. Whether you need $100 or $100,000 to cover your expenses, we're here to help you gain access to that money and put it toward your schooling.
                </p>
                <p class="text-medium mod-subhead">
                    All you have to do is <a href="{{ url_builder('register') }}">fill out one form, and we will take care of the rest!</a>
                </p>
              </div>
          </div>
        </div>
    </div>
</section>

<!-- Our System at a Glance -->
<section role="region" aria-labelledby="our-system-at-glance">
    <div class="section--system-at-glance paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center">
                    <h2 class="h2 text-light" id="our-system-at-glance">
                        <span class="linebreak-xxs">Our System </span>at a Glance
                    </h2>
                    <p class="text-medium">
                        We have created a program that allows students of all ages to <strong class="bold">apply for hundreds of scholarships with just one set of information. You don't have to spend hours on the computer typing out your personal information and filling in redundant forms for the opportunity of adding your name to a list of potential scholarship winners.</strong> We use the information you provide us and cross-reference it with our vast database to give you the scholarships you are eligible for.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Types of Scholarships -->
<section role="region" aria-labelledby="types-of-scholarships">
    <div class="section--types-of-scholarshps lightBlue-bg clearfix">
        <div class="container center-block">
            <div class="row">
                <div class="text-container clearfix">

                <header>
                    <div class="section--types-of-scholarships-header text-center">
                        <h2 class="h3 mod-text-size text-blue text-light" id="types-of-scholarships">
                            Types of Scholarships You Can Apply for
                        </h2>
                        <p class="text-medium">
                            We have a huge selection of scholarships here on our website, with new ones coming in every month. <br />Some of our most sought-after scholarship categories include:
                        </p>
                    </div>
                </header>

                <div class="col-xs-12 col-sm-6 item item-merit">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-merit" alt="Merit Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                Merit Scholarships
                            </h2>
                            <p>
                                <small>
                                    Get paid for all the hard work you've put into your education! Those late night study sessions and head-hurting homework assignments will finally pay off. Merit scholarships are designed to reward successful students for outstanding performance in school, sports, leadership roles, and more.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-no-essay-scholarships">

                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-no-essay-scholarships" alt="No Essay Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                No Essay Scholarships
                            </h2>
                            <p>
                                <small>
                                    You're eyes are fine – that's not a typo. No essay scholarships really do exist! These scholarships are somewhat hard to come by, and they usually involve some sort of drawing to determine the winner. Apply to the right one at the right time, and you could get some easy money to pay for your degree.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-need-based-scholarships">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-need-based-scholarships" alt="Need-Based Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                Need-Based Scholarships
                            </h2>
                            <p>
                                <small>
                                   Most college students don't have thousands of dollars lying around to pay for school. That's where need-based scholarships come into play. These awards are issued to students who exhibit a strong financial need. Whether you're a single mom or a fresh high school grad, there are funding options out there for you.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-athletic-scholarships">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-athletic-scholarships" alt="Athletic Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                Athletic Scholarships
                            </h2>
                            <p>
                                <small>
                                    Have a talent for pitching, kicking, running, swimming, wrestling, dribbling…juggling? Athletic scholarships help awesome athletes like you pay for their education and expand their sports careers. If you'd rather score a goal than a high rate on your SAT, this is a great category for you to explore.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-minority-scholarships">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-minority-scholarships" alt="Minority Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                               	Minority Scholarships
                            </h2>
                            <p>
                                <small>
                					Minority scholarships are designed to encourage diversity in American colleges and universities. Some are made for minority students of all backgrounds, while others aim to target specific ethnic groups or genders. Since these awards focus on a narrowed group of applicants you have a better chance at getting money for college.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-artistic-scholarships">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-artistic-scholarships" alt="Artistic Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                Artistic Scholarships
                            </h2>
                            <p>
                                <small>
                                    Put your creativity to good use when you use it to pay for your education. Artistic scholarships are available in every medium imaginable, from painting scholarships to poetry scholarships and more. If you have a flare for thinking outside the box, this could be the perfect opportunity for you.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-international-student">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-international-student-scholarships" alt="International Student Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                International Student Scholarships
                            </h2>
                            <p>
                                <small>
                                    International students studying in America can get special scholarships to pay for their courses. Many traditional financial aid sources require you to be an American citizen or a resident of the state you attend school in. With international scholarships, you can pay for your classes, school supplies, and living expenses while you're in the States.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-unusual-scholarships">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-unusual-scholarships" alt="Unusual Scholarships" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAFwAAABbAQMAAADEJHCjAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjIJBCQAEnwAB4IswKQAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-semibold text-blue">
                                Unusual Scholarships
                            </h2>
                            <p>
                                <small>
                                    You don't have to be a cookie-cutter college student to find financial aid online. ScholarshipOwl has a number of unusual scholarships available for students who choose to break the mold. Make a duct tape prom dress or become a champion duck caller, and there will be a scholarship waiting for you. It's that simple!
                                </small>
                            </p>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- We do all the work for you -->
<section role="region" aria-labelledby="we-do-work-for-you">
    <div class="section--all-for-you clearfix" id="all-for-you">
        <div class="container">
            <div class="row">
                <div class="text">
                    <h2 class="section--all-for-you-heading text-center mod-heading" id="we-do-work-for-you">
                        We do all the work for you
                    </h2>
                    <p class="section--all-for-you-teaser text-medium text-center mod-text">
                        Our goal is to help you get the financing you're looking for, no matter what it takes.
                        <br />
                        We want you to <span class="bold">graduate debt free.</span>
                    </p>
                    <div id="sign-up-btn" class="button-wrapper">
                        <div class="btn btn-lg btn-block btn-warning mod-padding text-uppercase text-center">
                            <a id="sign_up_now_btn" href="{{ url_builder('register') }}" class="">Apply Now</a>
                            <div class="arrow-btn">
                                <div class="arrow">
                                    <span class="a1"></span>
                                    <span class="a2"></span>
                                    <span class="a3"></span>
                                    <span class="a4"></span>
                                    <span class="a5"></span>
                                    <span class="a6"></span>
                                    <span class="a7"></span>
                                    <span class="a8"></span>
                                    <span class="a9"></span>
                                    <span class="a10"></span>
                                    <span class="a11"></span>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>


<!-- Who Behind the Hoo -->
<section role="region" aria-labelledby="who-behind-the-hoo">
    <div class="section--who-behind-hoo clearfix">
        <div class="container">
            <div class="row">
                <div class="section--who-behind-hoo-header text">
                    <h2 class="section--who-behind-hoo-heading text-large text-light text-center" id="who-behind-the-hoo">
                        <strong class="text-semibold text-blue">The Who</strong> <span class="linebreak">behind the Hoo</span>
                    </h2>
                    <p class="section--who-behind-hoo-teaser text-medium text-center">We aren't a scholarship committee, and we aren't a university. <span class="linebreak"><strong class="bold">We're a team of people dedicated to your success. </strong></span>When you sign up for our services, you'll get:
                    </p>
                </div>
            </div>
        </div>

        <div class="container">
            <div class="row ">

                <div class="services center-block clearfix">
                    <div class="col-xs-12 col-md-3 divider">
                        <div class="item item-consultants text-center">
                            <figure class="">
                                <img src="assets/img/whoweare/scholarship-consultants.png" alt="" class="center-block img-responsive">
                            </figure>
                            <div class="teaser">
                                <h2 class="title">
                                    <span class="text-uppercase">
                                        <strong>Scholarship Consultants</strong>
                                    </span>
                                </h2>
                                <p>to answer all your important questions</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-3 divider">
                        <div class="item item-tech-support text-center">
                            <figure class="">
                                <img src="assets/img/whoweare/tech-support.png" alt="" class="center-block img-responsive">
                            </figure>
                            <div class="teaser">
                                <h2 class="title">
                                    <span class="text-uppercase">
                                        <strong>Technical Support</strong>
                                    </span>
                                </h2>
                                <p>to help when you're in trouble</p>
                            </div>
                        </div>
                    </div>


                    <div class="col-xs-12 col-md-3 divider">
                        <div class="item item-customer-service text-center">
                            <figure class="">
                                <img src="assets/img/whoweare/customer-service.png" alt="" class="center-block img-responsive">
                            </figure>
                            <div class="teaser">
                                <h2 class="title">
                                    <span class="text-uppercase">
                                        <strong>Customer Service</strong>
                                    </span>
                                </h2>
                                <p>to explain our services to you</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-xs-12 col-md-3 divider">
                        <div class="item item-account-managers text-center">
                            <figure class="">
                                <img src="assets/img/whoweare/account-managers.png" alt="" class="center-block img-responsive">
                            </figure>
                            <div class="teaser">
                                <h2 class="title">
                                    <span class="text-uppercase">
                                        <strong>Account Managers</strong>
                                    </span>
                                </h2>
                                <p>to keep track of your applications</p>
                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <div id="wisdom" class="row">
                <div class="section--who-behind-hoo-footer">
                    <p class="section--who-behind-hoo-footer-text text-medium mod-text">
                        With the wisdom of ScholarshipOwl on your side, you can worry less about paying for school.
                        We are here to ensure that our users have another helping hand toward a successful education.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

	@include('includes/refer')
@stop
