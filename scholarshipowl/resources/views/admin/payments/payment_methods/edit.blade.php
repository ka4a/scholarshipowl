@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    Edit <b>{{ $set->getName()}}</b>
                </div>
            </div>
            <div class="box-content">
                {{ Form::open(['route' => ['admin::payments.payment_methods.edit', isset($set) ? $set->getId() : null], 'class' => 'form-horizontal']) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Id</label>
                        <div class="col-xs-6">
                            {{ Form::text('id', isset($set) ? $set->getId() : '', ['class' => 'form-control', 'readonly']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Name</label>
                        <div class="col-xs-6">
                            {{ Form::text('name', isset($set) ? $set->getName() : '', ['class' => 'form-control', 'readonly']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Company details set</label>
                        <div class="col-xs-6">
                            {{ Form::select('company_details_id', $companyDetailsList,
                             $set->getFeatureCompanyDetailsSet() ? $set->getFeatureCompanyDetailsSet()->getId() : null, ['class' => 'form-control',  'placeholder' => 'None..']) }}
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
