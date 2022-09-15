@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Account</span>
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
				<form method="post" action="/admin/accounts/post-register" class="form-horizontal ajax_form">
					{!! Form::token() !!}

					<fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Domain</label>
                            <div class="col-sm-6">
                                {!! Form::select('domain_id', $options['domains'], \App\Entity\Domain::SCHOLARSHIPOWL, ["class" => "form-control"]) !!}
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-6">
								{!! Form::text('email', "", array("class" => "form-control")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">First Name</label>
							<div class="col-sm-6">
								{!! Form::text('first_name', "", array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Last Name</label>
							<div class="col-sm-6">
								{!! Form::text('last_name', "", array("class" => "form-control")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Account Status</label>
							<div class="col-sm-6">
								{!! Form::select('account_status_id', $options['account_statuses'], "", array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Account Type</label>
							<div class="col-sm-6">
								{!! Form::select('account_type_id', $options['account_types'], "", array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Password</label>
							<div class="col-sm-6">
								{!! Form::password('password', [], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Retype Password</label>
							<div class="col-sm-6">
								{!! Form::password('retype_password', [], array("class" => "form-control")) !!}
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">Register Account</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>



@stop
