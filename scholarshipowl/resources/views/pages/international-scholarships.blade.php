@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('carousel4steps') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('tips') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('advertise') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('partnerships') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('international') !!}
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

<!-- Scholarship Help for International Students header -->
<section role="region" aria-labelledby="page-title">
    <div class="blue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container center-block text-white text-center">
                    <h1 class="h2 text-light" id="page-title">
                        International Students
                    </h1>
                    <p class="lead mod-top-header">
                        International students studying in America  can take advantage of a wide range of scholarships to pay for their degrees. Whether you plan to earn your degree in the US or just study here for a  semester, you can <strong>earn free money for college</strong> to avoid getting into debt. The tips and resources below will help  you find and apply for scholarships as an international college student.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Use ScholarshipOwl to Find International Student Scholarship -->
<section role="region" aria-labelledby="use-scholarshipowl">
    <div class="section--find-international paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center center-block">
                  	<h2 class="h3 mod-text-size text-light" id="use-scholarshipowl">
                        Use ScholarshipOwl to Find <br />International Student Scholarships
                    </h2>
    				<p class="mod-text">
                        The easiest way to find and apply for <strong>international student college scholarships</strong> is to register with ScholarshipOwl. We take the hassle out of searching for  scholarships and filling out applications so you can focus on your education.  Here is a quick look at how it works:
                    </p>

                <!-- carousel -->
                <div id="carousel_4_steps" class="section--find-international-carousel carousel slide carousel_4_steps center-block" data-ride="carousel">

                    <ol class="carousel-indicators">
                        <li data-target=".carousel_4_steps" data-slide-to="0" class="active"><span class="indicator">1</span></li>
                        <li data-target=".carousel_4_steps" data-slide-to="1" class=""><span class="indicator">2</span></li>
                        <li data-target=".carousel_4_steps" data-slide-to="2" class=""><span class="indicator">3</span></li>
                        <li data-target=".carousel_4_steps" data-slide-to="3" class=""><span class="indicator">4</span></li>
                      </ol>
                    <div class="carousel-inner">
                        <div class="item clearfix active">
                            <figure>
                                <img src="assets/img/whatwedo/slider1.png" alt="Fill out Your Profile" class="center-block img-responsive">
                            </figure>

                            <div class="carousel-caption">
                                <div class="text-medium text-semiblod">Fill out Your Profile</div>
                                <div>
                                    <small>
                                        We need to know your name, age, where you're from and some school info. This will give us the info we need to match you to all the relevant international student scholarships. Complete each section of your profile accordingly, so that we can complete your scholarship applications.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="item clearfix">
                            <figure>
                                <img src="assets/img/whatwedo/slider2.png" alt="Review Your Award Offers" class="center-block img-responsive">
                            </figure>
                            <div class="carousel-caption">
                                <div class="text-medium text-semiblod">Review Your Award Offers</div>
                                <div>
                                    <small>
                                        Once we know who you are, we will find a variety of international student scholarships you may be interested in. Look over each one of them to determine which ones you want to apply to. If you want to go for all of them the decision is all yours!
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="item clearfix">
                            <figure>
                                <img src="assets/img/whatwedo/slider3.png" alt="Fill out Special Information" class="center-block img-responsive">
                            </figure>
                            <div class="carousel-caption">
                                <div class="text-medium text-semiblod">Fill out Special Information</div>
                                <div>
                                    <small>
                                        Some international student scholarships require non-standard information. In those situations, we will ask for additional details on your end. This is usually an essay that is specific to a scholarship or organization. Complete any additional information we ask for, and we'll use that for your applications.
                                    </small>
                                </div>
                            </div>
                        </div>
                        <div class="item clearfix">
                            <figure>
                                <img src="assets/img/whatwedo/slider4.png" alt="Let ScholarshipOwl Go to Work for You" class="center-block img-responsive">
                            </figure>
                            <div class="carousel-caption">
                                <div class="text-medium text-semiblod">Let ScholarshipOwl Go to Work for You</div>
                                <div>
                                    <small>
                                        We'll use all of the data you have given us to apply for the international student scholarships you have chosen. We complete applications as soon as you tell us to, so you don't have to worry about missing deadlines. All you have to do is wait for a response from the scholarship board to see what you've won.
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /carousel -->
                                </div>
            </div>
        </div>
    </div>
