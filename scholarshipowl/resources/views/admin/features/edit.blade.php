@extends("admin/base")
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    {{ (isset($set) ? 'Edit ' . $set->getName() : 'Create feature set') }}
                </div>
            </div>
            <div class="box-content">
                {{ Form::open(['route' => ['admin::features.edit', isset($set) ? $set->getId() : null], 'class' => 'form-horizontal']) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Name</label>
                        <div class="col-xs-6">
                            {{ Form::text('name', isset($set) ? $set->getName() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Desktop Payment Set</label>
                        <div class="col-xs-6">
                            {{ Form::select('desktop_payment_set', \App\Entity\FeaturePaymentSet::options(), isset($set) ? $set->getDesktopPaymentSet()->getId() : null, ['class' => 'populate placeholder select2']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Mobile Payment Set</label>
                        <div class="col-xs-6">
                            {{ Form::select('mobile_payment_set', \App\Entity\FeaturePaymentSet::options(), isset($set) ? $set->getMobilePaymentSet()->getId() : null,['class' => 'populate placeholder select2']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Content set</label>
                        <div class="col-xs-6">
                            {{ Form::select('content_set', \App\Entity\FeatureContentSet::options(), isset($set) ? $set->getContentSet()->getId() : null,['class' => 'populate placeholder select2']) }}
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
