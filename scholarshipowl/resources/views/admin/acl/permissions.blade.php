@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-university"></i>
                    <span>Pages Permissions</span>
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

            {{ Form::open(['route' => ['admin::acl.permissions-post', $role->getAdminRoleId()], 'id' => 'permissions-form', 'method' => 'post']) }}
            {{ Form::hidden('roleId', $role->getAdminRoleId()) }}
                <div class="box-content">
                    <div class="pull-left">
                        {{ Form::submit('Save permissions', ['class' => 'btn btn-danger']) }}
                    </div>
                    <div class="pull-right">
                        <button type='button' id='permission-form-check-all' class="btn">Check all</button>
                        <button type='button' id='permissoin-form-uncheck-all' class="btn">Un-check all</button>
                    </div>
                    <br/>
                    <br/>
                        <table class="table table-hover table-striped table-bordered table-heading">
                            <thead>
                            <tr>
                                <th>Permission</th>
                                <th>Enabled</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach (\App\Policies\RoutePolicy::getAvailablePermissions() as $permission => $description)
                                <tr>
                                    <td>
                                        {{ $description }}
                                    </td>

                                    <td>
                                        {{ Form::checkbox($permission, 1, $role->hasPermissionTo($permission) ) }}
                                    </td>
                                </tr>
                            @endforeach
                            <tr>
                                <td>
                                    Onboarding Calls View
                                </td>

                                <td>
                                    {{ Form::checkbox("account::onboarding-call.view", 1, $role->hasPermissionTo("account::onboarding-call.view") ) }}
                                </td>
                            </tr>
                            <tr>
                                <td>
                                    Onboarding Calls Edit
                                </td>

                                <td>
                                    {{ Form::checkbox("account::onboarding-call.update", 1, $role->hasPermissionTo("account::onboarding-call.update") ) }}
                                </td>
                            </tr>
                            </tbody>
                        </table>
                    {{ Form::submit('Save permissions', ['class' => 'btn btn-danger']) }}
                </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
@stop
