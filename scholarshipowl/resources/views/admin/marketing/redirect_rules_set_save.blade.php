@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Redirect Rules Set</span>
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

			<div class="box-content">
				<form method="post" action="/admin/marketing/redirect_rules_set/post-save" class="form-horizontal">
					{{ Form::token() }}
					{{ Form::hidden('redirect_rules_set_id', $redirect_rules_set->getId()) }}

					<div class="form-group">
						<label class="col-sm-3 control-label">Name</label>
						<div class="col-sm-3">
							{{ Form::text('name', $redirect_rules_set->getName(), array("class" => "form-control")) }}
						</div>
					</div>

					<fieldset>
                        <div class="form-group">
							<label class="col-sm-3 control-label">Type</label>
							<div class="col-sm-9">
								{{ Form::select('type', $options['rule_types'], $redirect_rules_set->getType(), array("class" => "populate placeholder form-control")) }}
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Rules</label>
							<div class="col-sm-9">
								<table class="table table-hover table-striped table-bordered table-heading" id="RedirectRulesTable">
									<thead>
										<tr>
											<th>Field</th>
											<th>Operator</th>
											<th>Value</th>
											<th>Active</th>
											<th>Actions</th>
										</tr>
									</thead>
									<tbody>
                                        @if($redirect_rules_set->getRedirectRules())
										@foreach ($redirect_rules_set->getRedirectRules() as $redirectRuleId => $redirectRule)
											<tr data-redirect_rule-id="{{ $redirectRuleId }}" id="{{ $redirectRuleId }}">
												<td>{{ Form::hidden('redirect_rule_id_' . $redirectRuleId, $redirectRuleId) }}
													{{ Form::select('redirect_rule_field_' . $redirectRuleId, $options['profile_fields'], $redirectRule->getField(), array("class" => "form-control populate placeholder")) }}</td>
												<td>{{ Form::select('redirect_rule_operator_' . $redirectRuleId, $options['operators'], $redirectRule->getOperator(), array("class" => "form-control populate placeholder")) }}</td>
												<td>{{ Form::text('redirect_rule_value_' . $redirectRuleId, $redirectRule->getValue(), array("class" => "form-control")) }}</td>
                                                    <td>{{ Form::checkbox('redirect_rule_active_' . $redirectRuleId, "1", $redirectRule->getActive()) }}</td>
												<td><a class="btn btn-danger DeleteRedirectRuleButton" data-redirect-rule-id="{{ $redirectRuleId }}" href="#">Delete</a></td>
											</tr>
										@endforeach
                                        @endif
									</tbody>
								</table>

								<p><a class="btn btn-primary AddRedirectRuleButton" href="#">Add Rule</a></p>
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary SaveButton" type="submit">Save Rules Set</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/marketing/redirect_rules_set" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

<div class="hidden">
	<div id="operators">
		@foreach($options['operators'] as $key => $name)
			<option value="{{ $key }}">{{ $name }}</option>
		@endforeach
	</div>

	<div id="profile-fields">
		@foreach($options['profile_fields'] as $profile_field)
			<option value="{{ $profile_field }}">{{ $profile_field }}</option>
		@endforeach
	</div>
</div>

@stop
