@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- The Ultimate Guide to College Scholarships -->
<section role="region" aria-labelledby="page-title">
    <div id="tips_head" class="blue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container center-block text-center text-white">
                    <h2 class="h2 text-light" id="page-title">
                        The <span class="text-uppercase">ultimate</span> Guide To <br />College Scholarships
                    </h2>
                    <p class="lead mod-top-header">
                       Feel lost in the hustle and bustle of college scholarships? Don't know where to go, what to do, or how to apply for the aid you need? There's an eBook for that! ScholarshipOwl presents The <strong><em>Ultimate Guide To College Scholarships</em></strong>, a comprehensive guide through the scholarship application process.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ebook -->
<section role="region" aria-labelledby="what-you-ll-learn">
    <div class="section--ebook paleBlue-bg clearfix" id="ebook">
        <div class="container">
            <div class="row">
                <div class="text-container text-container-narrow center-block text-center">
                    <header>
                        <div class="section--ebook-header text-center">
                            <h2 class="h3 mod-text-size text-center" id="what-you-ll-learn">What You'll Learn from Our eBook</h2>
                            <p class="lead text-center">Here are some of the awesome lessons you'll learn <span class="linebreak-sm">from our scholarship eBook:</span></p>
                        </div>
                    </header>

                    <div class="text-container-narrow center-block">

                        <p class="h4 text-blue text-semibold">
                            How To Choose The Perfect College
                        </p>

                        <p class="divider-dashed">
                            Learn how to compare costs, admissions requirements, degree options, and more to pick the right school for you.
                        </p>

                        <p class="h4 text-blue text-semibold">
                            The Importance Of College Scholarships
                        </p>

                        <p class="divider-dashed">
                            See just how valuable scholarships and grants can be in helping you pay for your degree.
                        </p>

                        <p class="h4 text-blue text-semibold">
                            Different Types Of College Scholarships
                        </p>

                        <p class="divider-dashed">
                            Understand the differences between merit scholarships, athletic scholarships, minority scholarships, religious scholarships, and much more.
                        </p>

                        <p class="h4 text-blue text-semibold">
                            The Right Way To Apply For Scholarships
                        </p>

                        <p class="divider-dashed">
                            Get a complete overview of how the scholarship application process works, from finding the award to submitting your application.
                        </p>

                        <p class="h4 text-blue text-semibold">
                            How To Improve Your Chances Of Getting A Scholarship
                        </p>

                        <p class="divider-dashed">
                            Read exclusive "pro tips" from ScholarshipOwl to boost your chances of winning scholarship money.
                        </p>

                        <p class="h4 text-blue text-semibold">
                            The Do's And Don'ts Of Scholarship Essays
                        </p>

                        <p class="divider-dashed">
                            Find out what scholarship committees are really looking for in essays, and learn how to improve your writing skills for the future.
                        </p>

                        <p class="h4 text-blue text-semibold">
                            How To Spot A Scholarship Scam
                        </p>

                        <p class="divider-dashed">
                            Protect yourself against scam artists who only want to steal your money or personal information.
                        </p>

                        <p class="text-semibold"><em>…And Much More!</em></p>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- Snippets from the book + carousel -->
