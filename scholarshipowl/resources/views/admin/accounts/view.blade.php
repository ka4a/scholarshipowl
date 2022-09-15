@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-sm-7">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Account Settings</p>

                        <p><b>Domain: </b>{{ $account->getDomain() }}</p>
						<p><b>Email: </b>{{ $account->getEmail() }}</p>
						<p><b>Username: </b>{{ $account->getUsername() }}</p>
                        <p><b>Password (external): </b>{{ $account->getPasswordExternal() }}</p>
                        <p><b>Application inbox: </b>{{ $account->getInternalEmail() }}</p>
						<p><b>Referral Code: </b>{{ $account->getReferralCode() }}</p>
						<p><b>Account Status: </b>{{ $account->getAccountStatus()->getName() }}</p>
						<p><b>Account Type: </b>{{ $account->getAccountType()->getName() }}</p>
						<p><b>Profile Type: </b>{{ $account->getProfile()->getProfileType() }}</p>
						<p><b>Created: </b>  {{ $account->getCreatedDate()  }}</p>
						<p><b>Last Updated: </b>{{ $account->getLastUpdatedDate()  }}</p>
                        @can('access-route', 'accounts.impersonate')
						    <p>
                                <b>Login Token: </b>
                                @if ($accountLoginToken)
                                    @if($accountLoginToken->getIsUsed())<strike>@endif
                                    {{ $accountLoginToken->getToken()}}
                                    @if($accountLoginToken->getIsUsed())</strike>@endif
                                @endif
                            </p>
                        @endcan
                        <p><b>Eligibility update: </b>{{ $account->getEligibilityUpdate()  }}</p>
                        <p><b>Eligibility hash: </b>{{ $account->getEligibility()  }}</p>
                        <p><b>Device token: </b>{{ $account->getDeviceToken()  }}</p>
                        <p><b>Do Not Sell Personal Information: </b>{{ $account->isSellInformation() ? "Yes" : "No" }}</p>
                        <p><b>Current Fset: </b>{{ is_null($account->getFset()) ? '' : $account->getFset()->getName()}}</p>
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Education</p>

						<p><b>School Level: </b>{{ $account->getProfile()->getSchoolLevel() ? $account->getProfile()->getSchoolLevel()->getName() : "" }}</p>
						<p><b>Degree: </b>{{ $account->getProfile()->getDegree() ? $account->getProfile()->getDegree()->getName() : "" }}</p>
						<p><b>Degree Type: </b>{{ $account->getProfile()->getDegreeType() ? $account->getProfile()->getDegreeType()->getName() : "" }}</p>
						<p><b>GPA: </b>{{ $account->getProfile()->getGpa() }}</p>
						<p><b>Enrolled: </b>{{ $account->getProfile()->getEnrolled() === true ? 'Yes': ($account->getProfile()->getEnrolled() === false ? 'No' : '') }}</p>
						<p><b>Enrollment Year: </b>{{ $account->getProfile()->getEnrollmentYear() }}</p>
						<p><b>Enrollment Month: </b>{{ $account->getProfile()->getEnrollmentMonth() }}</p>
						<p><b>High School: </b>{{ $account->getProfile()->getHighschool() }}</p>
                        <p><b>High School Address: </b>{{ $account->getProfile()->getHighschoolAddress1() }} {{ $account->getProfile()->getHighschoolAddress2() }}</p>
						<p><b>High School Graduation Year: </b>{{ $account->getProfile()->getHighschoolGraduationYear() }}</p>
						<p><b>High School Graduation Month: </b>{{ $account->getProfile()->getHighschoolGraduationMonth() }}</p>
						<p><b>University: </b>{{ $account->getProfile()->getUniversity() }} @if($account->getProfile()->getUniversity1()), {{ $account->getProfile()->getUniversity1() }} @endif @if($account->getProfile()->getUniversity2()), {{ $account->getProfile()->getUniversity2() }} @endif @if($account->getProfile()->getUniversity3()), {{ $account->getProfile()->getUniversity3() }} @endif @if($account->getProfile()->getUniversity4()), {{ $account->getProfile()->getUniversity4() }} @endif</p>
                        <p><b>University Address: </b>{{ $account->getProfile()->getUniversityAddress1() }} {{ $account->getProfile()->getUniversityAddress2() }}</p>
						<p><b>University Graduation Year: </b>{{ $account->getProfile()->getGraduationYear() }}</p>
						<p><b>University Graduation Month: </b>{{ $account->getProfile()->getGraduationMonth() }}</p>
					</div>
				</div>

                <div class="box">
                    <div class="box-content">
                        <p class="page-header">Country of Study</p>
                        <p>
                            <b>{{ $account->getProfile()->getStudyCountry1() }}</b><br/>
                            <b>{{ $account->getProfile()->getStudyCountry2() }}</b><br/>
                            <b>{{ $account->getProfile()->getStudyCountry3() }}</b><br/>
                            <b>{{ $account->getProfile()->getStudyCountry4() }}</b><br/>
                            <b>{{ $account->getProfile()->getStudyCountry5() }}</b>
                        </p>
                    </div>
                </div>

                <div class="box">
					<div class="box-content">
						<p class="page-header">Interests</p>

						<p><b>Career Goal: </b>{{ $account->getProfile()->getCareerGoal() ? $account->getProfile()->getCareerGoal()->getName() : "" }}</p>
						<p><b>Study Online: </b>{{ ucfirst($account->getProfile()->getStudyOnline()) }}</p>
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Subscriptions</p>

                        @if ($account->isFreemium())
                            <p>Freemium account</p>
                            <p>Credits: {{ $account->getCredits() }}</p>
                            <p>Freemium credits: {{ $account->getFreemiumCredits() }}</p>
                        @endif

                        <table class="table table-hover table-striped table-bordered table-heading">
							<thead>
								<tr>
									<th>Name</th>
									<th>Price</th>
									<th>Scholarships</th>
									<th>Status</th>
									<th>Terminated at</th>
                                    <th>Free trial</th>
                                    <th>Freemium</th>
									<th>Start Date</th>
                                    <th>Renewal Date</th>
									<th>End Date</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($subscriptions as $subscriptionId => $subscription)
									<tr>
										<td>{{ $subscription->getName() }}</td>
										<td>{{ $subscription->getPrice() }}</td>
										<td>@if ($subscription->getIsScholarshipsUnlimited()) UNLIMITED @else {{ $subscription->getScholarshipsCount() }} @endif</td>
										<td>{{ $subscription->getSubscriptionStatus() }}</td>
										<td>{{ $subscription->getTerminatedAt() ? $subscription->getTerminatedAt()->format('Y-m-d') : '' }}</td>
                                        <td>{{ $subscription->isFreeTrial() ? 'Yes' : 'No' }}</td>
                                        <td>{{ $subscription->isFreemium() ? 'Yes' : 'No' }}</td>
										<td>{{ $subscription->getStartDate()->format('Y-m-d') }}</td>
										<td>{{ $subscription->isFreemium() ? '' : $subscription->getRenewalDate()->format('Y-m-d') }}</td>
										<td>{{ $subscription->isFreemium() ? '' : $subscription->getEndDate()->format('Y-m-d') }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>

                <div class="box">
                    <div class="box-content">
                        <p class="page-header">Transactions</p>
                        <table class="table table-hover table-striped table-bordered table-heading">
                            <thead>
                                <tr>
                                    <th>Id</th>
                                    <th>Created</th>
                                    <th>Amount</th>
                                    <th>Subscription</th>
                                    <th>Payment Processor</th>
                                    <th>Recurrent Number</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($transactions as $transaction)
                                    <tr>
                                        <td>
                                            <a href="{{ url()->route('admin::transactions.view', ['id' => $transaction->getTransactionId()]) }}">
                                            {{ $transaction->getTransactionId() }}
                                            </a>
                                        </td>
                                        <td>{{ $transaction->getCreatedDate()->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $transaction->getAmount() }}</td>
                                        <td>{{ $transaction->getSubscription()->getName() }}</td>
                                        <td>{{ $transaction->getPaymentMethod() }}</td>
                                        <td>{{ $transaction->getRecurrentNumber() }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">User Files</p>
						<table class="table table-hover table-striped table-bordered table-heading">
							<thead>
							<tr>
								<th>File</th>
								<th>Download</th>
								<th>Delete</th>
							</tr>
							</thead>
							<tbody>
							@foreach ($files as $accountFile)
								<tr>
									<td>{{ $accountFile->getPath() }}</td>
									<td><a href="{{ route('admin::accounts.file.download', $accountFile->getId()) }}">download</a></td>
									<td><a href="{{ route('admin::accounts.file.delete', $accountFile->getId()) }}">delete</a></td>
								</tr>
							@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-5">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Profile Information</p>

						<p><b>First Name: </b>{{ $account->getProfile()->getFirstName() }}</p>
						<p><b>Last Name: </b>{{ $account->getProfile()->getLastName() }}</p>
						<p><b>Phone: </b>{{ $account->getProfile()->getPhone() }}</p>
						<p><b>Date Of Birth: </b>{{ $account->getProfile()->getDateOfBirth() }}</p>
						<p><b>Gender: </b>{{ ucfirst($account->getProfile()->getGender()) }}</p>
						<p><b>Citizenship: </b>{{ $account->getProfile()->getCitizenship() ? $account->getProfile()->getCitizenship()->getName() : "" }}</p>
						<p><b>Ethnicity: </b>{{ $account->getProfile()->getEthnicity() ? $account->getProfile()->getEthnicity()->getName() : "" }}</p>
						<p><b>Mail Subscription: </b>@if ($account->getProfile()->isSubscribed() == "1") {{ "Yes" }} @else {{ "No" }} @endif</p>
						<p><b>Profile Completeness: </b>{{ $account->getProfile()->getCompleteness() }}%</p>
						<p><b>Military Affiliation: </b>{{ $account->getProfile()->getMilitaryAffiliation() ? $account->getProfile()->getMilitaryAffiliation()->getName() : "" }}</p>
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Location</p>

						<p><b>Country: </b>{{ $account->getProfile()->getCountry() ? $account->getProfile()->getCountry()->getName() : "" }}</p>
						<p><b>State: </b>{{ $account->getProfile()->getState() ? $account->getProfile()->getState()->getName() : "" }}</p>
                        <p><b>State (free text): </b>{{ $account->getProfile()->getStateName() }}</p>
						<p><b>City: </b>{{ $account->getProfile()->getCity() }}</p>
						<p><b>Address: </b>{{ $account->getProfile()->getFullAddress() }}</p>
						<p><b>Zip: </b>{{ $account->getProfile()->getZip() }}</p>
					</div>
				</div>

                @can("access-route", "scholarships.super-collage")
                    <div class="box">
                        <div class="box-content">
                            <p class="page-header">SuperCollege API</p>
                            <p><b>Number of eligible scholarships: </b>{{ $supercollegeEligibility }}</p>
                        </div>
                    </div>
                @endcan

                @can("view", new \App\Entity\AccountOnBoardingCall())
                    <div class="box">
                        <div class="box-content">
                            <p class="page-header">Onboarding Calls</p>
                            <p><b>Consent to be called: </b>{{ $account->getProfile()->getAgreeCall()?"Yes":"No" }}</p>
                            @if(!empty($onboardingCalls))
                                <p><b>Call1: </b>{{ $onboardingCalls->getCall1()?"Yes":"No" }}</p>
                                <p><b>Call2: </b>{{ $onboardingCalls->getCall2()?"Yes":"No" }}</p>
                                <p><b>Call3: </b>{{ $onboardingCalls->getCall3()?"Yes":"No" }}</p>
                                <p><b>Call4: </b>{{ $onboardingCalls->getCall4()?"Yes":"No" }}</p>
                                <p><b>Call5: </b>{{ $onboardingCalls->getCall5()?"Yes":"No" }}</p>
                            @endif
                        </div>
                    </div>
                @endcan

				<div class="box">
					<div class="box-content">
						<p class="page-header">Marketing System</p>

						@if (!empty($marketing))
							<p><b>{{ $marketing->getMarketingSystem() }}</b></p>
						@endif

						<table class="table table-hover table-striped table-bordered table-heading">
							<thead>
								<tr>
									<th>Name</th>
									<th>Value</th>
								</tr>
							</thead>
							<tbody>
								@if (!empty($marketing))
									@foreach ($marketing->getData() as $key => $value)
										<tr>
											<td>{{ $key }}</td>
											<td>{{ $value }}</td>
										</tr>
									@endforeach
								@endif
							</tbody>
						</table>
					</div>
				</div>

				<div class="box">
                    <div class="box-content">
                        <p class="page-header">Referrals</p>

                        <table class="table table-hover table-striped table-bordered table-heading">
                            <thead>
                                <tr>
                                    <th>Full Name</th>
                                    <th>Registration Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($referrals as $referral)
                                    <tr>
                                        <td>{{ $referral->first_name }} {{ $referral->last_name }}</td>
                                        <td>{{ format_date($referral->created_date) }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Login History</p>
						<table class="table table-hover table-striped table-bordered table-heading">
							<thead>
								<tr>
									<th>Action</th>
                                    <th>FSet</th>
                                    <th>SRV</th>
									<th>IP Address</th>
									<th>Date taken</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($loginHistory as $item)
									<tr>
										<td>{{ $item->getAction() }}</td>
                                        <td>{{ $item->getFeatureSet() }}</td>
                                        <td>{{ $item->getSrv() }}</td>
										<td>{{ $item->getIpAddress() }}</td>
										<td>{{ $item->getActionDate() }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				@if ($account->getZendeskUserId())
					<a target="_blank" href="https://scholarshipowl.zendesk.com/agent/users/{{ $account->getZendeskUserId()  }}" class="btn btn-info">View zendesk profile</a>
				@endif

                @can('access-route', 'accounts.edit')
				<a href="/admin/accounts/edit?id={{ $account->getAccountId() }}"  class="btn btn-primary">Edit</a>
                @endcan
				<a href="/admin/accounts/applications?id={{ $account->getAccountId() }}" title="View Applications" class="btn btn-warning">Applications</a>
                @can('access-route', 'accounts.subscription')
				<a href="{{ route('admin::accounts.subscriptions', $account->getAccountId()) }}"  class="btn btn-info">Subscriptions</a>
                @endcan
                @can('access-route', 'accounts.mailbox')
				<a href="/admin/accounts/mailbox/folders/{{ $account->getAccountId() }}"  class="btn btn-danger">Mailbox</a>
                @endcan
                @can('access-route', 'accounts.converstation')
				<a href="/admin/accounts/conversations?id={{ $account->getAccountId() }}" title="View Conversations" class="btn btn-success">Conversations</a>
                @endcan
				<a href="/admin/accounts/eligibility?id={{ $account->getAccountId() }}" title="Eligibility" class="btn btn-info">Eligibility</a>
				<a href="/admin/accounts/loginhistory?id={{ $account->getAccountId() }}" title="Login History" class="btn btn-primary">Login History</a>
                @can('access-route', 'accounts.impersonate')
				<a href="/admin/accounts/impersonate?id={{ $account->getAccountId() }}" title="Impersonate Account" class="btn btn-primary">Impersonate</a>
                @endcan
				<a href="/admin/accounts/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
