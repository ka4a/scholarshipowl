@extends("admin.base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Coreg Plugin</span>
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
				<div id="tabs">
					<ul>
						<li><a href="#tab-base">Base form</a></li>
						<li><a href="#tab-requirements">Requirements</a></li>
					</ul>
					{{ Form::open(['method' => 'post', 'route' => 'admin::marketing.coregs.post-save', 'class' => 'form-horizontal']) }}
						<div id="tab-base">

							{{ Form::token() }}
							{{ Form::hidden("coreg_plugin_id", $coreg_plugin->getId()) }}

							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">Name</label>
									<div class="col-sm-6">
										{{ Form::select("name", $names, $coreg_plugin->getName(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Is Visible</label>
									<div class="col-sm-6">
										{{ Form::select("is_visible", $flag_selector, $coreg_plugin->getVisible(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Just collect, not send</label>
									<div class="col-sm-6">
										{{ Form::select("just_collect", $flag_selector, $coreg_plugin->isJustCollect(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Text</label>
									<div class="col-sm-6">
										{{ Form::textarea("text", $coreg_plugin->getText(), array("class" => "form-control")) }}
									</div>
								</div>

								<div class="form-group">
									<label class="col-sm-3 control-label">Monthly Cap</label>
									<div class="col-sm-6">
										{{ Form::text("monthly_cap", $coreg_plugin->getMonthlyCap(), array("class" => "form-control")) }}
									</div>
								</div>
							</fieldset>

							<fieldset>
								<div class="form-group">
									<label class="col-sm-3 control-label">Display Position</label>
									<div class="col-sm-6">
										{{ Form::select("display_position", $display_positions, $coreg_plugin->getDisplayPosition(), array("class" => "populate placeholder select2")) }}
									</div>
								</div>
							</fieldset>

							<fieldset>
								<div class="form-group">
									<div class="col-sm-6">
										<button class="btn btn-primary SaveCoregPlugin" type="submit">Save Coreg Plugin</button>
									</div>
								</div>
							</fieldset>
						</div>
						<div id="tab-requirements">
							@include ("admin/marketing/coregs/coreg_plugin_requirements")
						</div>
					{{ Form::close() }}
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/marketing/coreg_plugin" class="btn btn-default">Back To List</a>
			</p>
		</div>
	</div>
</div>

@stop
