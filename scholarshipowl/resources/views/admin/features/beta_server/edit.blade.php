@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-cog"></i>
                    {{ isset($test) ? sprintf('Edit Ab Test (%s)', $test->getName()) : 'Create Ab Test' }}
                </div>
            </div>
            <div class="box-content">
                {{ Form::open(['route' => ['admin::features.ab_tests.edit', isset($test) ? $test->getId() : null], 'class' => 'form-horizontal']) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Name</label>
                        <div class="col-xs-6">
                            {{ Form::text('name', isset($test) ? $test->getName() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Enabled</label>
                        <div class="col-xs-6">
                            {{ Form::select('enabled', [0 => 'No', 1 => 'Yes'], isset($test) ? $test->isEnabled() ? 1 : 0 : 0, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Feature Set</label>
                        <div class="col-xs-6">
                            {{ Form::select('feature_set', \App\Entity\FeatureSet::options(), isset($test) ? $test->getFeatureSet()->getId() : null, ['class' => 'populate placeholder select2']) }}
                        </div>
                    </div>
                </fieldset>
                <hr/>
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Traffic percentage (1-99)</label>
                        <div class="col-xs-6">
                            {{ Form::text('config[percentage]', isset($test) ? $test->getConfig()['percentage'] ?? null : null, ['class' => 'form-control']) }}
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
