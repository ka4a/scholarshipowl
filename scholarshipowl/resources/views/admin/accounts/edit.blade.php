@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Profile</span>
				</div>
				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-up"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>
				<div class="no-move"></div>
			</div>

			<div class="box-content">
				<div id="tabs">
					<ul>
						<li><a href="#tab-profile">Profile Information</a></li>
						<li><a href="#tab-education">Education</a></li>
						<li><a href="#tab-interests">Interests</a></li>
						<li><a href="#tab-location">Location</a></li>
						<li><a href="#tab-account">Account Settings</a></li>
                        @can("update",  \App\Entity\AccountOnBoardingCall::class)
						    <li><a href="#tab-onboarding-calls">Onboarding Calls</a></li>
                        @endcan
					</ul>

					<div id="tab-profile">
						<form method="post" action="/admin/accounts/post-edit" class="form-horizontal ajax_form">
							{{ Form::token() }}
							{{ Form::hidden("_action", "profile") }}
							{{ Form::hidden("account_id", $account->getAccountId()) }}

							<fieldset>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Domain</label>
                                    <div class="col-sm-6">
                                        {{ Form::text('domain', $account->getDomain(), ['class' => 'form-control', 'readonly' => true]) }}
                                    </div>
                                </div>

								<div class="form-group">
									<label class="col-sm-3 control-label">First Name</label>
									<div class="col-sm-6">
										{{ Form::text('first_name', $account->getProfile()->getFirstName(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Last Name</label>
									<div class="col-sm-6">
										{{ Form::text('last_name', $account->getProfile()->getLastName(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Phone</label>
									<div class="col-sm-3">
										{{
											Form::text('phone', $account->getProfile()->getPhone(), [
												'class' => 'form-control',
												'data-phone-type' => $account->getProfile()->getCountry()->getId() === \App\Entity\Country::USA ? 'us' : 'non-us'
											])
										}}
									</div>
								</div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Profile Type</label>
                                    <div class="col-sm-3">
                                        {{ Form::select('profile_type', $options['profile_type'], $account->getProfile()->getProfileType(), array("class" => "populate placeholder select2")) }}
                                    </div>
                                </div>

								<hr />

								<div class="form-group">
									<label class="col-sm-3 control-label">Date Of Birth</label>

									<div class="col-sm-3">
										{{ Form::text('date_of_birth', $account->getProfile()->getDateOfBirth(), array("class" => "form-control date_picker")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Gender</label>
									<div class="col-sm-3">
										{{ Form::select('gender', $options['genders'], strtolower($account->getProfile()->getGender()), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<hr />

								<div class="form-group">
									<label class="col-sm-3 control-label">Citizenship</label>
									<div class="col-sm-6">
										{{ Form::select('citizenship_id', $options['citizenships'],
										$account->getProfile()->getCitizenship() ?
										$account->getProfile()->getCitizenship()->getId() : "",
										array("class" => "populate placeholder select2"))
										}}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Ethnicity</label>
									<div class="col-sm-6">
										{{ Form::select('ethnicity_id', $options['ethnicities'],
										$account->getProfile()->getEthnicity() ?
										$account->getProfile()->getEthnicity()->getId() : "",
										array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Mail Subscription</label>
									<div class="col-sm-3">
										{{ Form::select('is_subscribed', $options['subscriptions'], $account->getProfile()->getIsSubscribed() ? 1 : 0, array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Military Affiliation</label>
									<div class="col-sm-3">
										{{ Form::select('military_affiliation_id',
										 $options['military_affiliations'],
										 $account->getProfile()->getMilitaryAffiliation() ?
										 $account->getProfile()->getMilitaryAffiliation()->getId() : "",
										 array("class" => "populate placeholder select2")) }}
									</div>
								</div>
							</fieldset>

							<fieldset>
								<div class="form-group">
									<div class="col-sm-6">
										<a class="btn btn-primary SaveButton" href="#">Save Profile Information</a>
									</div>
								</div>
							</fieldset>
						</form>
					</div>

					<div id="tab-education">
						<form method="post" action="/admin/accounts/post-edit" class="form-horizontal ajax_form">
							{{ Form::token() }}
							{{ Form::hidden("_action", "education") }}
							{{ Form::hidden("account_id", $account->getAccountId()) }}

							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">School Level</label>
									<div class="col-sm-6">
										{{ Form::select('school_level_id', $options['school_levels'],
										$account->getProfile()->getSchoolLevel() ?
										$account->getProfile()->getSchoolLevel()->getId() : "",
										 array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<hr />

								<div class="form-group">
									<label class="col-sm-3 control-label">Degree</label>
									<div class="col-sm-6">
										{{ Form::select('degree_id', $options['degrees'],
										$account->getProfile()->getDegree() ?
										$account->getProfile()->getDegree()->getId() : "",
										array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Degree Type</label>
									<div class="col-sm-6">
										{{ Form::select('degree_type_id', $options['degree_types'],
										$account->getProfile()->getDegreeType() ?
										$account->getProfile()->getDegreeType()->getId() : "",
										array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">GPA</label>
									<div class="col-sm-6">
										{{ Form::select('gpa', $options['gpas'], $account->getProfile()->getGpa(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Enrolled</label>
                                    <div class="col-sm-6">
                                        {{ Form::select('enrolled', $options['enrolled'],
                                        (int)$account->getProfile()->getEnrolled(),
                                        array("class" => "populate placeholder select2")) }}
                                    </div>
                                </div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Enrollment Year</label>
									<div class="col-sm-6">
										{{ Form::select('enrollment_year', $options['enrollment_years'], $account->getProfile()->getEnrollmentYear(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Enrollment Month</label>
									<div class="col-sm-6">
										{{ Form::select('enrollment_month', $options['enrollment_months'], $account->getProfile()->getEnrollmentMonth(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<hr />

								<div class="form-group">
									<label class="col-sm-3 control-label">High School</label>
									<div class="col-sm-6">
										{{ Form::text('highschool', $account->getProfile()->getHighSchool(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">High School Address</label>
									<div class="col-sm-6">
										{{ Form::text('highschool_address1', $account->getProfile()->getHighschoolAddress1(), ['class' => 'form-control']) }}
                                        {{ Form::text('highschool_address2', $account->getProfile()->getHighschoolAddress2(), ['class' => 'form-control']) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">High School Graduation Year</label>
									<div class="col-sm-6">
										{{ Form::select('highschool_graduation_year', $options['graduation_years'], $account->getProfile()->getHighschoolGraduationYear(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">High School Graduation Month</label>
									<div class="col-sm-6">
										{{ Form::select('highschool_graduation_month', $options['graduation_months'], $account->getProfile()->getHighschoolGraduationMonth(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University</label>
									<div class="col-sm-6">
										{{ Form::text('university', $account->getProfile()->getUniversity(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University\College Address</label>
									<div class="col-sm-6">
										{{ Form::text('university_address1', $account->getProfile()->getUniversityAddress1(), ['class' => 'form-control']) }}
                                        {{ Form::text('university_address2', $account->getProfile()->getUniversityAddress2(), ['class' => 'form-control']) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University 1</label>
									<div class="col-sm-6">
										{{ Form::text('university1', $account->getProfile()->getUniversity1(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University 2</label>
									<div class="col-sm-6">
										{{ Form::text('university2', $account->getProfile()->getUniversity2(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University 3</label>
									<div class="col-sm-6">
										{{ Form::text('university3', $account->getProfile()->getUniversity3(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University 4</label>
									<div class="col-sm-6">
										{{ Form::text('university4', $account->getProfile()->getUniversity4(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University Graduation Year</label>
									<div class="col-sm-6">
										{{ Form::select('graduation_year', $options['graduation_years'], $account->getProfile()->getGraduationYear(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">University Graduation Month</label>
									<div class="col-sm-6">
										{{ Form::select('graduation_month', $options['graduation_months'], $account->getProfile()->getGraduationMonth(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>
							</fieldset>

							<fieldset>
								<div class="form-group">
									<div class="col-sm-6">
										<a class="btn btn-primary SaveButton" href="#">Save Education</a>
									</div>
								</div>
							</fieldset>
						</form>
					</div>

					<div id="tab-interests">
						<form method="post" action="/admin/accounts/post-edit" class="form-horizontal ajax_form">
							{{ Form::token() }}
							{{ Form::hidden("_action", "interests") }}
							{{ Form::hidden("account_id", $account->getAccountId()) }}

							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">Career Goal</label>
									<div class="col-sm-6">
										{{ Form::select('career_goal_id', $options["career_goals"],
										$account->getProfile()->getCareerGoal() ?
										$account->getProfile()->getCareerGoal()->getId() : "",
										array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Study Online</label>
									<div class="col-sm-6">
										{{ Form::select('study_online', $options['study_online'], $account->getProfile()->getStudyOnline(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>
							</fieldset>

							<fieldset>
								<div class="form-group">
									<div class="col-sm-6">
										<a class="btn btn-primary SaveButton" href="#">Save Interests</a>
									</div>
								</div>
							</fieldset>
						</form>
					</div>

					<div id="tab-location">
						<form method="post" action="/admin/accounts/post-edit" class="form-horizontal ajax_form">
							{{ Form::token() }}
							{{ Form::hidden("_action", "location") }}
							{{ Form::hidden("account_id", $account->getAccountId()) }}

							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">Country</label>
									<div class="col-sm-6">
										{{ Form::select('country_id', $options['countries'],
										$account->getProfile()->getCountry() ?
										$account->getProfile()->getCountry()->getId() : "",
										array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">State</label>
									<div class="col-sm-6">
										{{ Form::select('state_id', $options['states'],
										$account->getProfile()->getState() ?
										$account->getProfile()->getState()->getId() : "",
										array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">City</label>
									<div class="col-sm-6">
										{{ Form::text('city', $account->getProfile()->getCity(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Address</label>
									<div class="col-sm-6">
										{{ Form::text('address', $account->getProfile()->getAddress(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Zip</label>
									<div class="col-sm-6">
										{{ Form::text('zip', $account->getProfile()->getZip(), array("class" => "form-control")) }}
									</div>
								</div>
							</fieldset>

							<fieldset>
								<div class="form-group">
									<div class="col-sm-6">
										<a class="btn btn-primary SaveButton" href="#">Save Location</a>
									</div>
								</div>
							</fieldset>
						</form>
					</div>

					<div id="tab-account">
						@if ($can_edit_account == true)
						<form method="post" action="/admin/accounts/post-edit" class="form-horizontal ajax_form">
							{{ Form::token() }}
							{{ Form::hidden("_action", "account") }}
							{{ Form::hidden("account_id", $account->getAccountId()) }}

							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">Email</label>
									<div class="col-sm-6">
										{{ Form::text('email', $account->getEmail(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Username</label>
									<div class="col-sm-6">
										{{ Form::text('username', $account->getUsername(), array("class" => "form-control", "disabled" => "disabled")) }}
									</div>
								</div>

								<hr />

								<div class="form-group">
									<label class="col-sm-3 control-label">Account Status</label>
									<div class="col-sm-6">
										{{ Form::select('account_status_id', $options['account_statuses'], $account->getAccountStatus()->getId(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>
								{{ Form::hidden('account_type_id', 2)  }}
								<hr />

								<div class="form-group">
									<label class="col-sm-3 control-label">Password</label>
									<div class="col-sm-6">
										{{ Form::password('password', [], array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Retype Password</label>
									<div class="col-sm-6">
										{{ Form::password('retype_password', [], array("class" => "form-control")) }}
									</div>
								</div>

                                <hr />

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Recurrence Setting</label>
                                    <div class="col-sm-6">
                                        {{ Form::select('recurring_application', $options['recurrence_settings'], $account->getProfile()->getRecurringApplication(), ['class' => 'populate placeholder select2']) }}
                                    </div>
                                </div>


								<div class="form-group">
									<label class="col-sm-3 control-label">Do Not Sell Personal Information</label>
									<div class="col-sm-6">
										{{ Form::checkbox('sell_information', 0, 1, ['class' => 'populate placeholder select3 hidden']) }}
										{{ Form::checkbox('sell_information', 1, $account->isSellInformation(), ['class' => 'populate placeholder select3']) }}
									</div>
								</div>

							</fieldset>

							<fieldset>
								<div class="form-group">
									<div class="col-sm-6">
										<a class="btn btn-primary SaveButton" href="#">Save Account Settings</a>
									</div>
								</div>
							</fieldset>
						</form>
						@else
						<form action="#" class="form-horizontal ajax_form">
							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">Email</label>
									<div class="col-sm-6">
										<input type="text" disabled="disabled" class="form-control" value="{{ $account->getEmail() }}" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Username</label>
									<div class="col-sm-6">
										<input type="text" disabled="disabled" class="form-control" value="{{ $account->getUsername() }}" />
									</div>
								</div>

								<hr />

								<div class="form-group">
									<label class="col-sm-3 control-label">Account Status</label>
									<div class="col-sm-6">
										<input type="text" disabled="disabled" class="form-control" value="{{ $account->getAccountStatus() }}" />
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Account Type</label>
									<div class="col-sm-6">
										<input type="text" disabled="disabled" class="form-control" value="{{ $account->getAccountType() }}" />
									</div>
								</div>

							</fieldset>
						</form>
						@endif
					</div>

                    @can("update", \App\Entity\AccountOnBoardingCall::class)
                    <div id="tab-onboarding-calls">
                        <form method="post" action="/admin/accounts/post-edit" class="form-horizontal ajax_form">
                            {{ Form::token() }}
                            {{ Form::hidden("_action", "onboarding_calls") }}
                            {{ Form::hidden("account_id", $account->getAccountId()) }}

                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Call1</label>
                                    <div class="col-sm-6">
                                        {{ Form::select('call1', $options['onboarding_calls'],
                                        isset($onboardingCalls) ?
                                        (int)$onboardingCalls->getCall1() : "",
                                        array("class" => "populate placeholder select2")) }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Call2</label>
                                    <div class="col-sm-6">
                                        {{ Form::select('call2', $options['onboarding_calls'],
                                        isset($onboardingCalls) ?
                                        (int)$onboardingCalls->getCall2() : "",
                                        array("class" => "populate placeholder select2")) }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Call3</label>
                                    <div class="col-sm-6">
                                        {{ Form::select('call3', $options['onboarding_calls'],
                                        isset($onboardingCalls) ?
                                        (int)$onboardingCalls->getCall3() : "",
                                        array("class" => "populate placeholder select2")) }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Call4</label>
                                    <div class="col-sm-6">
                                        {{ Form::select('call4', $options['onboarding_calls'],
                                        isset($onboardingCalls) ?
                                        (int)$onboardingCalls->getCall4() : "",
                                        array("class" => "populate placeholder select2")) }}
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label class="col-sm-3 control-label">Call5</label>
                                    <div class="col-sm-6">
                                        {{ Form::select('call5', $options['onboarding_calls'],
                                        isset($onboardingCalls) ?
                                        (int)$onboardingCalls->getCall5() : "",
                                        array("class" => "populate placeholder select2")) }}
                                    </div>
                                </div>
                            </fieldset>
                            <fieldset>
                                <div class="form-group">
                                    <div class="col-sm-6">
                                        <a class="btn btn-primary SaveButton" href="#">Save Calls</a>
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                    @endcan
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/accounts/view?id={{ $account->getAccountId() }}"  class="btn btn-primary">View</a>
				<a href="/admin/accounts/applications?id={{ $account->getAccountId() }}" title="View Applications" class="btn btn-warning">Applications</a>
				<a href="{{ route('admin::accounts.subscriptions', $account->getAccountId()) }}"  class="btn btn-info">Subscriptions</a>
				<a href="/admin/accounts/mailbox/folders/{{ $account->getAccountId() }}"  class="btn btn-danger">Mailbox</a>
				<a href="/admin/accounts/conversations?id={{ $account->getAccountId() }}" title="View Conversations" class="btn btn-success">Conversations</a>
				<a href="/admin/accounts/eligibility?id={{ $account->getAccountId() }}" title="Eligibility" class="btn btn-info">Eligibility</a>
				<a href="/admin/accounts/loginhistory?id={{ $account->getAccountId() }}" title="Login History" class="btn btn-primary">Login History</a>
				<a href="/admin/accounts/impersonate?id={{ $account->getAccountId() }}" title="Impersonate Account" class="btn btn-primary">Impersonate</a>
				<a href="/admin/accounts/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
