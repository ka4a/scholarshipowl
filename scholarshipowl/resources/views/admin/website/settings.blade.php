@extends("admin/base")
@section("content")

<div class="row">
    <div class="col-sm-12 clearfix" style="padding: 10px 30px;">
        <a class="btn btn-danger pull-right" href="{{ route('admin::website.clear-cache') }}">Clear cache</a>
    </div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		@foreach ($settings as $group => $setting)
			<div class="box">
				<div class="box-content">
					<p class="page-header">{{ $group }}</p>

					@foreach ($setting as $entity)
						<form action="/admin/website/post-settings" class="form-horizontal ajax_form" method="post" setting_id="{{ $entity->getSettingId() }}">
							<input type="hidden" name="setting_id" value="{{ $entity->getSettingId() }}" />
							<input type="hidden" id="name_{{ $entity->getSettingId() }}" name="name_{{ $entity->getSettingId() }}" value="{{ $entity->getName() }}" />
							<input type="hidden" id="type_{{ $entity->getSettingId() }}" name="type_{{ $entity->getSettingId() }}" value="{{ $entity->getType() }}" />

							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">{{ $entity->getTitle() }}</label>

									<div class="col-sm-4">
										@if ($entity->isInt() || $entity->isDecimal() || $entity->isString())
											{{ Form::text('value_' . $entity->getSettingId(), $entity->getValue() ? $entity->getValue() : $entity->getDefaultValue(), array("class" => "form-control", "id" => "value_" . $entity->getSettingId())) }}
										@elseif ($entity->isArray())
											{{ Form::select('value_' . $entity->getSettingId() . '[]', $entity->getOptions(), $entity->getValue(), array("class" => "select2", "multiple" => "multiple", "id" => "value_" . $entity->getSettingId())) }}
										@elseif ($entity->isText())
											{{ Form::textarea('value_' . $entity->getSettingId(), $entity->getValue(), array("class" => "form-control tinymce", "id" => "value_" . $entity->getSettingId())) }}
										@elseif ($entity->isSelect())
											{{ Form::select('value_' . $entity->getSettingId(), $entity->getOptions(), $entity->getValue(), array("class" => "select2", "id" => "value_" . $entity->getSettingId())) }}
										@endif
									</div>

									<div class="col-sm-2">
										<label class="control-label">Available in REST API </label>
{{--										{{ Form::hidden('isAvailableInRest_' . $entity->getSettingId(), 0, [ "id" => "isAvailableInRest_" . $entity->getSettingId()]) }}--}}
										{{ Form::checkbox('isAvailableInRest_' . $entity->getSettingId(), !$entity->getIsAvailableInRest(), $entity->getIsAvailableInRest(), [ "id" => "isAvailableInRest_" . $entity->getSettingId()]) }}
									</div>
									<div class="col-sm-2">
										<a href="#" setting_id="{{ $entity->getSettingId() }}" class="btn btn-primary SaveSettingButton">Save</a>
									</div>
								</div>
							</fieldset>
						</form>
					@endforeach
				</div>
			</div>
		@endforeach
	</div>
</div>


@stop
