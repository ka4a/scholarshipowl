<section role="region" aria-label="Test your eligibility">
	<div class="eligibility-wrapper">
		<div class="container">
			<div class="row">

				<!-- Eligibility -->
				<div id="eligibility" class="center-block clearfix">

					<div class="content eligibility-section">
						@if(!\App\Entity\FeatureSet::config()->getContentSet()->isHpDoublePromotionFlag())
							@include('includes/speech-bubble')
						@endif
						<figure class="hidden-sm hidden-xs">
							<div class="mascot"></div>
							<div id="alertFillContainer" class="alert alert-dismissible hidden" data-alertid="errorNotification" role="alert">
								<div id="alertFill" class="center-block">
									<button type="button" class="close" data-dismiss="alert" aria-label="Close">
										<span aria-hidden="true">&times;</span>
									</button>
									You must <strong>fill all required<br> fields</strong> in order to apply.
								</div>
							</div>
						</figure>
						<div class="eligibility">
							<div class="eligibility-heading">

								<h1 class="h2">{!! \App\Entity\FeatureSet::config()->getContentSet()->mapHomepageHeader() !!}</h1>

								@if(!\App\Entity\FeatureSet::config()->getContentSet()->isHpYdiFlag())
									<div class="youDeserveItP">
										<div>
											<span class="registerTo">Register</span><br />
											<span class="registerTo">to enter our</span><br />
											<span class="registerToPrice">$1,000</span><br />
											<span class="givwaway text-uppercase">scholarship</span>
										</div>
									</div>
								@endif
							</div>

							<div class="eligibility-separator"></div>

							<div class="eligibility-inner-wrapper clearfix">

								<div class="col-xm-12 col-sm-10 col-sm-offset-1 clearfix">

									<form id="registerForm" name="checkYourChances" class="form clearfix ajax_form" role="form" action="{!! url_builder('post-eligibility') !!}" method="POST">
										{!! Form::token() !!}
										{!! Form::hidden("_return", url_builder("register")) !!}


									<div class="row">
										<div class="col-sm-6 odd">

											<div class="form-group date clearfix" data-toggle="tooltip" data-original-title="Enter your date of birth!" data-placement="top" data-trigger="manual">
												<!-- Enter your birthdate -->

												<div id="birth-date" class="button-group">
													<label class="sr-only">Month</label>
													{!!
														Form::select('birthday_month', $options['birthday_months'], @$session['birthday_month'],
														array('class'=>'selectpicker month pull-left', 'data-width' => '30%', 'data-size'=>'7', 'data-container'=>'body', 'data-live-search'=>'true', 'title' => 'Month', 'required' => ''))
													!!}

													<label class="sr-only">Day</label>
													{!!
														Form::select('birthday_day', $options['birthday_days'], @$session['birthday_day'],
														array('class'=>'selectpicker day pull-left', 'data-width' => '30%', 'data-size'=>'7', 'data-container'=>'body', 'data-live-search'=>'true', 'title' => 'Day', 'required' => ''))
													!!}

													<label class="sr-only">Year</label>
													{!!
														Form::select('birthday_year', $options['birthday_years'], @$session['birthday_year'],
														array('class'=>'selectpicker year pull-left', 'data-width' => '40%', 'data-size'=>'7', 'data-container'=>'body', 'data-live-search'=>'true', 'title' => 'Year', 'required' => ''))
													!!}
												</div>
											</div>
										</div>

										<div class="col-sm-6 even">
											<div id="Gender" class="form-group">
												<!-- Select gender -->
												<label class="sr-only">Gender</label>
												{!!
													Form::select('gender', $options['gender'], @$session['gender'],
													array('class'=>'selectpicker Gender', 'data-title' => 'Gender', 'data-container'=>'body', 'data-width' => '100%', 'data-bv-field' => 'gender'))
												!!}
												</div>
											</div>

										<div class="col-sm-6 odd">
											<div class="form-group">
												<!-- Current School level -->
												<label for="school_level_id" class="sr-only">Current School Level</label>
												<div class="selectContainer">
												{!!
													Form::select('school_level_id', $options['school_levels'], @$session['school_level_id'],
													array('class'=>'selectpicker school-level', 'data-width' => '100%', 'data-size'=>'8', 'data-container'=>'body', 'title' => 'Current School Level', 'data-bv-field' => 'school_level_id', 'id' => 'school_level_id'))
												!!}
												</div>
											</div>
										</div>


										<div class="col-sm-6 even">
											<div class="form-group">
												<!-- Field of Study -->
												<div class="selectContainer">
												{!!
													Form::select('degree_id', $options['degrees'], @$session['degree_id'],
													array('class'=> 'selectpicker degree', 'data-container'=>'body', 'data-width'=>'100%', 'data-size'=>'7', 'title'=>'Field Of Study', 'data-live-search'=>'true', 'data-bv-field'=>'field_of_study_id'))
												!!}
												</div>
											</div>
										</div>
									</div>
									<div class="clearfix"></div>

									<div id="checkYourChances" class="btn-block">
										<div class="btn btn-lg btn-block btn-warning text-center">
											<button name="signUpBtn" id="sign_up_now_btn" class="EligibilityButton text-uppercase" disabled="true">
												{!! \App\Entity\FeatureSet::config()->getContentSet()->getHpCtaText() !!}
												<div class="arrow-btn hidden-xs">
													<div class="button-loader"></div>
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
											</button>
										</div>
										@include('includes/texts/deserve-it-disclaimer')
									</div>
									@include('includes/texts/details-qualifications-disclaimer')
								</form>

							</div>
                            <div class="application">
								<p class="application__value application-counter">
									<span class="application-counter__bg"></span>
									<span class="application-counter__digit" id="app-counter"
										data-count="{!! \App\Entity\Counter::findByName("application")->getCount() !!}">
										1,000,000
									</span>
								</p>
								<p class="application__text">Total scholarship applications submitted</p>
                            </div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
