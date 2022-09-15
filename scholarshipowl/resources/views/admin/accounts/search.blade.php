@extends("admin/base")
@section("content")

@can('access-route', 'export')
<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/accounts?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>
@endcan

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search-plus"></i>
					<span>Filter Search</span>
				</div>

				<div class="box-icons">
                    <form method="get" action="/admin/accounts/search" class="form-inline pull-left">
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Email</label>
                            <div class="col-sm-6">
                                {{ Form::text('email', $search['email'], array("class" => "form-control")) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-6">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>
                        </div>
                    </form>
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>

				<div class="no-move"></div>
			</div>

			<div class="box-content" style="display: none;">
				<form method="get" action="/admin/accounts/search" class="form-horizontal">
					<fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Domain</label>
                            <div class="col-sm-6">
                                {{ Form::select('domain', $options['domains'], $search['domain'], ["class" => "form-control"]) }}
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-6">
								{{ Form::text('email', $search['email'], array("class" => "form-control")) }}
							</div>
						</div>

                        <div class="form-group">
							<label class="col-sm-3 control-label">Username</label>
							<div class="col-sm-6">
								{{ Form::text('username', $search['username'], array("class" => "form-control")) }}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Profile Type</label>
                            <div class="col-sm-6">
                                {{ Form::select('profile_type[]', $options['profile_types'], $search['profile_type'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
                            </div>
                        </div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Account Status</label>
							<div class="col-sm-6">
								{{ Form::select('account_status_id[]', $options['account_statuses'], $search['account_status_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Account Pro</label>
							<div class="col-sm-6">
								{{ Form::select('account_pro', $options['account_pro'], $search['account_pro'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Account Type</label>
							<div class="col-sm-6">
								{{ Form::select('account_type_id[]', $options['account_types'], $search['account_type_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Created From</label>

							<div class="col-sm-3">
								{{ Form::text('created_date_from', $search['created_date_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Created To</label>

							<div class="col-sm-3">
								{{ Form::text('created_date_to', $search['created_date_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Has Subscription</label>
                            <div class="col-sm-3">
                                {{ Form::select('has_active_subscription', $options['paid_subscriptions'], $search['has_active_subscription'], array("class" => "populate placeholder select2")) }}
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Paying</label>
							<div class="col-sm-3">
								{{ Form::select('paid', $options['paid_subscriptions'], $search['paid'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Packages</label>
							<div class="col-sm-3">
								{{ Form::select('package_id[]', $options['packages'], $search['package_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">First Name</label>
							<div class="col-sm-6">
								{{ Form::text('first_name', $search['first_name'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Last Name</label>
							<div class="col-sm-6">
								{{ Form::text('last_name', $search['last_name'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Phone</label>
							<div class="col-sm-3">
								{{ Form::text('phone', $search['phone'], array("class" => "form-control")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Date Of Birth From</label>

							<div class="col-sm-3">
								{{ Form::text('date_of_birth_from', $search['date_of_birth_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Date Of Birth To</label>

							<div class="col-sm-3">
								{{ Form::text('date_of_birth_to', $search['date_of_birth_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Gender</label>
							<div class="col-sm-3">
								{{ Form::select('gender', $options['genders'], $search['gender'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Citizenship</label>
							<div class="col-sm-3">
								{{ Form::select('citizenship_id[]', $options['citizenships'], $search['citizenship_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

                        <div class="form-group">
							<label class="col-sm-3 control-label">Ethnicity</label>
                            <div class="col-sm-3">
								{{ Form::select('ethnicity_id[]', $options['ethnicities'], $search['ethnicity_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
                            </div>
                        </div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Military Affiliation</label>
							<div class="col-sm-3">
                                {{ Form::select('military_affiliation_id', $options['military_affiliations'], $search['military_affiliation_id'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Mail Subscription</label>
							<div class="col-sm-3">
								{{ Form::select('is_subscribed', $options['subscriptions'], $search['is_subscribed'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">School Level</label>
							<div class="col-sm-6">
								{{ Form::select('school_level_id[]', $options['school_levels'], $search['school_level_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Degree</label>
							<div class="col-sm-6">
								{{ Form::select('degree_id[]', $options['degrees'], $search['degree_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Degree Type</label>
							<div class="col-sm-6">
								{{ Form::select('degree_type_id[]', $options['degree_types'], $search['degree_type_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">GPA</label>
							<div class="col-sm-6">
								{{ Form::select('gpa[]', $options['gpas'], $search['gpa'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Enrollment Year</label>
							<div class="col-sm-6">
								{{ Form::select('enrollment_year[]', $options['enrollment_years'], $search['enrollment_year'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Enrollment Month</label>
							<div class="col-sm-6">
								{{ Form::select('enrollment_month[]', $options['enrollment_months'], $search['enrollment_month'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">High School</label>
							<div class="col-sm-6">
								{{ Form::text('highschool', $search['highschool'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">University</label>
							<div class="col-sm-6">
								{{ Form::text('university', $search['university'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">University Graduation Year</label>
							<div class="col-sm-6">
								{{ Form::select('graduation_year[]', $options['graduation_years'], $search['graduation_year'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">University Graduation Month</label>
							<div class="col-sm-6">
								{{ Form::select('graduation_month[]', $options['graduation_months'], $search['graduation_month'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Highschool Graduation Year</label>
							<div class="col-sm-6">
								{{ Form::select('highschool_graduation_year[]', $options['graduation_years'], $search['highschool_graduation_year'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Highschool Graduation Month</label>
							<div class="col-sm-6">
								{{ Form::select('highschool_graduation_month[]', $options['graduation_months'], $search['highschool_graduation_month'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Career Goal</label>
							<div class="col-sm-6">
								{{ Form::select('career_goal_id[]', $options['career_goals'], $search['career_goal_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Study Online</label>
							<div class="col-sm-6">
								{{ Form::select('study_online[]', $options['study_online'], $search['study_online'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Country</label>
							<div class="col-sm-6">
								{{ Form::select('country_id[]', $options['countries'], $search['country_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">State</label>
							<div class="col-sm-6">
								{{ Form::select('state_id[]', $options['states'], $search['state_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">City</label>
							<div class="col-sm-6">
								{{ Form::text('city', $search['city'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Address</label>
							<div class="col-sm-6">
								{{ Form::text('address', $search['address'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Zip</label>
							<div class="col-sm-6">
								{{ Form::text('zip', $search['zip'], array("class" => "form-control")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Login Action</label>
							<div class="col-sm-6">
								{{ Form::select('login_action[]', $options['login_actions'], $search['login_action'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Login IP</label>
							<div class="col-sm-6">
								{{ Form::text('login_ip', $search['login_ip'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Login Date From</label>

							<div class="col-sm-3">
								{{ Form::text('login_date_from', $search['login_date_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Login Date To</label>

                            <div class="col-sm-3">
                                {{ Form::text('login_date_to', $search['login_date_to'], array("class" => "form-control date_picker")) }}
                            </div>
                        </div>
                        <hr />
						<div class="form-group">
							<label class="col-sm-3 control-label">Consent to be called</label>
							<div class="col-sm-3">
								{{ Form::select('agree_call', $options['agree_call'], $search['agree_call'], ["class" => "populate placeholder select2"]) }}
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary" type="submit">Search</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-users"></i>
					<span>Results ({{ $count }})</span>
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
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>ID</th>
                            <th>Domain</th>
							<th>Name</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Paying</th>
							<th>Package</th>
							<th>%</th>
							<th>#</th>
							<th>Created</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($accounts as $account)
							<tr>
								<td>{{ $account->getAccountId() }}</td>
                                <td>
                                    {{ $account->getDomain() }}
                                </td>
								<td>
                                    <a href="{{ route('admin::accounts.edit', ['id' => $account->getAccountId()]) }}" target="_blank" title="Edit Profile: {{ $account->getProfile()->getFullName() }}">
                                        {{ $account->getProfile()->getFirstName() }} {{ $account->getProfile()->getLastName()  }} </a>
                                </td>
								<td><a href="mailto:{{ $account->getEmail() }}" title="Send Mail: {{ $account->getProfile()->getFirstName() }}">{{ $account->getEmail() }}</a></td>

								<td>
									@if ($account->getProfile()->getPhone())
										<a href="skype:{{ $account->getProfile()->getPhone() }}" title="Skype Call: {{ $account->getProfile()->getFullName() }}">{{ $account->getProfile()->getPhone() }}</a>
									@endif
								</td>

								<td>
									@if (array_key_exists($account->getAccountId(), $subscriptions) && !is_null($subscriptions[$account->getAccountId()]))
										@if ($subscriptions[$account->getAccountId()]->isReallyPaid())
											Yes
										@else
											No
										@endif
									@else
										No
									@endif
								</td>

								<td>
									@if (array_key_exists($account->getAccountId(), $subscriptions) && !is_null($subscriptions[$account->getAccountId()]))
										{{ $subscriptions[$account->getAccountId()]->getName() }}
									@endif
								</td>

								<td>{{ $account->getProfile()->getCompleteness() }}</td>
								<td>{{ @$applications_count[$account->getAccountId()] }}</td>

								<td>{{ format_date($account->getCreatedDate()) }}</td>

								<td>
									<div class="btn-group">
										<!-- <a class="btn btn-primary" href="javascript:void(0);"><i class="fa fa-user fa-fw"></i> User</a> -->
										<a class="btn btn-primary dropdown-toggle" data-toggle="dropdown" href="#">
											<span class="fa fa-caret-down"></span>
										</a>

										<ul class="dropdown-menu">
											@if ($account->getZendeskUserId())
												<li><a target="_blank" href="https://scholarshipowl.zendesk.com/agent/users/{{ $account->getZendeskUserId()  }}"><i class="fa fa-search fa-fw"></i>View zendesk profile</a></li>
											@endif

											<li><a href="{{ route('admin::accounts.view', ['id' => $account->getAccountId()])  }}"><i class="fa fa-search fa-fw"></i> View Profile</a></li>
                                            @can('access-route', 'accounts.edit')
											<li><a href="{{ route('admin::accounts.edit', ['id' => $account->getAccountId()])  }}"><i class="fa fa-pencil fa-fw"></i> Edit Profile</a></li>

											<li>
												<a 	href="#"
													data-delete-url="/admin/accounts/delete?id={{ $account->getAccountId() }}&params={{ base64_encode(http_build_query($pagination['url_params']))}}&page={{ $pagination['page'] }}"
													data-delete-message="Delete Profile For '{{ $account->getProfile()->getFullName()}}' ({{ $account->getEmail() }}) (ID={{ $account->getAccountId() }}) ?"
													title="Delete Profile"
													class="DeleteAccountButton">
													<i class="fa fa-trash fa-fw"></i> Delete Profile
												</a>
											</li>
                                            @endcan

											<li><a href="/admin/accounts/applications?id={{ $account->getAccountId() }}"><i class="fa fa-university fa-fw"></i> Applications</a></li>
                                            @can('access-route', 'accounts.subscription')
											<li><a href="{{ route('admin::accounts.subscriptions', $account->getAccountId()) }}"><i class="fa fa-star fa-fw"></i> Subscriptions</a></li>
                                            @endcan
                                            @can('access-route', 'accounts.mailbox')
											<li><a href="/admin/accounts/mailbox/folders/{{ $account->getAccountId() }}"><i class="fa fa-envelope-o fa-fw"></i> Mailbox</a></li>
                                            @endcan
                                            @can('access-route', 'accounts.conversation')
											<li><a href="/admin/accounts/conversations?id={{ $account->getAccountId() }}"><i class="fa fa-phone fa-fw"></i> Conversations</a></li>
                                            @endcan
											<li><a href="/admin/accounts/eligibility?id={{ $account->getAccountId() }}"><i class="fa fa-chain-broken fa-fw"></i> Eligibility</a></li>
											<li><a href="/admin/accounts/loginhistory?id={{ $account->getAccountId() }}"><i class="fa fa-file-text fa-fw"></i> Login History</a></li>
                                            @can('access-route', 'accounts.impersonate')
											<li><a href="/admin/accounts/impersonate?id={{ $account->getAccountId() }}"><i class="fa fa-user fa-fw"></i> Impersonate</a></li>
                                            @endcan
										</ul>
									</div>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/accounts?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>


@stop