</section>

<!-- Apply Now -->
<section role="region" aria-labelledby="apply-now">
    <div id="sign_up_now" class="lightBlue-bg clearfix">
        <div class="container center-block">
            <div class="row">
                <div class="text">
                    <h2 class="sr-only" id="apply-now">Apply Now</h2>
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

<!-- Scholarship Tips for International Students -->
<section role="region" aria-labelledby="scholarship-tips">
    <div class="section--scholarship-tips-for-international-students paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container center-block text-center">

                    <h2 class="h3 mod-text-size text-light" id="scholarship-tips">
                        Scholarship Tips for International Students
                    </h2>
                    <p class="lead mod-text">
                        Here is some quick advice to help you pay for college as an <br />international college student in America.
                    </p>

                </div>
                <div class="text-container text-container-narrow mod-text-container center-block text-center">

                    <h2 class="text-semibold text-blue">
                        Participate in Work Study Programs
                    </h2>
                    <p class="divider-dashed">
                    	Work-study programs can help you pay for college and get direct insight into your future career. Some international students are able to earn free housing and food cards through their college's work study programs. Learn about the job you want to pursue and get the aid you need to cover your expenses as you complete your degree in America.
                      </p>

                    <h2 class="text-semibold text-blue">Edit Your Essays with a Native English Speaker</h2>
                    <p class="divider-dashed">
                   	If English is not your first language, you may ask a native English speaker to read over your scholarship application essays. Professors, counselors, and peers may work with you to make your essays compelling and error-free. You can also take advantage of our <a href="#">scholarship editing services</a> to get advanced help writing your essays. </p>

                    <h2 class="text-semibold text-blue">Maintain Good Grades in School</h2>
                    <p class="divider-dashed">
                    	Whether you already live in the US or you plan in the future, getting good grades will significantly improve your chances of winning scholarships and grants. Maintain a high grade point average in high school and college so you can wow the scholarship committee with your achievements.
                  	</p>
                    <h2 class="text-semibold text-blue">Sign Up for Community Service Opportunities</h2>
                    <p class="divider-dashed">
                    	Being an active member of the community will make your scholarship applications look even better. Scholarship committees love to see applicants who are making a difference in the world. Volunteer with a local organization to teach a foreign language, clean up the city, educate young people, work with the elderly, or do anything else that improves someone else's life. You'll feel great for helping others, and you'll have something new to add to your scholarship applications!
                  	</p>
                    <h2 class="text-semibold text-blue">Always Look for More Scholarships</h2>
                    <p class="divider-dashed">
                   	You can never have too many scholarships as an international college student. Apply for as many awards as possible so you can <strong>graduate debt-free</strong>. We get new scholarships every single month here at ScholarshipOwl, and we'll notify you when a new award comes in that you qualify for. <a href="{{ url_builder('register') }}">Sign up today</a> to discover all the amazing award options available to you!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Online Resources for International Students -->
