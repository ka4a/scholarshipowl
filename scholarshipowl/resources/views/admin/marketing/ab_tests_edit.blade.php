@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save AB Test</span>
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
				<form method="post" action="/admin/marketing/ab_tests/post-edit" class="form-horizontal ajax_form">
					{{ Form::token() }}
					{{ Form::hidden('ab_test_id', $abTest->getABTestId()) }}
					
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-6">
								{{ Form::text('name', $abTest->getName(), array("class" => "form-control", "disabled" => "disabled")) }}
							</div>
						</div>
						
						<div class="form-group">
                            <label class="col-sm-3 control-label">Description</label>
                            <div class="col-sm-6">
                                {{ Form::textarea('description', $abTest->getDescription(), array("class" => "form-control", "disabled" => "disabled")) }}
                            </div>
                        </div>
						
						<hr />
						
						<div id="PackageExpirationTypeDate">
							<div class="form-group">
								<label class="col-sm-3 control-label">Start Date</label>
								<div class="col-sm-3">
									{{ Form::text('start_date', substr($abTest->getStartDate(), 0, 10), array("class" => "form-control date_picker")) }}
								</div>
							</div>
						</div>
						
						<div id="PackageExpirationTypeDate">
							<div class="form-group">
								<label class="col-sm-3 control-label">End Date</label>
								<div class="col-sm-3">
									{{ Form::text('end_date', substr($abTest->getEndDate(), 0, 10), array("class" => "form-control date_picker")) }}
								</div>
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Is Active</label>
							<div class="col-sm-3">
								{{ Form::select('is_active', $options["active"], $abTest->isActive(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>
					</fieldset>
					
					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">Save AB Test</a>
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
				<a href="/admin/marketing/ab_tests" class="btn btn-default">Back To Tests</a>
			</p>
		</div>
	</div>
</div>
	
@stop