<section role="region" aria-labelledby="snippets-from-book">
    <div class="section--testimonials section--snippets">
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <div class="text-container clearfix">
                        <h2 class="h4 text-center text-bold" id="snippets-from-book">
                            <big>Snippets from the Book</big>
                        </h2>
                        <div class="section--snippets-text text-center">
                            <p>
                               Want a sneak peek of the amazing advice in our scholarship guide eBook? We'll give it to ya! We spent a great deal of <span class="linebreak-md">time putting together the perfect resource for current and future college students in need of financial aid.</span><span class="lilnebreak-md">Check out these quick snippets from the book…</span>
                            </p>
                        </div>

                        <section role="region" aria-labelledby="snippets">
                            <h2 class="sr-only" id="snippets">Snippets from the book</h2>
                            <div class="section--snippets-carousel carousel slide carousel-text" data-ride="carousel">
                                <!-- Indicators -->
                                <ol class="carousel-indicators">
                                    <li data-target=".carousel-text" data-slide-to="0" class="active">&#10148;</li>
                                    <li data-target=".carousel-text" data-slide-to="1">&#10148;</li>
                                    <li data-target=".carousel-text" data-slide-to="2">&#10148;</li>
                                    <li data-target=".carousel-text" data-slide-to="3">&#10148;</li>
                                    <li data-target=".carousel-text" data-slide-to="4">&#10148;</li>
                                </ol>

                                <!-- Wrapper for slides -->
                                <div class="carousel-inner text-center clearfix">
                                    <div class="item active">
                                        <div class="carousel-content">
                                            <p class="text-medium text-uppercase text-center">
                                                Is college right for you?
                                            </p>
                                            <p>
                                                <em>Getting a college education  could be the best decision you'll ever make in life, but the fact is that <strong>college is not for everyone</strong>. Sure, we  could tell you that going to college is the only way you will succeed in life,  but there are plenty of careers in the world that don't require a college  education… </em>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="carousel-content">
                                            <p class="text-medium text-uppercase text-center mod-heading">
                                                The importance of scholarships in college
                                            </p>
                                            <p>
                                                <em>It's no secret that college is expensive, but most people don't realize just how high the cost of a college degree has become over the years. Based on the statistics below, the average bachelor's degree from a pubic four-year, in-state university costs about <strong>$74,000</strong>. That's a lot of money to potentially pay out of your own pocket…</em>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="carousel-content">
                                            <p class="text-medium text-uppercase text-center mod-heading">
                                                Scholarships vs. grants vs. student loans
                                            </p>
                                            <p>
                                                <em>Financial aid is primarily broken into three categories: scholarships, grants, and student  loans. All of these options can help you pay for your education, but they all  work in different ways. By understanding the differences between scholarships,  grants, and student loans, you can effectively find the right way to pay for  college…</em>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="carousel-content">
                                            <p class="text-medium text-uppercase text-center mod-heading">
                                                The do's and dont's of scholarship essays
                                            </p>
                                            <p>
                                                <em>Essays  are one of the most important features in most scholarship applications.  These essays show the scholarship committee  who you are and why they should choose you as a recipient.  If your grades aren't great or your academic  record is unimpressive, your essay could give you a new opportunity to make the  judges take notice of your application…</em>
                                            </p>
                                        </div>
                                    </div>
                                    <div class="item">
                                        <div class="carousel-content">
                                            <p class="text-medium text-uppercase text-center mod-heading">
                                                How to recognize scholarship scams
                                            </p>
                                            <p>
                                                <em>Sadly,  not all scholarship offers online are legitimate. Some people will create fake  scholarships on the internet to collect personal information and money from  college students. Before you become a victim of a scholarship scam, you need to  know what to watch out for…</em>
                                            </p>
                                        </div>
                                    </div>
                				</div>
                            </div>
                        </section>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Download your copy -->
<section role="region" aria-labelledby="download-your-copy">
    <div class="section--download-your-copy lightBlue-bg clearfix">
        <div class="container center-block">
            <div class="row">
                <div class="col-xs-12">
        			<div class="text-container text-container-narrow center-block text-center clearfix">
                        <h2 class="h4 text-uppercase text-semibold text-warning" id="download-your-copy">
                            Download Your Copy Today!
                        </h2>
                        <p class="mod-text">
                            Prepare yourself for a great experience in  college by reading our one-of-a-kind scholarship guide. You'll learn <strong>The Do's, The Don'ts, The Now's, and the  NEVER's</strong> for every step in the application process. Consider this as your  crash course in the world of financial aid. The more you know, the more you  will succeed!
                        </p>
                        <div class="col-xs-12 col-md-4 col-md-offset-4">
                            <div class="button-wrapper">
                                <a href="{!! asset("assets/pdf/Scholarship_Tips_Tricks_ScholarshipOwl.pdf") !!}" target="_blank" class="btn btn-lg btn-warning btn-block center-block text-uppercase text-center">
                                    Download Now
                                </a>
                            </div>
                        </div>
        			</div>
                </div>

            </div>
        </div>
    </div>
</section>
@stop
