@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
	{!! \App\Extensions\AssetsHelper::getCSSBundle('additionalServices') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- Additional Services header -->
<section role="region" aria-labelledby="page-title">
    <div class="section--additional-services-header blue-bg clearfix">
		<div class="container">
			<div class="row">
				<div class="text-container text-center text-white">
					<h1 class="h2 text-light" id="page-title">Additional Services</h1>
					<p class="lead mod-top-header">
						<span class="linebreak-xs">At ScholarshipOwl, we do much more than find scholarships and send out applications. </span><span class="linebreak-xs">
						<strong class="semibold">We are a team of dedicated professionals that are here to help you from start to finish.</strong></span>

						Here is a glance at some of our other services:
					</p>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- List of Additional Services -->
<section role="region" aria-labelledby="list-of-additional-services">
    <div class="section--additional-services lightBlue-bg clearfix">
        <div class="container center-block">
            <div class="row">
                <h2 class="sr-only" id="list-of-additional-services">List of Additional Services</h2>
                <div class="col-xs-12 col-sm-6 item item-webinar">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-spam-protection" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                SPAM protection
                            </h2>
                            <p>
                                <small>
                                    A dedicated mailbox in our members section keeps your personal email clean from unwanted emails. Get news and updates from hundreds of scholarship services without exposing your personal email address.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-scholarship-news">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-scholarship-news" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Scholarship News
                            </h2>
                            <p>
                                <small>
                                    We bring you the latest news in the scholarship world so you can be up to date. We cover everything from new scholarship opportunities to changes in government grants. Ask our reps about the best way to follow.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-essay-writting-help">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-essay-writting-help" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Essay Writing Help
                            </h2>
                            <p>
                                <small>
                                    Crafting a perfect scholarship application improves your chances of getting awards. More often than not, essays are the key to a successful application. We can assist you with essays so that you can hand in a professional, successful essay that you can feel confident about.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-webinars">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-webinars" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Webinars
                            </h2>
                            <p>
                                <small>
                                    We run regular webinars to teach our users about scholarships and education as a whole. We have plenty of fun topics lined up for the future, so watch out for the next ScholarshipOwl webinar.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-success-stories">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-success-stories" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Success Stories
                            </h2>
                            <p>
                                <small>
                                    We connect you with students who have won scholarships you are interested in.
                                    From them, you can learn how to make your application the best it can possibly be.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-application-sourcing-and-planning">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-application-sourcing-and-planing" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Application Sourcing and Planning
                            </h2>
                            <p>
                                <small>
                                    Where do you see yourself in five years? The friendly advisors here at ScholarshipOwl will go over your employment plans for the future and help you find scholarship opportunities to achieve your goals.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-scholarship-ebook">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-scholarship-ebook" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Scholarship eBook
                            </h2>
                            <p>
                                <small>
                                    Get access to our exclusive eBook, The Ultimate Guide to College Scholarships. Learn the do's, the don'ts, the now's, and the never's of scholarship applications to boost your chance of acceptance.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="col-xs-12 col-sm-6 item item-automatic-resubmissions">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-automatic-resubmissions" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Automatic Resubmissions
                            </h2>
                            <p>
                                <small>
                                    Upgrade your membership, and we will automatically re-apply you for recurring scholarships. You won't have to worry about keeping a calendar of awards. We take care of that for you.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>

                <div class="col-xs-12 col-sm-6 item item-financial-aid-consultations">
                    <div class="row">
                        <div class="col-xs-4 col-md-3">
                            <figure class="pull-right">
                                <img class="sprite-responsive-icon-financial-aid-consultations" alt="" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAGIAAABaAQMAAAB5QhGNAAAAA1BMVEX///+nxBvIAAAAAXRSTlMAQObYZgAAABJJREFUeNpjYBgFo2AUjILhDwAE7AABJ87WUwAAAABJRU5ErkJggg==">
                            </figure>
                        </div>
                        <div class="col-xs-8 col-md-9">
                            <h2 class="h5 mod-text-size text-blue text-semibold">
                                Financial Aid Consultations
                            </h2>
                            <p>
                                <small>
                                    We offer free consultation to all of our users, where we answer your questions about the awards we have available. We'll walk you through all of the steps required for the creation of an amazing scholarship application.
                                </small>
                            </p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>


<!-- Testimonials -->
<section role="region" aria-labelledby="testimonials">
    <div class="section--testimonials section--text-carousel">
        <div class="container">
            <div class="row">
                <div class="text-container clearfix">

                    <h2 class="sr-only" id="testimonials">Testimonials</h2>

                    <div class="carousel slide carousel-text text-center" data-ride="carousel">
                        <!-- Indicators -->
                        <ol class="carousel-indicators">
                            <li data-target=".carousel-text" data-slide-to="0" class="active">&#10148;</li>
                            <li data-target=".carousel-text" data-slide-to="1">&#10148;</li>
                            <li data-target=".carousel-text" data-slide-to="2">&#10148;</li>
                            <li data-target=".carousel-text" data-slide-to="3">&#10148;</li>
                            <li data-target=".carousel-text" data-slide-to="4">&#10148;</li>
                            <li data-target=".carousel-text" data-slide-to="5">&#10148;</li>
                            <li data-target=".carousel-text" data-slide-to="6">&#10148;</li>
                         </ol>

                        <!-- Wrapper for slides -->
                        <div class="carousel-inner">
                            <div class="item active">
                                <div class="carousel-content col-xs-12">
                                    <p class="text-medium text-semibold">
                                        Choose  the Right School for You
                                    </p>
                                    <p>
                                        There are so many colleges to choose from  in the US: private universities, public colleges, online learning centers,  vocational schools, and more! Make sure you go to a school that matches your  career goals, personal needs, and budget all at the same time. If you have  already been approved for a scholarship, make sure your new college will accept  it. Picking the right school will put you on the fast track to your degree.
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content col-xs-12">
                                    <p class="text-medium text-semibold">
                                        Start with the Basics
                                    </p>
                                    <p>
                                    	Try to fill your first few semesters with basic Math, English, Natural or physical science, and social science courses. These classes can be used for nearly every major, so you will be prepared in case you change your mind. Talk to your advisor about which core curriculum courses would be best for your current career goals, and leave yourself open to changing your mind in the future.
                                    </p>
                                 </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content col-xs-12">
        	                        <p class="text-medium text-semibold">
                                        Don't Settle Too Soon
                                    </p>
                                    <p>
                                        We have seen far too many ScholarshipOwl students spend years on their degrees, only to change their minds right before graduation and start a new major. Make sure you give yourself time to thoroughly explore your options. Go to a career fair, test out different internships, and think about where you see yourself in five years. Don't rush into a degree until you're ready for it.
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content col-xs-12">
                                    <p class="text-medium text-semibold">
                                        Know Your Personal Limits
                                    </p>
                                    <p>
                                        Don't take on more work than you can handle. If you can only manage 6 hours of school each semester, don’t take on more than you can handle! College is a great opportunity to explore new hobbies, groups, passions, and activities, but don't go too crazy so that you sacrifice your quality of life. Enjoy a little "me" time every now and then so you don't get burned out too quickly.
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content col-xs-12">
                                    <p class="text-medium text-semibold">
                                        Get a Reliable Study Buddy
                                    </p>
                                    <p>
                                        Study buddies aren't just for elementary school. Having a friend in similar classes will give you a chance to study in a whole new way. Get feedback from one another to make sure you understand the material, and double your chances at success! You could build a lifelong bond if everything works out.
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content col-xs-12">
                                    <p class="text-medium text-semibold">
                                        Avoid Those Crazy Cram Sessions!
                                    </p>
                                    <p>
                                        Pulling an all-nighter is a recipe for disaster. Your brain can only handle so much information at once. Take naps after you study so your mind can properly store the information, and try to study in small chunks. One hour a day over the course of a week will leave you much more prepared for a big exam.
                                    </p>
                                </div>
                            </div>
                            <div class="item">
                                <div class="carousel-content col-xs-12">
        	                        <p class="text-medium text-semibold">
                                        Apply for Scholarships – Regularly
                                    </p>
                                    <p>
                                        Don't assume scholarships are only available in the summer. You can apply for scholarships all year long! Premium and Full Access ScholarshipOwl users get free automatic submissions for recurring scholarships, along with a slew of other benefits you're sure to enjoy. Take advantage of the awesome tools we offer on our website, and you can avoid student loans altogether.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@include('includes/refer')
@stop
