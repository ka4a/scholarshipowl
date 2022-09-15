@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle13') !!}
@endsection

@section('content')

<div id="registration-congratulations-head" class="congratulations-head blue-bg clearfix">
	<div class="container">
		<div class="row">
			<div class="text-container text-center text-white">
				<h2 class="text-large text-light">Congratulations!</h2>
				<div class="text-semibold mod-subtitle">You have completed the registration process.</div>
				<div class="text-medium">Our team has begun the application process to your eligible scholarships.</div>
				<div class="diploma"></div>
			</div>
		</div>
	</div>
</div>


<section id="registration-congratulations-body" class="congratulations paleBlue-bg clearfix">
	<div class="container">
		<div class="row">
			<div class="clearfix">
				<div class="left">
					<h3 class="title">
						What's  next?
					</h3>
					<p class="description">
						ScholarshipOwl is at work beginning to apply to all the scholarships you are eligible for.
					</p>
					<p class="description-1">
						So what can you do next to help	increase your scholarship success?
					</p>
				</div>

				<div class="right">

					<div class="left-1">
						<div class="clearfix divider visible-xs-block"></div>
						<h4 class="title">
							<a href="{{ url_builder('my-account') }}">Complete your Profile</a>
						</h4>
						<p class="text-in">
							The more detailed you are with your background the greater the chance is of finding either more scholarships or higher value scholarships.
						</p>
					</div>

					<div class="right-1">
						<h4 class="title">
							<a href="{{ url_builder('additional-services') }}">Let Us help you</a>
						</h4>
						<p class="text-in">
							We offer a wide range of additional services for our members. We are here to help you with the next step.
						</p>
					</div>

					<div class="clearfix"></div>
					<div class="divider hidden-xs"></div>


					<div class="left-1">
						<h4 class="title">
							<a href="{{ url_builder('awards-semester') }}">Win great rewards</a>
						</h4>
						<p class="text-in">
							Let your friends and classmates know about ScholarshipOwl and you can win some great rewards for school.
						</p>
					</div>


					<div class="clearfix visible-xs-block"></div>
					<div class="divider visible-xs-block"></div>

					<div class="right-1">
						<ul class="mini-refer">
							<li role="button" id="invite-email" data-toggle="modal" data-target="#invite-form">
								<img src="https://scholarshipowl.com/assets/img/invite_email.png" hspace="17" width="26" alt="">
								<a href="mailto:">
									<span>Invite by Email</span>
								</a>
							</li>
							<li role="buton" id="invite-fb" data-toggle="modal" data-target="#invite-form">
								<img src="https://scholarshipowl.com/assets/img/invite_fb.png" hspace="17" width="26" alt="">
								<a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=500,width=500');return false;" href="https://www.facebook.com/share.php?u=https://www.facebook.com/pages/Scholarship-Owl/235886926604530">
									<span>Invite by Facebook</span>
								</a>
							</li>
							<li role="button" id="invite-twitter" data-toggle="modal" data-target="#invite-form" class="last">
								<img src="https://scholarshipowl.com/assets/img/invite_twitter.png" hspace="17" width="26" alt="">
								<a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://twitter.com/share?text=I%20just%20applied%20for%20scholarships%20with%20@ScholarshipOwl.%20Come%20try%20it%20out%20&amp;url=https://scholarshipowl.com">
									<span>Invite by Twitter</span>
								</a>
							</li>
						</ul>
					</div>

				</div>
			</div>
		</div>
	</div>
</section>

@include('includes/testimonials')
@stop
