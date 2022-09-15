@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('tips') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('partnerships') !!}
    {!! HTML::script('assets/plugins/lazyloadxt/jquery.lazyloadxt.spinner.min.css') !!}
    {!! HTML::script('assets/plugins/lazyloadxt/jquery.lazyloadxt.fadein.min.css') !!}
@endsection

@section("scripts")
    {!! HTML::script('assets/plugins/lazyloadxt/jquery.lazyloadxt.extra.min.js') !!}
    {!! HTML::script('assets/js/lazyloadxt.min.js') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- Helpful Guides from ScholarshipOwl header -->
<section role="region" aria-labelledby="page-title">
    <div class="blue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container center-block text-white text-center">
                    <h1 class="h2 text-light" id="page-title">
                        Education Center
                    </h1>
                    <p class="lead mod-top-header">
                       ScholarshipOwl offers a number of scholarship resources to help you win the money you need for your degree! Learn how to find scholarships, apply for scholarships, and win the perfect scholarships for you.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Helpful guides -->
<section role="region" aria-labelledby="helpful-guides">
    <div class="section--helpful-guides paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-justify">

                    <h2 class="sr-only" id="helpful-guides">Helpful guides</h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers mod-first-child">
                      <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/Y0eE7Tr8PcI" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="{{ url_builder('howtoapply') }}">Tips on How to Apply</a> – A comprehensive look at what it takes to win the scholarships you need to pay for college.
                    </p>
                    <p>
                        <a href="{{ url_builder('whatwedo') }}">How to Find Scholarships with ScholarshipOwl</a> – See just how easy it is to find college scholarships online with ScholarshipOwl, and learn about the other amazing services we have to offer!
                    </p>
                    <p>
                        <a href="{{ url_builder('register') }}">The Ultimate Guide to College Scholarships</a> – Read through our eBook to learn The Do's, The Don'ts, The Now's, and The NEVER's of applying for college scholarships, along with additional online resources for you to explore.
                    </p>

                    <!-- button -->
                    <div class="button-wrapper">
                        <p class="h5 mod-text-size text-blue text-center text-semibold">Additional Services</p>
                        <a href="{{ url_builder('additional-services') }}" class="btn btn-primary btn-block text-uppercase">Learn More…</a>
                    </div>

                    <h2 class="h3 mod-text-size mod-margin text-left">
                        How to Find and Win College Scholarships
                    </h2>
                    <p>
                        You can never learn too much about applying for college scholarships. With the links and tutorials below, you can discover even more ways to find and win the scholarships you deserve! See what it takes to get everything you need to pay for college and graduate debt-free.
                    </p>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/KbM73_2bIUs" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="{{ url_builder('register') }}">ScholarshipOwl.com</a> – Finding and applying for college scholarships just got a little easier! At ScholarshipOwl, you can get matched with scholarships you qualify for and apply for all of them with one quick and easy application. We do the work for you, so all you have to do is fill out one form, one time. Get automatically applied to recurring scholarships and other awards you qualify for, and gain access to thousands of dollars to help you pay for college! <a href="{{ url_builder('register') }}">Sign up today</a>.
                    </p>
                    <p>
                        <a href="http://money.cnn.com/tools/collegecost/collegecost.html" target="_blank">College Cost Calculator</a> – An online tool from CNN Money to help you find out how much money you need for college. The database contains all of the major colleges and universities in the country, with a breakdown of tuition costs and estimated college expenses for each school.
                    </p>
                    <p>
                        <a href="http://www.iwillteachyoutoberich.com/blog/the-1-day-iwillteachyoutoberich-entrepreneurship-boot-camp/" target="_blank">How I Won $100,000+ in College Scholarships</a> – An online tutorial from a successful scholarship winner that you can use to inspire you in the application process.
                    </p>
                    <p>
                        <a href="http://money.usnews.com/money/blogs/my-money/2011/03/22/17-ways-to-boost-your-shot-at-a-scholarship" target="_blank">17 Ways to Win a Scholarship</a> – Helpful tips and tricks from U.S. News & World Report explaining steps you can take to improve your applications.
                    </p>
                    <p>
                        <a href="http://www.wsj.com/articles/how-to-win-the-college-scholarship-game-1408126980" target="_blank">How to Win the College Scholarship Game</a> – Advice from The Wall Street Journal to help you beat the competition and make your application stand out from everyone else's.
                    </p>
                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Scholarship Essay Tips and Tricks
                    </h2>
                    <p>
                        Writing a killer scholarship essay will significantly improve your chances of winning college scholarships. Make a great first impression and wow the scholarship committee with your eloquent way with words, and you'll have no trouble paying for your degree. Here are some awesome scholarship essay tips and tricks from around the web.
                    </p>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/w818YUg0Wls" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="http://www.finaid.org/scholarships/essays.phtml" target="_blank">Essay Writing Tips</a> – Simple and effective advice to make your scholarship essays stand out from the crowd.
                    </p>
                    <p>
                        <a href="http://www.internationalstudent.com/essay_writing/scholarship_essaysample/" target="_blank">Examples of Winning Scholarship Essays</a> – Read samples of actual winning college scholarship essays, along with the comments about what made the writing successful.
                    </p>
                    <p>
                        <a href="https://www.rivier.edu/uploadedFiles/Scholarship%20Essay%20Event%20Handout.pdf" target="_blank">Common Essay Questions and How to Handle Them</a> – A breakdown of popular essay prompts you may come across, courtesy of Rivier University.
                    </p>
                    <p>
                        <a href="http://www.plagtracker.com/" target="_blank">Free Plagiarism Checker</a> – A free online tool that you can use to check for plagiarism when writing your scholarship essays.
                    </p>
                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Free Scholarship Webinars Online
                    </h2>
                    <p>
                        A webinar is a virtual seminar that you can attend from your home computer. Learn from experts in financial aid, college applications, career placement, and much more. If you don't have time to attend the webinars live, you can usually watch previous recordings online and skip to the parts you really want to see!
                    </p>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="420" height="315" data-src="https://www.youtube.com/embed/2nfHwres5r4" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="http://www.scholarshipworkshop.com/scholarship-webinars-college-preparation-webinars-online-classes-for-writing-college-and-scholarship-essays.html" target="_blank">The Scholarship Workshop</a> – Sign up for scholarship webinars throughout the year for just $30 a session. These webinars aren't "free" like the other ones listed here, but they give you direct insights from industry experts about finding and applying for college scholarships.
                    </p>
                    <p>
                        <a href="http://blog.collegegreenlight.com/blog/free-scholarship-webinars/" target="_blank">Free Scholarship Webinars</a> – Watch videos of previously-recorded scholarship webinars online, including tips for scholarship essays and preparing for your future career.
                    </p>
                    <p>
                        <a href="http://www.cies.org/event-type/webinar-schedule" target="_blank">FullBright Scholarship Webinars</a> – Attend webinars from the Fullbright Scholar Program geared toward international studies and cultural affairs.
                    </p>

                    <!-- button -->
                    <div class="button-wrapper">
                        <p class="h5 mod-text-size text-blue text-center text-semibold">Sign Up for ScholarshipOwl Webinars</p>
                        <a href="{{ url_builder('register') }}" class="btn btn-primary btn-block text-uppercase">Sign Up</a>
                    </div>

                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Resources for Federal Student Aid
                    </h2>
                    <p>
                        The U.S. government offers a variety of grants and student loans that you can apply for free of charge. Many colleges and universities use the Free Application for Federal Student Aid (FAFSA) to determine how much money they can offer their students. Review the resources below to learn how to find and apply for federal aid to minimize your cost of attendance.
                    </p>
                    <h2 class="h3 mod-text-size mod-margin text-left">
                        FAFSA Basics: What It Is and How to Apply
                    </h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/c-23SMf5DyQ" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="https://fafsa.ed.gov/" target="_blank">Free Application for Federal Student Aid</a> – A link to the free application you need to apply for grants and student loans through the federal government.
                    </p>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/VRyXfUStHO0" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="https://fafsa.ed.gov/help/fftoc03b.htm" target="_blank">How to Apply for Aid</a> – The steps you need to take to complete your FAFSA online.
                    </p>
                    <p>
                        <a href="https://studentaid.ed.gov/eligibility/basic-criteria" target="_blank">Basic Eligibility Requirements</a> – Anyone can apply for aid through the FAFSA. This resource explains which students are the most likely to receive aid after completing their applications.
                    </p>
                    <p>
                        <a href="https://fafsa.ed.gov/help.htm" target="_blank">FAFSA FAQs</a> – Answers to commonly asked questions about the FAFSA, courtesy of the Department of Education.
                    </p>

                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Common FAFSA Mistakes That <br />Could Cost You Thousands
                    </h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/AlARWTi3Y5E" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="http://www.ed.gov/blog/2014/01/7-common-fafsa-mistakes/" target="_blank">7 Common FAFSA Mistakes</a> – Advice directly from the U.S. Department of Educstion to help you avoid common errors on your FAFSA.
                    </p>

                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Maximize Your Federal Student Aid
                    </h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/6YrzbzVvc8Q" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="https://studentaid.ed.gov/fafsa/estimate" target="_blank">Estimate Your Aid</a> – See approximately how much money you may get from your FAFSA.
                    </p>
                    <p>
                        <a href="http://www.boston.com/business/personalfinance/gallery/federalstudentaid/" target="_blank">9 Tips on Applying for Federal Student Aid</a> – Quick and helpful suggestions to maximize your federal grant and student loan options.
                    </p>

                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Types of Federal Aid
                    </h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/Pn4OECMTh5w" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="https://studentaid.ed.gov/types/grants-scholarships" target="_blank">Federal Scholarships And Loans</a> – An overview of the most common types of scholarships and loans you can get after completing your FAFSA.
                    </p>
                    <p>
                        <a href="https://studentaid.ed.gov/types/work-study" taget="_blank">Work-Study Aid</a> – Get a closer look at the work-study programs available through the federal government. These programs provide an insight into your future career while helping you pay for college at the same time.
                    </p>
                    <p>
                        <a href="https://studentaid.ed.gov/types/loans" target="_blank">Federal Student Loans</a> – Review different types of student loans you may need to supplement your financial aid, along with repayment options to explore.
                    </p>
                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Advice from Previous Scholarship Winners
                    </h2>
                    <p>
                        Check out these videos from previous scholarship winners to see what they did to achieve their victory. You can also connect with previous winners through ScholarshipOwl (LINK) to get one-on-one advice from the successful applicants.
                    </p>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/VLuvC9MTOTM" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/774h7ke8vDU" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/kvr3A157o9M" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/Tr_zjqK45VM" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- button -->
                    <div class="button-wrapper">
                        <p class="h5 mod-text-size text-blue text-center text-semibold">Connect with Previous Scholarship Winners</p>
                        <a href="{{ url_builder('register') }}" class="btn btn-primary btn-block text-uppercase">Sign Up</a>
                    </div>

                    <h2 class="h3 mod-text-size mod-margin text-left">
                        Finding and Applying to College
                    </h2>
                    <p>
                        The college application process can be just as intimidating as applying for scholarships. Getting into the right school will ensure that you have the education you need for a successful career. With the resources listed below, you'll be able to find and get accepted into the perfect school for your professional goals.
                    </p>
                    <h2 class="h3 mod-text-size mod-margin text-left">
                        How to Find the Right College
                    </h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/uK_8s3PeRVM" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="http://www.petersons.com/college-search.aspx" target="_blank">Peterson's Online College Search</a> – A three-step college matching tool that will help you narrow down colleges and universities you may want to apply to.
                    </p>
                    <p>
                        <a href="https://bigfuture.collegeboard.org/find-colleges/how-find-your-college-fit" target="_blank">Finding Your College Fit</a> – A large online resource center from the College Board that explains how to compare colleges, how to search for colleges, how to reach out to colleges, and much more.
                    </p>
                    <p>
                        <a href="http://www.forbes.com/top-college-quiz/" target="_blank">Which College Is Right for You?</a> – An online quiz from Forbes that will help you determine what type of colleges and universities you should be applying to.
                    </p>
                    <h2 class="h3 mod-text-size mod-margin text-left">
                        College Placement Tests
                    </h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                        <iframe class="embed-responsive-item" width="560" height="315" data-src="//www.youtube.com/embed/IOHiu7QL-CU" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <p>
                        <a href="http://www.pcc.edu/resources/testing/placement/preparing.html" target="_blank">Preparing for Your Placement Tests</a> – A series of PDF guides that explain different types of skills you need for college placement tests, courtesy of Portland Community College. The guides cover writing, reading, math, English as a second language, test-taking strategies, and more.
                    </p>
                    <p>
                        <a href="http://sat.collegeboard.org/practice/sat-practice-test" target="_blank">SAT Practice Tests</a> – Examples of SAT tests you can use to prepare for taking the SAT. The tests are free, and they are completely available online.
                    </p>
                    <p>
                        <a href="http://www.actstudent.org/sampletest/" target="_blank">ACT Practice Tests</a> – Sample ACT questions directly from the makers of the test that will help you prepare for the ACT. The questions are broken down by subject so you can focus on the areas you need the most practice in.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@stop
