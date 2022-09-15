@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle28') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('partnerships') !!}
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
    <div id="tips_head" class="blue-bg clearfix">
        <div class="container">
            <div class="row">
                <div class="text-container text-center text-white">
                    <h1 class="h2 text-light" id="page-title">
                        Scholarship Owl In the Press
                    </h1>
                    <p class="lead mod-top-header">
                        Want to share the news about ScholarshipOwl with the rest of the world? We welcome press inquiries! Simply reach out through the contact form below, and one of our representatives will get back to you shortly. Most questions are answered within 48 hours.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ScholarshipOwl in the News -->
<section role="region" aria-labelledby="in-the-news" class="section--in-the-news">
    <div class="container-fluid paleBlue-bg">
        <div class="row">
            <div class="container">
                <div class="row">
                    <div class="text-container clearfix">

                        <div class="col-xs-12 text-center">
                            <h2 class="sr-only sr-focusable" id="in-the-news">ScholarshipOwl in the News & Press Kit</h2>
                        </div>

                        <div class="col-xs-12 col-sm-6">
                            <h2 class="h4 text-semibold">ScholarshipOwl in the News</h2>
                            <ul class="list-unstyled text-left list-one-line">
                                <small>
                                    <li>
                                    	<img src="assets/img/as-seen-on/forbes_black.png">
                                        <a href="http://www.forbes.com/sites/annefield/2015/08/30/applying-for-private-scholarships-no-longer-a-wild-goose-chase/" target="_blank" title="Forbes - Applying For Private Scholarships: No Longer A Wild Goose Chase">
                                            Forbes - Applying For Private Scholarships: No Longer A Wild Goose Chase
                                        </a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/gigaom.png">
                                        <a href="https://gigaom.com/2015/09/14/scholarshipowl-uses-big-data-machine-learning-to-fix-the-convoluted-scholarship-application-process/" target="_blank" title="Gigaom - ScholarshipOwl uses big data">
                                            Gigaom - ScholarshipOwl uses big data, machine learning to fix the convoluted scholarship application process
										</a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/redef.png">
                                        <a href="http://redef.com/search/articles/scholarshipowl" target="_blank" title="Redef - ScholarshipOwl">
                                            Redef - ScholarshipOwl
										</a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/tnw.png">
                                        <a href="http://thenextweb.com/insider/2015/07/22/scholarshipowl-automates-college-scholarships-for-students/" target="_blank" title="TheNextWeb - ScholarshipOwl automates college scholartships for students">
                                            TheNextWeb - ScholarshipOwl automates college scholartships for students
                                        </a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/vatornews.png">
                                    	<a href="http://vator.tv/news/2015-07-22-scholarshipowl-launches-to-link-students-with-scholarships#Vv1bkGLyP3Zzemk0.99" target="_blank" title="ScholarshipOwl launches to link students with scholarships">
                                            vatornews - ScholarshipOwl launches to link students with scholarships
                                        </a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/techzulu.png">
                                        <a href="http://techzulu.com/scholarshipowl-one-done-never-miss-a-scholarship-opportunity/" target="_blank" title="TechZulu - ScholarshipOwl | One & Done | Never miss a scholarship opportunity">
                                            TechZulu - ScholarshipOwl | One & Done | Never miss a scholarship opportunity
                                        </a>
                                    </li>
                                    <li>
                                    	  <img src="assets/img/as-seen-on/potential-magazine.png">
                                        <a href="https://www.joomag.com/magazine/potential-magazine-spring-2017/0282455001486656017?short" target="_blank" title="TechZulu - ScholarshipOwl | One & Done | Never miss a scholarship opportunity">
                                            Page 22 - Scholarships, is it worth my time to apply?
                                        </a>
                                    </li>
                                    <li>
                                    	  <img src="assets/img/as-seen-on/huffington.png">
                                        <a href="http://www.huffingtonpost.com/entry/how-to-attend-college-without-going-broke_us_58bedb0de4b0abcb02ce225b" target="_blank" title="TechZulu - ScholarshipOwl | One & Done | Never miss a scholarship opportunity">
                                            How to Attend College Without Going Broke
                                        </a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/product_hunt.png">
                                        <a href="http://www.producthunt.com/tech/scholarshipowl" target="_blank" title="Product Hunt - ScholarshipOwl on ProductHunt">
                                            Product Hunt - ScholarshipOwl on ProductHunt
                                        </a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/la-biz.png">
                                        <a href="http://www.bizjournals.com/losangeles/news/2015/07/22/scholarshipowl-feathers-students-nests-with.html" target="_blank" title="L.A. Biz - ScholarshipOwl feathers students’ nests with scholarship money">
                                            L.A. Biz - ScholarshipOwl feathers students’ nests with scholarship money
                                        </a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/hello_giggles.png">
                                    	<a href="http://hellogiggles.com/website-connect-perfect-scholarship/" target="_blank" title="Hello Giggles - This website wants to connect you with the perfect scholarship">
                                            Hello Giggles - This website wants to connect you with the perfect scholarship
                                        </a>
                                    </li>
                                    <li>
                                    	<img src="assets/img/as-seen-on/uloop.png">
                                    	<a href="http://www.uloop.com/news/view.php/195074/scholarshipowls-new-tool-helps-college-students-find-scholarships" target="_blank" title="Uloop - ScholarshipOwl's New Tool Helps College Students Find Scholarships">
                                            Uloop - ScholarshipOwl's New Tool Helps College Students Find Scholarships
                                        </a>
                                    </li>
                                    <li>
                                        <img src="assets/img/as-seen-on/abc11.png">
                                        <a href="http://abc11.com/finance/scholarship-owl-helps-students-find-money-for-college/1340269" target="_blank" title="ABC11 - Scholarship Owl helps students find money for college">
                                            ABC11 - Scholarship Owl helps students find money for college
                                        </a>
                                    </li>
                                    <li>
                                        <img src="assets/img/as-seen-on/tech_crunch.png">
                                        <a href="https://techcrunch.com/2016/06/10/scholarships-are-the-new-sweepstakes/" target="_blank" title="TechCrunch - Scholarships are the new sweepstakes">
                                            TechCrunch - Scholarships are the new sweepstakes
                                        </a>
                                    </li>
                                    <li class="press-with-sev-cont"  style="padding-bottom: 0;">
                                        <img src="assets/img/as-seen-on/college_magazine.png">
                                        <ul class="several-items-list">
                                            <li>
                                                <a href="http://www.collegemagazine.com/top-10-scholarships-every-college-student-know/" target="_blank" title="College Magazine - Top 10 Scholarships Every College Student Should Know About">
                                                    College Magazine - Top 10 Scholarships Every College Student Should Know About
                                                </a>
                                            </li>
                                            <li>
                                                <a href="http://www.collegemagazine.com/10-reasons-scholarshipowl-better-stripping/" target="_blank" title="College Magazine - 10 Reasons Why ScholarshipOwl is Better than Stripping">
                                                    College Magazine - 10 Reasons Why ScholarshipOwl is Better than Stripping
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li>
                                        <img style="height: 13px; width: auto;" src="assets/img/as-seen-on/ebony.png">
                                        <a href="http://www.ebony.com/career-finance/which-colleges-are-the-most-affordable#axzz4va5HVLQS" target="_blank" title="TechCrunch - Scholarships are the new sweepstakes">
                                            A List of the Most Affordable 4-Year Colleges in Each State
                                        </a>
                                    </li>
                                    <li>
                                        <img src="assets/img/as-seen-on/gah.png">
                                        <a href="https://www.highlands.edu/2017/10/10/ghc-named-affordable-four-year-college-georgia/" target="_blank" title="TechCrunch - Scholarships are the new sweepstakes">
                                            GHC named most affordable four-year college in Georgia
                                        </a>
                                    </li>
                                    <li>
                                        <img src="assets/img/as-seen-on/wallet-hacks.png">
                                        <a href="https://wallethacks.com/scholarship-owl-review/" target="_blank" title="Wallet Hacks - Can this freemium service help you win scholarships?">
                                            Can this freemium service help you win scholarships?
                                        </a>
                                    </li>
                                    <li>
                                        <img style="height: 23px" src="assets/img/as-seen-on/news-watch.png">
                                        <a href="https://newswatchtv.com/2019/05/06/scholarshipowl-newswatch-review/" target="_blank" title=" ScholarshipOwl – Have Quick and Easy Access to Several Scholarship Opportunities">
                                             ScholarshipOwl – Have Quick and Easy Access to Several Scholarship Opportunities
                                        </a>
                                    </li>
                                    <li>
                                        <img src="assets/img/as-seen-on/badcredit.png" alt="ScholarshipOwl Provides Fast, Streamlined Access to College Funding for Students in the U.S.">
                                        <a href="https://www.badcredit.org/news/scholarshipowl-streamlines-access-to-college-funding/" target="_blank" title=" ScholarshipOwl Provides Fast, Streamlined Access to College Funding for Students in the U.S.">
                                             ScholarshipOwl Provides Fast, Streamlined Access to College Funding for Students in the U.S.
                                        </a>
                                    </li>
                                </small>
                            </ul>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <h2 class="h4 text-semibold">Press Kit</h2>
                    		<ul class="list-unstyled text-left list-one-line">
                                <small>
                                    @foreach ($downloads as $name => $link)
                                    <li>
                                        <a class="wordwrap" href="{{ $link }}" target="_blank">{{ $name }}</a>
                                    </li>
                                    @endforeach
                                </small>
                            </ul>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</section>
@stop
