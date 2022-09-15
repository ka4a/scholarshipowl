@extends('admin.base')
@section('content')
    <div id="subscriptions-index-page">
        <div class="box" id="subscription-grid-search">
            <div class="box-header" data-toggle="collapse" data-target="#subscription-grid-search-content">
                <div class="box-name-sub">
                    <i class="fa fa-search-plus"></i>
                    <span>Search</span>
                </div>
            </div>
            <div id="subscription-grid-search-content" class="box-content collapse">
                <div class="container form-horizontal">
                    {!! Form::open(['id' => 'search-form', 'class' => 'form-horizontal']) !!}
                        <div class="row">
                            <div class="col-xs-12">
                                <h4>Subscription</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-package" class="control-label col-xs-3">Subscription Status</label>
                            <div class="col-xs-6">
                                {!! Form::select('subscriptionStatus[]', \App\Entity\SubscriptionStatus::options(), null, ['id' => 'search-subscription-status', 'class' => 'populate placeholder select2', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-package" class="control-label col-xs-3">Remote Status</label>
                            <div class="col-xs-6">
                                {!! Form::select('remoteStatus[]', \App\Entity\Subscription::remoteOptions(), null, ['id' => 'search-remote-status', 'class' => 'populate placeholder select2', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-paid" class="control-label col-xs-3">Paid</label>
                            <div class="col-xs-6">
                                {!! Form::select('paid', ['Not selected', 'Yes', 'No'], null, ['id' => 'search-paid', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-package" class="control-label col-xs-3">Free Trial</label>
                            <div class="col-xs-6">
                                {!! Form::select('freeTrial', [0 => 'Not selected', 1 => 'Yes', 2 => 'No'], null, ['id' => 'search-free-trial', 'class' => 'form-control']) !!}
                                <span>Subscription free trial only in free trial period.</span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-package" class="control-label col-xs-3">Freemium</label>
                            <div class="col-xs-6">
                                {!! Form::select('isFreemium', [0 => 'Not selected', 1 => 'Yes', 2 => 'No'], null, ['id' => 'search-is-freemium', 'class' => 'form-control']) !!}
                                <span>Only freemium subscription</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4>Account</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3">First Name</label>
                            <div class="col-xs-6">
                                {!! Form::text('accountFirstName', null, ['id' => 'search-account-first-name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3">Last name</label>
                            <div class="col-xs-6">
                                {!! Form::text('accountLastName', null, ['id' => 'search-account-last-name', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3">Id</label>
                            <div class="col-xs-6">
                                {!! Form::text('accountId', null, ['id' => 'search-account-id', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3">Email</label>
                            <div class="col-xs-6">
                                {!! Form::text('accountEmail', null, ['id' => 'search-account-email', 'class' => 'form-control']) !!}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4>Date ranges</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-start-date-from" class="control-label col-xs-3">Start date from:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-start-date-from" type="text" name="startDateFrom"/>
                            </div>
                            <label for="search-start-date-to" class="control-label col-xs-1">Until:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-start-date-until" type="text" name="startDateUntil"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-end-date-from" class="control-label col-xs-3">End date from:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-end-date-from" type="text" name="endDateFrom"/>
                            </div>
                            <label for="search-end-date-to" class="control-label col-xs-1">Until:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-end-date-until" type="text" name="endDateUntil"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-terminated-at-from" class="control-label col-xs-3">Terminated at from:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-terminated-at-from" type="text" name="terminatedAtFrom"/>
                            </div>
                            <label for="search-terminated-at-until" class="control-label col-xs-1">Until:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-terminated-at-until" type="text" name="terminatedAtUntil"/>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-renewal-date-from" class="control-label col-xs-3">Renewal date from:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-renewal-date-from" type="text" name="renewalDateFrom"/>
                            </div>
                            <label for="search-renewal-date-to" class="control-label col-xs-1">Until:</label>
                            <div class="col-xs-3">
                                <input class="form-control date_picker" id="search-renewal-date-until" type="text" name="renewalDateUntil"/>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4>Package</h4>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-package" class="control-label col-xs-3">Package</label>
                            <div class="col-xs-6">
                                {!! Form::select('package[]', $packages, null, ['id' => 'search-package', 'class' => 'populate placeholder select2', 'multiple' => 'multiple']) !!}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="search-free-trial" class="control-label col-xs-3">Package Free Trial</label>
                            <div class="col-xs-6">
                                {!! Form::select('packageFreeTrial', [0 => 'Not selected', 1 => 'Yes', 2 => 'No'], null, ['id' => 'search-package-free-trial', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <h4>Marketing</h4>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Affiliate ID</label>
                            <div class="col-xs-6">
                                {!! Form::text('affiliate_id', null, ['id' => 'search-affiliate-id', 'class' => 'form-control']) !!}
                            </div>
                        </div>

                        <hr/>
                        <div class="row">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-6">
                                {!! Form::submit('Search', ['class' => 'btn btn-success']) !!}
                            </div>
                        </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <div class="box-name">Subscriptions - Grid</div>
            </div>
            <div class="box-content">

                <table id="subscription-grid" class="display dt-responsive no-wrap"
                   data-url="{{ route('rest::v1.subscription.index') }}"
                   data-url-export="{{ route('rest::v1.subscription.export') }}"
                >
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Start</th>
                            <th>Status</th>
                            <th>R. Status</th>
                            <th>Account Id</th>
                            <th>Account Name</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Renewal</th>
                            <th>Free Trial</th>
                            <th>Freemium</th>
                            <th>End</th>
                            <th>Terminated at</th>
                            <th>Aff. ID</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection
