<script>
window.eligibilityTypeMap = {!! json_encode(\App\Entity\Eligibility::$fields); !!};
</script>
<form method="post" action="/admin/scholarships/post-save-eligibility" class="form-horizontal ajax_form">
	{{ Form::token() }}
	{{ Form::hidden('scholarship_id', $scholarship->getScholarshipId()) }}

	<fieldset>
		<div class="form-group">
			<div class="col-sm-12">
				<table class="table table-bordered table-heading table-hover" id="ScholarshipEligibilityTable">
					@php($isSunrise = $scholarship->getApplicationType() === 'sunrise')
					<thead>
						<tr>
							<th>Field</th>
							<th>Type</th>
							<th>Value</th>
							@if($isSunrise)
							<th>Is optional</th>
							@endif
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($scholarship->getEligibilities() as $k => $eligibility)
						<tr id="eligibility_{{ time() }}" disabled="disabled">
							<td>
								{{ Form::select('eligibility_field[]', $fields, $eligibility->getField()->getFieldId(), array("class" => "select2", "disabled" => "disabled")) }}
								{{ Form::hidden('eligibility_field[]', $eligibility->getField()->getFieldId()) }}
							</td>
							<td>
								{{ Form::select('eligibility_type[]', $options["eligibility_types"], $eligibility->getType(), array("class" => "select2", "disabled" => "disabled")) }}
								{{ Form::hidden('eligibility_type[]', $eligibility->getType()) }}
							</td>
							<td>
								@php($multiValueOptionTypes = [
									\App\Entity\Eligibility::TYPE_IN,
									\App\Entity\Eligibility::TYPE_NIN,
									\App\Entity\Eligibility::TYPE_BETWEEN,
								])
								@if (array_key_exists($eligibility->getField()->getFieldId(), $options["multi_values"]))
                                    @if(in_array($eligibility->getType(), $multiValueOptionTypes))
                                        {{ Form::select('eligibility_value[]', $options[strtolower($options["multi_values"][$eligibility->getField()->getFieldId()])], explode(",", json_decode($eligibility->getValue())), array("class" => "select2", "disabled" => "disabled", "multiple" => "multiple")) }}
                                    @else
									    {{ Form::select('eligibility_value[]', $options[strtolower($options["multi_values"][$eligibility->getField()->getFieldId()])], json_decode($eligibility->getValue()), array("class" => "select2", "disabled" => "disabled")) }}
                                    @endif
								@else
									{{ Form::text('eligibility_value[]', json_decode($eligibility->getValue()), array("class" => "form-control", "disabled" => "disabled")) }}
								@endif

								{{ Form::hidden('eligibility_value[]', json_decode($eligibility->getValue())) }}
							</td>
							@if($isSunrise)
							<td>
								@php($isOptional = $eligibility->getIsOptional())
								{{ Form::hidden("eligibility_is_optional[{$k}]", 0) }}
								{{ Form::checkbox("eligibility_is_optional[{$k}]", 1, $eligibility->getIsOptional()) }}
							</td>
							@endif
							<td><a href='#' class='btn btn-danger DeleteEligibilityButton'>Delete</a></td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>

			<div class="col-xs-12">
				<div class="col col-12 pull-right">
					<a href="#" class="btn btn-primary" data-isSunrise="{!! (int)$isSunrise !!}" id="AddEligibilityButton">Add Eligibility</a>
				</div>
			</div>

			<div class="clearfix">&nbsp;</div>
		</div>
	</fieldset>

	<fieldset>
		<div class="form-group">
			<div class="col-sm-6">
				<a class="btn btn-primary SaveButton" href="#">Save Eligibility</a>
			</div>
		</div>
	</fieldset>
</form>
