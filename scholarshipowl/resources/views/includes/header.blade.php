
@if (!empty($payment_show_popup))
{!! Form::hidden("payment_show_popup", $payment_show_popup) !!}
@endif

<header id="header-component" role="banner" aria-label="header">
	<nav class="navbar navbar-default navbar-fixed-top" role="navigation" aria-label="main navigation">
		<div class="container">

			<div id="navbar" class="row">
				<!-- Logo -->
				<div class="logo col-xs-2 col-sm-4 col-md-3 mod-logo">
					<a class="navbar-brand sprite-logo" href="{!! homepage() !!}" title="ScholarshipOwl">
						<h2 class="text-hide" id="site-name">Scholarship Owl</h2>
					</a>
				</div>

				<!-- collapsible menu -->
				<div class="col-xs-12 col-sm-4 navbar-collapse-xs navbar-collapse collapse">
					<div id="navbar-collapse">
						<ul class="nav navbar-nav menu-main hidden-sm" role="menu">
							<!-- INFO -->
							<!-- visible on XS -->
							<li class="hidden-sm hidden-md hidden-lg">
								<a data-toggle="collapse" href="#collapse-info" aria-expanded="false" aria-controls="collapse-about-us">
									Info <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul id="collapse-info" aria-expanded="false" class="list-unstyled menu-link-mod-xs collapse">
									<li>
										<a href="{!! url_builder('faq') !!}">
											FAQ
										</a>
									</li>
									<li>
										<a href="{!! url_builder('what-people-say-about-scholarshipowl') !!}">
											Reviews
										</a>
									</li>
									<li class="menu-item">
										<a href="{!! url_builder('awards/scholarship-winners') !!}">
											Scholarship Winners
										</a>
									</li>
									<li class="hidden">
										<a href="{!! url_builder('refer-a-friend') !!}">
											Refer A Friend
										</a>
									</li>
								</ul>
							</li>
							<!-- visible on md and lg -->
							<li class="dropdown hidden-xs hidden-sm">
								<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
									Info <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
									<li class="menu-item">
										<a href="{!! url_builder('faq') !!}">
											FAQ
										</a>
									</li>
									<li class="menu-item">
										<a href="{!! url_builder('what-people-say-about-scholarshipowl') !!}">
											Reviews
										</a>
									</li>
									<li class="menu-item">
										<a href="{!! url_builder('awards/scholarship-winners') !!}">
											Scholarship Winners
										</a>
									</li>
									<li class="hidden">
										<a href="{!! url_builder('refer-a-friend') !!}">
											Refer A Friend
										</a>
									</li>
								</ul>
							</li>

							<!-- ABOUT US -->
							<!-- visible on XS-->
							<li class="visible-xs">
								<a data-toggle="collapse" href="#collapse-about-us" aria-expanded="false" aria-controls="collapse-about-us">
									About us <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul id="collapse-about-us" aria-expanded="false" class="list-unstyled menu-link-mod-xs collapse">
									<li>
										<a href="{!! url_builder('about-us') !!}">
											About us
										</a>
									</li>
									<li>
										<a href="{!! url_builder('press') !!}">
											Press
										</a>
									</li>
								</ul>
							</li>
							<!-- visible on MD and LG -->
							<li class="dropdown hidden-xs hidden-sm">
								<a href="#" onclick="return false" class="dropdown-toggle" data-toggle="dropdown">
									About us <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="{!! url_builder('about-us') !!}">
											About us
										</a>
									</li>
									<li>
										<a href="{!! url_builder('press') !!}">
											Press
										</a>
									</li>
								</ul>
							</li>

							<!-- SERVICES -->
							<!-- visible on xs-->
							<li class="hidden-sm hidden-md hidden-lg">
								<a data-toggle="collapse" href="#collapse-services" aria-expanded="false" aria-controls="collapse-services">
									Services <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>

								<ul id="collapse-services" aria-expanded="false" class="list-unstyled menu-link-mod-xs collapse">
									<li>
										<a href="{!! url_builder('additional-services') !!}">
											Additional Services
										</a>
									</li>
									<li class="hidden">
										<a href="{!! url_builder('premium-services') !!}">
											Why We Charge For The Premium Services
										</a>
									</li>
									<li>
										<a href="{!! url_builder('ebook') !!}">
											eBook
										</a>
									</li>
									<li>
										<a href="{!! url_builder('offer-wall') !!}">
											Featured Scholarships
										</a>
									</li>
									<li>
										<a href="https://scholarshipowl.studentbeans.com/us" target="_blank">
											Student discount
										</a>
									</li>
									<li>
										<a href="{!! url_builder('jobs') !!}">
											jobs
										</a>
									</li>
								</ul>
							</li>
							<!-- visible on MD and LG -->
							<li class="dropdown hidden-xs hidden-sm">
								<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
									Services <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="{!! url_builder('additional-services') !!}">
											Additional Services
										</a>
									</li>
									<li class="hidden">
										<a href="{!! url_builder('premium-services') !!}">
											Why We Charge For The <br />Premium Services
										</a>
									</li>
									<li>
										<a href="{!! url_builder('ebook') !!}">
											eBook
										</a>
									</li>
									<li>
										<a href="{!! url_builder('offer-wall') !!}">
											Featured Scholarships
										</a>
									</li>
									<li>
										<a href="https://scholarshipowl.studentbeans.com/us" target="_blank">
											Student discount
										</a>
									</li>
									<li>
										<a href="{!! url_builder('jobs') !!}">
											Jobs
										</a>
									</li>
								</ul>
							</li>

							<!-- PARTNER -->
							<!-- visible on xs-->
							<li class="hidden-sm hidden-md hidden-lg hidden">
								<a data-toggle="collapse" href="#collapse-partner" aria-expanded="false" aria-controls="collapse-services">
									Partner <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul id="collapse-partner" aria-expanded="false" class="list-unstyled menu-link-mod-xs collapse">
									<li>
										<a href="{!! url_builder('partners') !!}">
											Partners
										</a>
									</li>
									<li>
										<a href="{!! url_builder('advertise-with-us') !!}">
											Advertise With Us
										</a>
									</li>
									<li>
										<a href="{!! url_builder('partnerships') !!}">
											Partnerships
										</a>
									</li>
								</ul>
							</li>
							<!-- visible on MD and LG -->
							<li class="dropdown hidden-xs hidden-sm hidden">
								<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
									Partner <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="{!! url_builder('partners') !!}">
											Partners
										</a>
									</li>
									<li>
										<a href="{!! url_builder('advertise-with-us') !!}">
											Advertise With Us
										</a>
									</li>
									<li>
										<a href="{!! url_builder('partnerships') !!}">
											Partnerships
										</a>
									</li>
								</ul>
							</li>

							<li class="hidden-sm hidden-md hidden-lg">
								<a data-toggle="collapse" href="#collapse-contact" aria-expanded="false" aria-controls="collapse-services">
									Contact <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul id="collapse-contact" aria-expanded="false" class="list-unstyled menu-link-mod-xs collapse">
									<li>
										<a href="{!! url_builder('contact') !!}">
											Contact
										</a>
									</li>
									<li>
										<a href="{!! url_builder('list-your-scholarship') !!}">
											List your scholarship
										</a>
									</li>
									<li>
										<a href="{!! url_builder('partners') !!}">
											Partners
										</a>
									</li>
								</ul>
							</li>
							<li class="hidden-sm hidden-md hidden-lg">
								<a href="http://blog.scholarshipowl.com" target="_blank">
									Blog
								</a>
							</li>

							<li class="dropdown hidden-xs">
								<a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">
									Contact <span class="caret hidden-xs"></span><span class="plus-minus visible-xs-inline-block"></span>
								</a>
								<ul class="dropdown-menu" role="menu">
									<li>
										<a href="{!! url_builder('contact') !!}">
											Contact
										</a>
									</li>
									<li>
										<a href="{!! url_builder('list-your-scholarship') !!}">
											List your scholarship
										</a>
									</li>
									<li>
										<a href="{!! url_builder('partners') !!}">
											Partners
										</a>
									</li>
									<li class="dropdown hidden-xs hidden-md hidden-lg">
										<a href="http://blog.scholarshipowl.com" target="_blank">
											Blog
										</a>
									</li>
								</ul>
							</li>
							<li class="hidden-sm hidden-xs">
								<a href="http://blog.scholarshipowl.com" target="_blank">
									Blog
								</a>
							</li>
						</ul>
					</div>
				</div>

				<div class="col-xs-2 col-sm-1 no-gutter-this hidden-md hidden-lg mod-navbar-toggle pull-right">
					<div class="navbar-toggle-container">
						<button type="button" class="navbar-toggle pull-right" data-toggle="collapse" data-target=".navbar-collapse">
							<span class="sr-only">Toggle navigation</span>
						</button>

						<div class="spinner-master2">
							<input type="checkbox" id="spinner-form2" />
							<label for="spinner-form2" class="spinner-spin2">
								<div class="spinner2 diagonal part-1"></div>
								<div class="spinner2 horizontal"></div>
								<div class="spinner2 diagonal part-2"></div>
							</label>
						</div>

					</div>
				</div>

				@if (isset($user))
				<div class="col-xs-8 col-sm-7 col-md-4 navbar-height no-gutter-this">
					<div class="navbar-nav-user-container pull-right">
						<div class="navbar-height no-gutter btn-block">
							@if(setting("content.phone.show") == "yes")
							<div class="phoneNumber vertical-center text-right pull-left">
								<span class="glyphicon glyphicon-earphone"></span>
								<span class="phone-number" data-phone="{!! setting("content.phone") !!}">
									{!! setting("content.phone") !!}
								</span>
							</div>
							@endif

							<ul id="userMenu" class="nav navbar-nav menu-user pull-right vertical-center" role="menu">
								<li class="dropdown">
									<span></span> <!-- shadow correction -->
									<a href="javascript:void(0)" data-toggle="dropdown" class="btn dropdown-toggle mod-dropdown pull-right">
										<img height="42" width="42" class="userpic hidden-xs" src="/assets/img/register_userpic.png">
										<div id="package">
											<!-- subscription info -->
											<div class="packageWrapper pull-left"></div>

											<!-- mail notification -->
											<div class="mail-notification-wrapper pull-left hidden">

												<span class="glyphicon icon-mail"></span>
												<span class="label label-warning" id="message-count"></span>
											</div>
										</div>

										<span class="username">Hi <span class="bold">{!! $user->getProfile()->getFullName() !!}</span></span>
										<span class="caret"></span>
									</a>
									<ul class="dropdown-menu clearfix" role="menu">
										<div class="user-dropdown clearfix">
											<div class="menu-userinfo">
												<img height="60" width="60" class="userpic visible-xs-inline-block" src="/assets/img/register_userpic.png">
												<div class="title">{!! $user->getProfile()->getFullName() !!}</div>
												<div class="desc">
													{!! $user->getProfile()->getCity() !!} / {!! getinfo("State", $user->getProfile()->getState()->getStateId()) !!}
												</div>
												<div class="clearfix"></div>
												<div class="profile-button">
													<div id="myProfile">
														<a id="1444" href="{!! url_builder('my-account') !!}" class="btn btn-primary btn-block text-center text-uppercase">My profile</a>
													</div>
													<div id="myApplications">
														<a id="my-applicationsions" href="{!! url_builder('my-applications') !!}" class="btn btn-primary btn-block text-center text-uppercase">My applications</a>
													</div>
													<div id="applyNow">
														<a id="applyNowBtn" href="{!! url_builder('select') !!}" class="btn btn-primary btn-block text-center text-uppercase">
															Scholarship Matches
														</a>
													</div>
													<div id="upgradeAccount">
														<a id="upgradeAccountBtn" class="btn btn-warning btn-block text-center text-uppercase {!! !$isMobile ? "GetMoreScholarshipsButton" : "" !!}" href="{!! !$isMobile ? "#" : url_builder('upgrade-mobile') !!}" data-source-page="{!! Request::path() !!}">
															Upgrade
														</a>
													</div>
												</div>
											</div>

											<div class="menu-useractions">
												<div class="col-xs-5 .vertical-center-parent">
													<a class="btn btn-xs btn-block upgrade vertical-center {!! !$isMobile ? "GetMoreScholarshipsButton" : "" !!}" href="{!! !$isMobile ? "#" : url_builder('upgrade-mobile') !!}" data-source-page="{!! Request::path() !!}">Upgrade</a>
												</div>
												<div class="col-xs-5 col-xs-offset-2 vetical-center-parent">
													<a class="btn btn-default btn-block btn-xs signout vertical-center" href="{!! route('logout') !!}">Sign out</a>
												</div>
											</div>

										</div>
									</ul>
								</li>
							</ul>
						</div>
					</div>
				</div>

				@else

				<div class="col-xs-8 col-sm-7 col-md-4 pull-right navbar-height no-gutter-this">
					<div class="navbar-nav-logginApply-container pull-right">
						<div class="navbar-height">
							@if(setting("content.phone.show") == "yes")
							<div class="phoneNumber vertical-center text-right pull-left">
								<span class="glyphicon glyphicon-earphone"></span>
								<span class="phone-number" data-phone="{!! setting("content.phone") !!}">
									{!! setting("content.phone") !!}
								</span>
							</div>
							@endif

							<div class="apply-btn-container center-block vertical-center pull-right">
								<a id="register" class="btn btn-warning btn-block btn-apply" href="{!! url_builder('register') !!}">Apply</a>
							</div>
							<div class="login-btn-container vertical-center pull-right">
								<a class="btn btn-block login-btn btn-block center-block text-center">Login</a>
							</div>

						</div>
					</div>
				</div>
				@endif

			</div>
		</div>
		<div class="clearfix"></div>

		@if (!isset($user))
		@if ($social)
		@include('includes/social')
		@endif
		@endif

	</nav>


	<!-- Collect the nav links, forms, and other content for toggling -->
	<nav class="navbar-collapse navbar-collapse-sm collapse" id="navbar-collapse-2">
		<div class="container">
			<div class="row">
				<ul id="navbarNavSm" class="nav navbar-nav menu-main-sm visible-sm-block first-level" role="menu">
					<li>

						<!-- INFO -->
						<ul role="menu" class="menu-main-sm_second-level col-sm-3">
							<li class="menu-item mod-first-child">
								<p class="text-lg">Info</p>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('faq') !!}">
									FAQ
								</a>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('what-people-say-about-scholarshipowl') !!}">
									Reviews
								</a>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('awards/scholarship-winners') !!}">
									Scholarship Winners
								</a>
							</li>
							<li class="menu-item hidden">
								<a href="{!! url_builder('refer-a-friend') !!}">
									Refer A Friend
								</a>
							</li>
						</ul>
					</li>

					<li>
						<!-- ABOUT US -->
						<ul role="menu" class="menu-main-sm_second-level col-sm-3">
							<li class="menu-item mod-first-child">
								<p class="text-lg">About us</p>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('about-us') !!}">
									About us
								</a>
							</li>
							<li class="menu-item">
								<a href="http://blog.scholarshipowl.com" target="_blank">
									Blog
								</a>
							</li>
							<li class="menu-item hidden">
								<a href="{!! url_builder('giving-back-to-students') !!}">
									Giving Back to Students/Society
								</a>
							</li>
						</ul>
					</li>

					<li>
						<!-- SERVICES -->
						<ul role="menu" class="menu-main-sm_second-level col-sm-3">
							<li class="menu-item mod-first-child">
								<p class="text-lg">Services</p>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('additional-services') !!}">
									Additional Services
								</a>
							</li>
							<li class="menu-item hidden">
								<a href="{!! url_builder('premium-services') !!}">
									Why We Charge For The Premium Services
								</a>
							</li>
							<li class="menu-item mod-last-child">
								<a href="{!! url_builder('ebook') !!}">
									eBook
								</a>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('offer-wall') !!}">
									Featured Scholarships
								</a>
							</li>
							<li class="menu-item">
								<a href="https://scholarshipowl.studentbeans.com/us" target="_blank">
									Student discount
								</a>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('jobs') !!}">
									Jobs
								</a>
							</li>
						</ul>
					</li>


					<li>
						<!-- PARTNER -->
						<ul role="menu" class="menu-main-sm_second-level col-sm-3 hidden">
							<li class="menu-item mod-first-child">
								<p class="text-lg">Partner</p>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('partners') !!}">
									Partners
								</a>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('advertise-with-us') !!}">
									Advertise With Us
								</a>
							</li>
							<li class="menu-item mod-last-child">
								<a href="{!! url_builder('partnerships') !!}">
									Partnerships
								</a>
							</li>
						</ul>


						<!-- CONTACT -->
						<ul role="menu" class="menu-main-sm_second-level col-sm-3">
							<li class="menu-item mod-first-child">
								<p class="text-lg">Contact us</p>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('contact') !!}">
									Contact
								</a>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('list-your-scholarship') !!}">
									List your scholarship
								</a>
							</li>
							<li class="menu-item">
								<a href="{!! url_builder('partners') !!}">
									Partners
								</a>
							</li>
						</ul>

					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- ××× -->
</header>
