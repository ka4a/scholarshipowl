@extends('admin.base')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @section('box-content')
                {{ Form::open(['route' => 'admin::acl.admin-post', 'method' => 'post', 'class' => 'form-horizontal']) }}
                    @if ($admin) {{ Form::hidden('adminId', $admin->getAdminId()) }}@endif

                    <fieldset>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Role</label>
                            <div class="col-xs-6">
                                {{ Form::select('role', $options['role'], $admin ? $admin->getAdminRole()->getAdminRoleId() : null, ['class' => 'select2']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Status</label>
                            <div class="col-sm-6">
                                {{ Form::select('status', $options['status'], $admin ? $admin->getStatus() : null, ['class' => 'select2']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Name</label>
                            <div class="col-xs-6">
                                {{ Form::text('name', $admin ? $admin->getName() : '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Email</label>
                            <div class="col-xs-6">
                                {{ Form::text('email', $admin ? $admin->getEmail() : '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Password</label>
                            <div class="col-xs-6">
                                {{ Form::text('password', '', ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                {{ Form::submit($admin ? 'Save' : 'Create', ['class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    </fieldset>
                {{ Form::close() }}
            @overwrite
            @include('admin.common.box', ['boxName' => 'Admin'])
        </div>
    </div>
@stop
