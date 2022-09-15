@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Affiliate Goal Mapping</span>
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
				<form method="post" action="/admin/marketing/affiliate_goal_mapping/post-save" class="form-horizontal">
					{{ Form::token() }}
					{{ Form::hidden('affiliate_goal_mapping_id', $affiliate_goal_mapping->getAffiliateGoalMappingId()) }}
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">URL Parameter</label>
							<div class="col-sm-6">
								{{ Form::text('url_parameter', $affiliate_goal_mapping->getUrlParameter(), array("class" => "form-control")) }}
							</div>
						</div>
					</fieldset>
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Redirect Rules Set</label>
							<div class="col-sm-3">
								{{ Form::select('redirect_rules_set_id', $options["redirect_rules"], $affiliate_goal_mapping->getRedirectRulesSetId(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Goal ID if rules met</label>
                            <div class="col-sm-3">
                                {{ Form::text('affiliate_goal_id', $affiliate_goal_mapping->getAffiliateGoalId(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Goal ID if rules not met</label>
                            <div class="col-sm-3">
                                {{ Form::text('affiliate_goal_id_secondary', $affiliate_goal_mapping->getAffiliateGoalIdSecondary(), array("class" => "form-control")) }}
                            </div>
                        </div>
					</fieldset>


					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary SaveButton" type="submit">Save Affiliate Goal Mapping</button>
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
				<a href="/admin/marketing/affiliate_goal_mapping" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
