@extends("admin/base")
@section("content")
<div class="box">
    <div class="box-header">
        <div class="box-name">
            Transactional Email - Test
        </div>
    </div>
    <div class="box-content">
        @include('admin.marketing.transactional_email.test-form', [
            'transactionalEmailId' => $transactionalEmail->getTransactionalEmailId(),
        ])
    </div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Transactional Email</span>
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
                {{ Form::open(['method' => 'post', 'route' => ['admin::marketing.transactional_email.saveTransactionalEmail', $transactionalEmail->getTransactionalEmailId()], 'class' => 'form-horizontal']) }}

					<fieldset>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Subject</label>
                            <div class="col-xs-6">
                                {{ Form::text('subject', $transactionalEmail->getSubject(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-3 control-label">From Name</label>
                            <div class="col-xs-6">
                                {{ Form::text('from_name', $transactionalEmail->getFromName(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-3 control-label">From Email</label>
                            <div class="col-xs-6">
                                {{ Form::text('from_email', $transactionalEmail->getFromEmail(), ['class' => 'form-control']) }}
                            </div>
                        </div>

                        <hr/>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Event Name</label>
                            <div class="col-sm-6">
                                {{ Form::text("event_name", $transactionalEmail->getEventName(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Mandrill Template Slug</label>
                            <div class="col-sm-6">
                                {{ Form::text("template_name", $transactionalEmail->getTemplateName(), array("class" => "form-control")) }}
                            </div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Maximum Amount To Send</label>
                            <div class="col-sm-6">
                                {{ Form::text("sending_cap", $transactionalEmail->getSendingCap(), array("class" => "form-control")) }}
                            </div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Sending Period</label>
                            <div class="col-sm-2">
                                {{ Form::text('cap_value', $transactionalEmail->getCapValue(), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-sm-4">
                                {{ Form::select("cap_period", ["" => "Select"] + $transactionalEmail->getPeriodValues(), $transactionalEmail->getCapPeriod(), array("class" => "populate placeholder select2")) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-3 control-label">Delay</label>
                            <div class="col-xs-2">
                                {{ Form::text('delay_value', $transactionalEmail->getDelayValue(), ['class' => 'form-control']) }}
                            </div>
                            <div class="col-xs-4">
                                {{ Form::select('delay_type', ['' => 'Select'] + \App\Entity\TransactionalEmail::delayOptions(), $transactionalEmail->getDelayType(), ['class' => 'populate placeholder select2']) }}
                            </div>
                        </div>
					</fieldset>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Active</label>
                        <div class="col-sm-6">
                            {{ Form::select("active", ["1" => "Yes", "0" => "No"], $transactionalEmail->isActive()?1:0, array("class" => "populate placeholder select2")) }}
                        </div>
                    </div>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
                                {{ Form::submit('Save Transactional Email', ['class' => 'btn btn-primary']) }}
							</div>
						</div>
					</fieldset>
                {{ Form::close() }}
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/marketing/transactional_email" class="btn btn-default">Back To List</a>
			</p>
		</div>
	</div>
</div>

@stop
