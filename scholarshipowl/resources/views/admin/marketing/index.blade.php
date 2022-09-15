@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Menu</p>

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
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Customer Support</p>

						<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@stop
