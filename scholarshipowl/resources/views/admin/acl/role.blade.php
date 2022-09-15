@extends('admin.base')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @section('box-content')
            {{ Form::open(['method' => 'post', 'route' => 'admin::acl.role-post', 'class' => 'form-horizontal']) }}
                @if ($role)
                    {{ Form::hidden('roleId', $role->getAdminRoleId()) }}
                @endif

                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Name</label>
                        <div class="col-sm-6">
                            {{ Form::text('name', $role ? $role->getName() : '', array("class" => "form-control")) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Access Level</label>
                        <div class="col-sm-6">
                            {{ Form::select('access_level', $options['access_levels'], $role ?
                             $role->getAccessLevel() : '', ["class" => "populate placeholder select2"]) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Description</label>
                        <div class="col-sm-6">
                            {{ Form::textarea('description', $role ? $role->getDescription() : '', array("class" => "form-control")) }}
                        </div>
                    </div>
                </fieldset>

                <fieldset>
                    <div class="form-group">
                        <div class="col-sm-3"></div>
                        <div class="col-sm-6">
                            {{ Form::submit($role ? 'Save' : 'Create', ['class' => 'btn btn-success']) }}
                        </div>
                    </div>
                </fieldset>
            {{ Form::close() }}
            @overwrite
            @include('admin.common.box', ['boxName' => 'Role'])
        </div>
    </div>
@stop