<section role="region" aria-labelledby="online-resources">
    <div class="section--online-resources-for-international-students clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container center-block text-center">

                    <h2 class="h3 mod-text-size text-light text-center" id="online-resources">
                        Online Resources for International Students
                    </h2>
                    <p class="lead text-center">
                        Here are some awesome resources about international scholarship applications from around the web. Learn from professors, colleges, and successful international students who have earned money to pay for their education.
                    </p>

                    <div class="text-container center-block mod-text-container">

                       	<h2 class="h3">
                            Links to Online Resources
                        </h2>

                        <p class="divider-dashed">
                            <big>
                                <a class="text-semibold" href="http://www.usnews.com/education/blogs/international-student-counsel/2013/11/05/3-ways-international-students-can-boost-scholarship-chances" target="_blank">3 Ways to Boost Your Scholarship Chancesx</a>
                            </big>
                            <br />
                            A quick and concise guide to improving your chances of getting scholarship  money as an international student.
                        </p>
                        <p class="divider-dashed">
                            <big>
                                <a class="text-semibold" href="http://www.snre.umich.edu/current_students/career_services/international_development_careers/top_ten_tips" target="_blank">Career Tips for International Students</a>
                            </big>
                            <br />
                            Learn how to enhance your college education and find the perfect career as an  international student in America.
                        </p>
                        <p class="divider-dashed">
                            <big>
                                <a class="text-semibold" href="http://blog.frontrange.edu/2013/04/08/5-tips-for-international-students/" target="_blank">Tips to Be a Successful International Student</a> <br />
                            </big>
                            Easy ways to improve your educational experiences in America, courtesy of  Front Range Community College.
                        </p>
                        <p class="divider-dashed">
                            <big>
                                <a class="text-semibold" href="http://blog.peertransfer.com/2013/01/02/4-tips-for-international-students-to-make-friends-in-the-u-s/" target="_blank">Tips for Making Friends</a>
                            </big>
                            <br />
                            Build lifelong relationships with friends in colleges and share in multi-cultural  experiences with these great tips.
                        </p>

                    </div>

                    <div class="text-container lightBlue-bg">

                        <h2 class="h3 mod-text-size text-semibold text-center">Videos about <br />International Student Scholarships</h2>

                        <!-- video -->
                        <div class="embed-responsive embed-responsive-16by9 yt-containers">
                          <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/u5v332XCGac" frameborder="0" allowfullscreen></iframe>
                        </div>

                        <!-- video -->
                        <div class="embed-responsive embed-responsive-16by9 yt-containers">
                          <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/9A5wnZuMuH4" frameborder="0" allowfullscreen></iframe>
                        </div>

                        <!-- video -->
                        <div class="embed-responsive embed-responsive-16by9 yt-containers">
                          <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/zKrToqjeJT8" frameborder="0" allowfullscreen></iframe>
                        </div>

                        <!-- video -->
                        <div class="embed-responsive embed-responsive-16by9 yt-containers">
                          <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/bmTawu5anH8" frameborder="0" allowfullscreen></iframe>
                        </div>

                        <!-- video -->
                        <div class="embed-responsive embed-responsive-16by9 yt-containers">
                          <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/wy5joo5HgoE" frameborder="0" allowfullscreen></iframe>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Helpful Online Resources -->
<section role="region" aria-labelledby="helpful-online-resources">
    <div class="section--helpful-online-resources paleBlue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container center-block text-center">

                    <h2 class="h3 mod-text-size text-light" id="helpful-online-resources">
                        Helpful Online Resources
                    </h2>
                    <p class="lead">
                        <a href="{{ url_builder('register') }}">ScholarshipOwl.com</a> use our online database and free scholarship matching tools to get study abroad scholarships. We have new awards coming in every single month, so you'll always have new scholarships to apply for!
                    </p>

                    <div class="text-container mod-text-container center-block text-center">

                        <p class="divider-dashed">
                            <big>
                                <a class="text-semibold" href="http://www.internationalstudent.com/study-abroad/guide/safety-tips/" target="_blank">Study Abroad Safety Tips</a>
                            </big>
                            <br />
                            Expert advice on how to stay safe while studying abroad so you do not get in trouble in a foreign country.
                        </p>

                        <p class="divider-dashed">
                            <big>
                                <a class="text-semibold" href="http://college.usatoday.com/2015/01/22/studying-abroad-tips-for-getting-acclimated-to-your-new-home/" target="_blank">How to Get Acclimated to Your New Home</a>
                            <br />
                            </big>
                            Learn what you can do to immerse yourself in another culture and make the  most of your time abroad.
                        </p>

                        <p class="divider-dashed">
                            <big>
                                <a class="text-semibold" href="https://www.studentuniverse.com/travel-guides/study-abroad" target="_blank">Study Abroad Travel Guide</a>
                            <br />
                            </big>
                            Read  dozens of articles about traveling and studying abroad.
                        </p>
                    </div>

                </div>

                <div class="text-container lightBlue-bg">

                    <h2 class="h3 mod-text-size text-semibold text-center">Videos about Studying Abroad</h2>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                      <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/s2vk9a90qu8" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                      <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/c7ZJS1fpglY" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                      <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/m3ZbTbdM5Wk" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                      <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/W7pR-TcQTms" frameborder="0" allowfullscreen></iframe>
                    </div>

                    <!-- video -->
                    <div class="embed-responsive embed-responsive-16by9 yt-containers">
                      <iframe class="embed-responsive-item" width="560" height="315" data-src="https://www.youtube.com/embed/rmYMqskkoa0" frameborder="0" allowfullscreen></iframe>
                    </div>


                </div>
            </div>
        </div>
    </div>
</section>
@stop
