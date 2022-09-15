@extends("admin.base")
@section("content")

    @can('access-route', 'export')
        <div class="row">
            <div class="col-xs-12">
                <p align="right">Export ({{ $count }}): <a
                            href="/admin/export/edumax?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
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
                    <form method="get" action="/admin/call-center/edumax" class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Domain</label>
                                <div class="col-sm-6">
                                    {{ Form::select('domain', $options['domains'], $search['domain'], ["class" => "form-control"]) }}
                                </div>
                            </div>

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

                            <hr/>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Has Subscription</label>
                                <div class="col-sm-3">
                                    {{ Form::select('has_active_subscription', $options['paid_subscriptions'], $search['has_active_subscription'], array("class" => "populate placeholder select2")) }}
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label">Paid</label>
                                <div class="col-sm-3">
                                    {{ Form::select('paid', $options['paid_subscriptions'], $search['paid'], array("class" => "populate placeholder select2")) }}
                                </div>
                            </div>

                            <hr/>

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

                            <hr/>

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
                            <th>Paid</th>
                            <th>Package</th>
                            <th>Created</th>
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
                                    <a href="{{ route('admin::accounts.view', ['id' => $account->getAccountId()]) }}"
                                       target="_blank"
                                       title="Edit Profile: {{ $account->getProfile()->getFullName() }}">
                                        {{ $account->getProfile()->getFirstName() }} {{ $account->getProfile()->getLastName()  }} </a>
                                </td>

                                <td>
                                    @if (array_key_exists($account->getAccountId(), $subscriptions))
                                        @if ($subscriptions[$account->getAccountId()]->isPaid())
                                            Yes
                                        @else
                                            No
                                        @endif
                                    @else
                                        No
                                    @endif
                                </td>

                                <td>
                                    @if (array_key_exists($account->getAccountId(), $subscriptions))
                                        {{ $subscriptions[$account->getAccountId()]->getName() }}
                                    @endif
                                </td>

                                <td>{{ format_date($account->getCreatedDate()) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    @include ('admin.common.pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-xs-12">
            <p align="right">Export ({{ $count }}): <a
                        href="/admin/export/edumax?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
        </div>
    </div>


@stop
