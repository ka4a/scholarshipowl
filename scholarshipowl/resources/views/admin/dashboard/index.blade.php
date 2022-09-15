@extends("admin/base")
@section("content")

<div class="row">
    <div class="col-sm-4">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <div class="box-name">
                            <i class="fa fa-desktop"></i>
                            <span>Menu</span>
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
                        @can('access-route', 'scholarships.view')
                        <p><a class="ajax-link" href="/admin/scholarships">Scholarships</a></p>
                        <ul>
                            @can('access-route', 'scholarships.edit')
                                <li><a class="ajax-link" href="/admin/scholarships/save">Add Scholarship</a></li>
                            @endcan
                            @can('access-route', 'scholarships.super-collage')
                                <li><a class="ajax-link" href="/admin/scholarships/search">Search Scholarships</a></li>
                                <li><a class="ajax-link" href="/admin/scholarships/super-college">SuperCollege API</a></li>
                                <li><a class="ajax-link" href="/admin/scholarships/super-college-eligibility">SuperCollege Eligibility</a></li>
                            @endcan
                        </ul>
                        @endcan


                        @can('access-route', 'winners.view')
                        <p><a class="ajax-link" href="/admin/winners">Winners</a></p>
                        <ul>
                            @can('access-route', 'winners.edit')
                                <li><a class="ajax-link" href="/admin/winners/edit">Add Winner</a></li>
                            @endcan

                            <li><a class="ajax-link" href="/admin/winners/search">Search Winners</a></li>
                        </ul>
                        @endcan

                        @can('access-route', 'accounts.view')
                        <p><a class="ajax-link" href="/admin/accounts">Accounts</a></p>
                        <ul>
                            <li><a class="ajax-link" href="/admin/accounts/search">Search Accounts</a></li>
                            @can('access-route', 'accounts.edit')
                            <li><a class="ajax-link" href="/admin/accounts/register">Register Account</a></li>
                            @endcan
                        </ul>
                        @endcan

                        @can('access-route', 'marketing.view')
						<p><a class="ajax-link" href="/admin/marketing">Marketing</a></p>
						<ul>
                            <li><a class="ajax-link" href="/admin/marketing/search">Search Marketing</a></li>
                            <li><a class="ajax-link" href="/admin/marketing/submissions">Search Submissions</a></li>
                            @can('access-route', 'marketing.ab_tests')
                                <li><a class="ajax-link" href="/admin/marketing/ab_tests">AB Tests</a></li>
                            @endcan
                            @can('access-route', 'marketing.affiliates')
                                <li><a class="ajax-link" href="/admin/marketing/affiliates/save">Add Affiliate</a></li>
                                <li><a class="ajax-link" href="/admin/marketing/affiliates">Affiliates</a></li>
                                <li><a class="ajax-link" href="/admin/marketing/affiliates_responses">Affiliates Responses</a></li>
                            @endcan
                            @can('access-route', 'marketing.affiliate_goal_mapping')
                                <li><a class="ajax-link" href="/admin/marketing/affiliate_goal_mapping/save">Add Goal Mapping</a></li>
                                <li><a class="ajax-link" href="/admin/marketing/affiliate_goal_mapping">Goal Mappings</a></li>
                            @endcan
                            @can('access-route', 'marketing.coreg_plugin')
                                <li><a class="ajax-link" href="/admin/marketing/coreg_plugin/save">Add Coreg Plugin</a></li>
                                <li><a class="ajax-link" href="/admin/marketing/coreg_plugin">Coreg Plugins</a></li>
                            @endcan
                            @can('access-route', 'marketing.redirect_rules_set')
                                <li><a class="ajax-link" href="/admin/marketing/redirect_rules_set/save">Add Redirect Rules Set</a></li>
                                <li><a class="ajax-link" href="/admin/marketing/redirect_rules_set">Redirect Rules Sets</a></li>
                            @endcan
                            @can('access-route', 'marketing.transactional_email')
                                <li><a class="ajax-link" href="/admin/marketing/transactional_email">Transactional Emails</a></li>
                            @endcan
						</ul>
                        @endcan

                        @can('access-route', 'packages.view')
						<p><a class="ajax-link" href="/admin/packages">Packages</a></p>
						<ul>
                            <li><a class="ajax-link" href="/admin/packages/search">Search Packages</a></li>
                            @can('access-route', 'packages.edit')
							<li><a class="ajax-link" href="/admin/packages/save">Add Package</a></li>
                            <li><a class="ajax-link" href="/admin/packages/batch-subscription">Batch Subscription</a></li>
                            @endcan
						</ul>
                        @endcan

                        @can('access-route', 'missions.view')
						<p><a class="ajax-link" href="/admin/missions">Missions</a></p>
						<ul>
                            @can('access-route', 'missions.edit')
							<li><a class="ajax-link" href="/admin/missions/save">Add Mission</a></li>
                            @endcan
							<li><a class="ajax-link" href="/admin/missions/search">Search Missions</a></li>
							<li><a class="ajax-link" href="/admin/missions/progress">Search Progress</a></li>
						</ul>
                        @endcan

                        @can('access-route', 'refer-a-friend.view')
						<p><a class="ajax-link" href="/admin/refer-a-friend">Refer A Friend</a></p>
						<ul>
                            @can('access-route', 'refer-a-friend.edit')
							<li><a class="ajax-link" href="/admin/refer-a-friend/awards/save">Add Award</a></li>
                            @endcan
							<li><a class="ajax-link" href="/admin/refer-a-friend/awards">Awards</a></li>
							<li><a class="ajax-link" href="/admin/refer-a-friend/awards/history">Awards History</a></li>
							<li><a class="ajax-link" href="/admin/refer-a-friend/share-report">Share Report</a></li>
							<li><a class="ajax-link" href="/admin/refer-a-friend/search">Search Referrals</a></li>
						</ul>
                        @endcan

                        @can('access-route', 'transactions.view')
						<p><a class="ajax-link" href="/admin/transactions">Transactions</a></p>
						<ul>
							<li><a class="ajax-link" href="/admin/transactions/search">Search Transactions</a></li>
						</ul>
                        @endcan

                        @can('access-route', 'statistics')
						<p><a class="ajax-link" href="/admin/statistics">Statistics</a></p>
						<ul>
							<li><a class="ajax-link" href="/admin/statistics/daily-management">Daily Management</a></li>
							<li><a class="ajax-link" href="/admin/statistics?for=transactions">Transaction Reports By Date</a></li>
							<li><a class="ajax-link" href="/admin/statistics/customer-report">Customer Report</a></li>
						</ul>
                        @endcan

                        @can('access-route', 'static-data')
						<p><a class="ajax-link" href="/admin/static_data">Static Data</a></p>
						<ul>
							<li><a class="ajax-link" href="/admin/website/account_fields">Account Fields</a></li>
							<li><a class="ajax-link" href="/admin/universities">Universities</a></li>
							<li><a class="ajax-link" href="/admin/highschools">High Schools</a></li>
						</ul>
                        @endcan

                        @can('access-route', 'website')
						<p><a class="ajax-link" href="/admin/website">Website</a></p>
						<ul>
							<li><a class="ajax-link" href="/admin/website/settings">General Settings</a></li>
							<li><a class="ajax-link" href="/admin/accounts/edit?id={{ $user->getAccountId() }}">My Profile</a></li>
							<li><a class="ajax-link" target="_blank" href="{{ URL::to('/') }}">View Website</a></li>
							<li><a class="MailTemplatePreview" href="#">View Mail Template</a></li>
							<li><a class="ajax-link" href="/admin/logout">Logout</a></li>
							<li><a class="ajax-link" href="/admin/website/commands">Commands</a></li>
						</ul>
                        @endcan

                        @can('access-route', 'priorities')
                        <p><a class="ajax-link" href="/admin/priorities">Priorities</a></p>
                        <ul>
                            <li><a class="ajax-link" href="/admin/priorities/missiongoals">Mission Goals</a></li>
                            <li><a class="ajax-link" href="/admin/priorities/packages">Packages</a></li>
                        </ul>
                        @endcan

                        @can('access-route', 'popup')
                        <p><a class="ajax-link" href="/admin/popup">Popup</a></p>
                        <ul>
                            <li><a class="ajax-link" href="/admin/popup/save">Add Popup</a></li>
                            <li><a class="ajax-link" href="/admin/popup/search">Search Popups</a></li>
                        </ul>
                        @endcan

					</div>
				</div>
			</div>
		</div>
	</div>

    <div class="col-sm-8">
        <div class="row">
            <div class="col-xs-12">
                @can('access-route', 'accounts.view')
                <div class="box">
                    <div class="box-header">
                        <div class="box-name">
                            <i class="fa fa-users"></i>
                            <span>Latest Accounts</span>
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
                                    <th>Email</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accounts as $account)
                                    @php($profile = $account->getProfile())
                                    <tr>
                                        <td><a href="mailto:{{ $account->getEmail() }}">{{ $account->getEmail() }}</a></td>
                                        <td>{{ $profile->getFirstName() }} {{ $profile->getLastName() }}</td>
                                        <td>{{ $profile->getPhone() }}</td>

                                        <td>
                                            <a href="{{ route('admin::accounts.view', ['id' => $account->getAccountId()])  }}" title="View Profile" class="btn btn-success">View</a>
                                            @can('access-route', 'accounts.edit')
                                                <a href="{{ route('admin::accounts.edit', ['id' => $account->getAccountId()])  }}" title="Edit Profile" class="btn btn-primary">Edit</a>
                                                @endcan
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <p class="pull-left"><a class="btn btn-primary btn-large" href="/admin/accounts/search">Search All</a></p>
                        <div class="clearfix"></div>
                    </div>
                </div>
                @endcan

                @can('access-route', 'scholarships.view')
                <div class="box">
                    <div class="box-header">
                        <div class="box-name">
                            <i class="fa fa-university"></i>
                            <span>Latest Scholarships</span>
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
                                    <th>Title</th>
                                    <th>Amount</th>
                                    <th>Deadline</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($scholarships as $scholarship)
                                    <tr>
                                        <td><a href="{{ route('admin::scholarships.view', ['id' => $scholarship->getScholarshipId()] ) }}">{{ $scholarship->getTitle() }}</a></td>
                                        <td>{{ $scholarship->getAmount() }}</td>
                                        <td>{{ format_date($scholarship->getExpirationDate()->format('Y-m-d')) }}</td>

                                        <td>

                                            <a href="{{ route('admin::scholarships.view', ['id' => $scholarship->getScholarshipId()] ) }}" title="View Scholarship" class="btn btn-success">View</a>
                                            <a href="{{ route('admin::scholarships.save', ['id' => $scholarship->getScholarshipId()] ) }}" title="Edit Scholarship" class="btn btn-primary">Edit</a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <p class="pull-left"><a class="btn btn-primary btn-large" href="/admin/scholarships/search">Search All</a></p>
                        <div class="clearfix"></div>
                    </div>
                </div>
                @endcan
            </div>
        </div>
    </div>
</div>

@stop
