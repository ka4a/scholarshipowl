@extends("admin/base")
@section("content")

    <div class="row">
        <div class="col-xs-12 col-sm-12">
                <div class="box">
                    <div class="box-content">
                        <p class="page-header">Settings</p>
                        {{ Form::open(['route' => 'admin::applyme.settings.save', 'class' => 'form-horizontal', 'method' => 'POST']) }}
                            <fieldset>
                                @foreach($settings as $setting)
                                <div class="form-group">
                                        <label class="col-sm-3 control-label">{{ $setting->getTitle() }}</label>

                                        <div class="col-sm-4">
                                            {{ Form::text($setting->getName(), $setting->getValue(), array("class" => "form-control")) }}
                                        </div>
                                </div>
                                @endforeach
                                <div class="col-sm-2">
                                    {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                                </div>
                            </fieldset>
                        {{ Form::close() }}
                    </div>
                </div>
        </div>
    </div>


@stop
