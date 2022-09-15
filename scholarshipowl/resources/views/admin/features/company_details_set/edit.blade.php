@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    {{ (isset($set) ? 'Edit ' . $set->getCompanyName() : 'Create company details set') }}
                </div>
            </div>
            <div class="box-content">
                {{ Form::open(['route' => ['admin::features.company_details_set.edit', isset($set) ? $set->getId() : null], 'class' => 'form-horizontal']) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Fset title</label>
                        <div class="col-xs-6">
                            {{ Form::text('name', isset($set) ? $set->getName() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Company name</label>
                        <div class="col-xs-6">
                            {{ Form::text('company_name', isset($set) ? $set->getCompanyName() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Company name 2</label>
                        <div class="col-xs-6">
                            {{ Form::text('company_name_2', isset($set) ? $set->getCompanyName2() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Address 1</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('address_1', isset($set) ? $set->getAddress1() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Address 2</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('address_2', isset($set) ? $set->getAddress2() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                </fieldset>
                <hr/>
                <div class="row">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-9">{{ Form::submit('Save', ['class' => 'btn btn-success']) }}</div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
