@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Affiliate</span>
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
				<form method="post" action="/admin/marketing/affiliates/post-save" class="form-horizontal" enctype="multipart/form-data">
					{{ Form::token() }}
					{{ Form::hidden('affiliate_id', $affiliate->getAffiliateId()) }}
					{{ Form::hidden('affiliate_goal_id', $goal->getAffiliateGoalId()) }}
					
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Name *</label>
							<div class="col-sm-6">
								{{ Form::text('name', $affiliate->getName(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Is Active *</label>
							<div class="col-sm-3">
								{{ Form::select('is_active', $options["active"], $affiliate->isActive(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Description</label>
							<div class="col-sm-6">
								{{ Form::textarea('description', $affiliate->getDescription(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Name *</label>
							<div class="col-sm-6">
								{{ Form::text('goal_name', $goal->getName(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Value *</label>
							<div class="col-sm-6">
								{{ Form::text('goal_value', $goal->getValue(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal URL *</label>
							<div class="col-sm-6">
								{{ Form::text('goal_url', $goal->getUrl(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Description</label>
							<div class="col-sm-6">
								{{ Form::textarea('goal_description', $goal->getDescription(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Redirect Description</label>
							<div class="col-sm-6">
								{{ Form::textarea('goal_redirect_description', $goal->getRedirectDescription(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Goal Redirect Time</label>
                            <div class="col-sm-6">
                                {{ Form::text('goal_redirect_time', $goal->getRedirectTime(), array("class" => "form-control")) }}
                            </div>
                        </div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Logo</label>
							<div class="col-sm-6">
								{{ Form::file('goal_logo') }}
								
								<br />
								@if ($goal->getLogo())
									<img src="{{ url('/system/affiliate/'.$goal->getLogo()) }}" />
								@endif
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-6">
								{{ Form::text('email', $affiliate->getEmail(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Phone</label>
							<div class="col-sm-6">
								{{ Form::text('phone', $affiliate->getPhone(), array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Website</label>
							<div class="col-sm-6">
								{{ Form::text('website', $affiliate->getWebsite(), array("class" => "form-control")) }}
							</div>
						</div>
					</fieldset>
					
					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary" type="submit">Save Affiliate</button>
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
				<a href="/admin/marketing/affiliates" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>
	
@stop
