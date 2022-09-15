<ul class="nav main-menu">
    <li>
        <a href="/admin/dashboard" class="@if ($active == 'index') {{ 'active' }} @endif ajax-link">
            <i class="fa fa-dashboard"></i>
            <span class="hidden-xs">Dashboard</span>
        </a>
    </li>

    @can('access-route', 'scholarships.view')
        <li class="dropdown">
            <a href="#" class="dropdown-toggle @if ($active == 'scholarships') {{ 'active' }} @endif">
                <i class="fa fa-university"></i>
                <span class="hidden-xs">Scholarships</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="ajax-link" href="/admin/scholarships/search">Search Scholarships</a></li>
                @can('access-route', 'scholarships.edit')
                    <li><a class="ajax-link" href="/admin/scholarships/save">Add Scholarship</a></li>
                @endcan
                @can('access-route', 'scholarships.super-collage')
                    <li><a class="ajax-link" href="/admin/scholarships/super-college">SuperCollege API</a></li>
                    <li><a class="ajax-link" href="/admin/scholarships/super-college-eligibility">SuperCollege Eligibility</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('access-route', 'winners.view')
        <li class="dropdown">
            <a href="#" class="dropdown-toggle @if ($active == 'winners') {{ 'active' }} @endif">
                <i class="fa fa-trophy"></i>
                <span class="hidden-xs">Winners</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="ajax-link" href="{{ route('admin::winners.search') }}">Search Winners</a></li>
                @can('access-route', 'winners.edit')
                    <li><a class="ajax-link" href="{{ route('admin::winners.edit') }}">Add Winner</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('access-route', 'accounts.view')
        <li class="dropdown">
            <a href="#" class="dropdown-toggle @if ($active == 'accounts') {{ 'active' }} @endif">
                <i class="fa fa-users"></i>
                <span class="hidden-xs">Accounts</span>
            </a>

            <ul class="dropdown-menu">
                <li><a class="ajax-link" href="/admin/accounts/search">Search Accounts</a></li>
                @can('access-route', 'accounts.edit')
                <li><a class="ajax-link" href="/admin/accounts/register">Register Account</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('access-route', 'applications.view')
        <li class="dropdown">
            <a href="#" class="dropdown-toggle @if ($active == 'applications') {{ 'active' }} @endif">
                <i class="fa fa-external-link-square"></i>
                <span class="hidden-xs">Applications</span>
            </a>

            <ul class="dropdown-menu">
                <li><a class="ajax-link" href="/admin/applications/search">Search Applications</a></li>
            </ul>
        </li>
    @endcan

    @can('access-route', 'features.edit')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'features') {{ 'active' }} @endif">
            <i class="fa fa-cogs"></i>
            <span class="hidden-xs">Features</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="{{ route('admin::features.index') }}">General</a></li>
            <li><a class="ajax-link" href="{{ route('admin::features.content_sets.index') }}">Content Sets</a></li>
            <li><a class="ajax-link" href="{{ route('admin::features.payment_sets.index') }}">Payment Sets</a></li>
            <li><a class="ajax-link" href="{{ route('admin::features.company_details_set.index') }}">Company details</a></li>
            <li><a class="ajax-link" href="{{ route('admin::features.ab_tests.index') }}">Ab Tests</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'marketing.view')
        <li class="dropdown">
            <a href="#" class="dropdown-toggle @if ($active == 'marketing') {{ 'active' }} @endif">
                <i class="fa fa-share-alt"></i>
                <span class="hidden-xs">Marketing</span>
            </a>
            <ul class="dropdown-menu">
                <li><a class="ajax-link" href="/admin/marketing/search">Search Marketing</a></li>
                <li><a class="ajax-link" href="/admin/marketing/submissions">Search Submissions</a></li>
                @can('access-route', 'marketing.affiliates')
                    <li><a class="ajax-link" href="/admin/marketing/affiliates/save">Add Affiliate</a></li>
                    <li><a class="ajax-link" href="/admin/marketing/affiliates">Affiliates</a></li>
                    <li><a class="ajax-link" href="/admin/marketing/affiliates_responses">Affiliates Responses</a></li>
                @endcan
                @can('access-route', 'marketing.affiliate_goal_mapping')
                <li><a class="ajax-link" href="/admin/marketing/affiliate_goal_mapping/save">Add Goal Mapping</a></li>
                <li><a class="ajax-link" href="/admin/marketing/affiliate_goal_mapping">Goal Mappings</a></li>
                @endcan
                @can('access-route', 'marketing.banners')
                <li><a class="ajax-link" href="{{ route('admin::marketing.banners.index') }}">Banners</a></li>
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
                <li><a class="ajax-link" href="/admin/marketing/mobile_push_notifications">Mobile push motifications</a></li>
                @endcan
            </ul>
        </li>
    @endcan

    @can('access-route', 'packages.view')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'packages') {{ 'active' }} @endif">
            <i class="fa fa-suitcase"></i>
            <span class="hidden-xs">Packages</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="/admin/packages/search">Search Packages</a></li>
            @can('access-route', 'packages.edit')
            <li><a class="ajax-link" href="/admin/packages/save">Add Package</a></li>
            <li><a class="ajax-link" href="/admin/packages/batch-subscription">Batch Subscription</a></li>
            @endcan
        </ul>
    </li>
    @endcan

    @can('access-route', 'callcenter.view')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'callcenter') {{ 'active' }} @endif">
            <i class="fa fa-phone"></i>
            <span class="hidden-xs">Call Center</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="/admin/call-center/edumax">React2Media</a></li>
            <li><a class="ajax-link" href="/admin/call-center/export">Export</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'refer-a-friend.view')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'refer_a_friend') {{ 'active' }} @endif">
            <i class="fa fa-tags"></i>
            <span class="hidden-xs">Refer A Friend</span>
        </a>
        <ul class="dropdown-menu">
            @can('access-route', 'refer-a-friend.edit')
            <li><a class="ajax-link" href="/admin/refer-a-friend/awards/save">Add Award</a></li>
            @endcan

            @can('access-router', 'awards.view')
            <li><a class="ajax-link" href="/admin/refer-a-friend/awards">Awards</a></li>
            <li><a class="ajax-link" href="/admin/refer-a-friend/awards/history">Awards History</a></li>
            @endcan

            <li><a class="ajax-link" href="/admin/refer-a-friend/share-report">Share Report</a></li>
            <li><a class="ajax-link" href="/admin/refer-a-friend/search">Search Referrals</a></li>
        </ul>
    </li>
    @endcan


    @can('access-route', 'transactions.view')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'transactions') {{ 'active' }} @endif">
            <i class="fa fa-money"></i>
            <span class="hidden-xs">Transactions</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="/admin/transactions/search">Search Transactions</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'subscriptions')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'subscriptions') {{ 'active' }} @endif">
            <i class="fa fa-money"></i>
            <span class="hidden-xs">Subscriptions</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="{{ route('admin::subscriptions.index') }}">Search Subscriptions</a></li>
            <li><a class="ajax-link" href="{{ route('admin::subscriptions.doubleSubscriptions') }}">Double Subscriptions</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'payments')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'payments') {{ 'active' }} @endif">
            <i class="fa fa-money"></i>
            <span class="hidden-xs">Payments</span>
        </a>
        <ul class="dropdown-menu">
            @can('access-route', 'payments.braintree')
            <li><a class="ajax-link" href="{{ route('admin::payments.braintree.index') }}">Braintree Accounts</a></li>
            @endcan
            <li><a class="ajax-link" href="{{ route('admin::payments.payment_methods.index') }}">Payment Methods</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'statistics')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'statistics') {{ 'active' }} @endif">
            <i class="fa fa-bar-chart-o"></i>
            <span class="hidden-xs">Statistics</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="/admin/statistics/daily-management">Daily Management</a></li>
            <li><a class="ajax-link" href="/admin/statistics?for=transactions">Transaction Reports By Date</a></li>
            <li><a class="ajax-link" href="/admin/statistics/customer-report">Customer Report</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'static-data')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'static_data') {{ 'active' }} @endif">
            <i class="fa fa-table"></i>
            <span class="hidden-xs">Static Data</span>
        </a>

        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="/admin/website/account_fields">Account Fields</a></li>
            <li><a class="ajax-link" href="/admin/universities">Universities</a></li>
            <li><a class="ajax-link" href="/admin/highschools">High Schools</a></li>
        </ul>
    </li>
    @endcan

    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'website') {{ 'active' }} @endif">
            <i class="fa fa-th-large"></i>
            <span class="hidden-xs">Website</span>
        </a>
        <ul class="dropdown-menu">
            @can('access-route', 'website')
            <li><a class="ajax-link" href="/admin/website/settings">General Settings</a></li>
            <li><a class="MailTemplatePreview" href="#">View Mail Template</a></li>
            @endcan
            <li><a class="ajax-link" target="_blank" href="{{ URL::to('/') }}">View Website</a></li>
            <li><a class="ajax-link" href="/admin/accounts/edit?id={{ $user->getAccountId() }}">My Profile</a></li>
            <li><a class="ajax-link" href="/admin/logout">Logout</a></li>
            <li><a class="ajax-link" href="/admin/website/commands">Commands</a></li>
        </ul>
    </li>

    @can('access-route', 'cms')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'cms') {{ 'active' }} @endif">
            <i class="fa fa-bars"></i>
            <span class="hidden-xs">CMS</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="{{ route('admin::cms.pages') }}">Pages</a></li>
            <li><a class="ajax-link" href="{{ route('admin::cms.create') }}">Create page</a></li>
            <li><a class="ajax-link" href="{{ route('admin::cms.special-offer-pages.index') }}">Special Offer Pages</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'notification')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'notification') {{ 'active' }} @endif">
            <i class="fa fa-bars"></i>
            <span class="hidden-xs">Notifications</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="{{ route('admin::notification.index', ['app' => \App\Entity\OnesignalNotification::APP_WEB]) }}">Web</a></li>
            <li><a class="ajax-link" href="{{ route('admin::notification.index', ['app' => \App\Entity\OnesignalNotification::APP_MOBILE]) }}">Mobile</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'popup')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'popup') {{ 'active' }} @endif">
            <i class="fa fa-bars"></i>
            <span class="hidden-xs">Popup</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="/admin/popup/save">Add Popup</a></li>
            <li><a class="ajax-link" href="/admin/popup/search">Search Popups</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'priorities')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active == 'priorities') {{ 'active' }} @endif">
            <i class="fa fa-bars"></i>
            <span class="hidden-xs">Priorities</span>
        </a>
        <ul class="dropdown-menu">
            <li><a class="ajax-link" href="/admin/priorities/missiongoals">Mission Goals</a></li>
            <li><a class="ajax-link" href="/admin/priorities/packages">Packages</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'acl')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active === 'acl') {{ 'active' }} @endif">
            <i class="fa fa-bars"></i>
            <span class="hidden-xs">Access Limiter</span>
        </a>

        <ul class="dropdown-menu" style="@if ($active === 'acl') display: block; @endif">
            <li><a href="{{ url()->route('admin::acl.admins') }} ">Admins</a></li>
            <li><a href="{{ url()->route('admin::acl.roles') }} ">Roles</a></li>
        </ul>
    </li>
    @endcan

    @can('access-route', 'apply-me')
        <li class="dropdown">
            <a href="#" class="dropdown-toggle @if ($active === 'applyme') {{ 'active' }} @endif">
                <i class="fa fa-bars"></i><span class="hidden-xs">ApplyMe</span>
            </a>
            <ul class="dropdown-menu" style="@if ($active === 'applyme') display: block; @endif">
                <li><a href="{{ url()->route('admin::applyme.settings.index') }} ">Settings</a></li>
            </ul>
        </li>
    @endcan

    @can('access-route', 'logs')
    <li class="dropdown">
        <a href="#" class="dropdown-toggle @if ($active === 'logs') {{ 'active' }} @endif">
            <i class="fa fa-bars"></i><span class="hidden-xs">Logs</span>
        </a>
        <ul class="dropdown-menu" style="@if ($active === 'acl') display: block; @endif">
            <li><a href="{{ url()->route('admin::logs.adminActivity') }} ">Admin Activity</a></li>
        </ul>
    </li>
    @endcan
</ul>
