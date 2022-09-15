@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Popup</span>
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
				<form method="post" action="/admin/popup/post-save" class="form-horizontal ajax_form" id="SavePopupForm">
					{{ Form::token() }}
					{{ Form::hidden('popup_id', $popup->getPopupId()) }}

					<fieldset>

						<div class="form-group">
							<label class="col-sm-3 control-label">Display</label>
							<div class="col-sm-6">
								{{ Form::select('popup_display', $options["popup_display_types"], $popup->getPopupDisplay(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Priority</label>
							<div class="col-sm-6">
								{{ Form::number('priority', $popup->getPriority(),  array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Title</label>
							<div class="col-sm-6">
								{{ Form::text('popup_title', $popup->getPopupTitle(), array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Text</label>
							<div class="col-sm-6">
								{{ Form::textarea('popup_text', $popup->getPopupText(), array("class" => "form-control tinymce")) }}
							</div>
						</div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Exit Dialogue Text</label>
                            <div class="col-sm-6">
                                {{ Form::textarea('popup_exit_dialogue_text', $popup->getPopupExitDialogueText(), array("class" => "form-control", "rows" => "2")) }}
                            </div>
                        </div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Delay</label>
                            <div class="col-sm-6">
                                {{ Form::text('popup_delay', $popup->getPopupDelay(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Times To Show To User</label>
                            <div class="col-sm-6">
                                {{ Form::text('popup_display_times', $popup->getPopupDisplayTimes(), array("class" => "form-control")) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Trigger Payment Popup</label>
                            <div class="col-sm-3">
                                {{ Form::select('trigger_upgrade', $options["trigger_upgrade"], $popup->isTriggerUpgrade(), array("class" => "populate placeholder select2")) }}
                            </div>
                        </div>

                        <hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Start Date</label>

							<div class="col-sm-6">
								{{ Form::text('start_date', format_date($popup->getStartDate()), array("class" => "form-control date_picker")) }}
							</div>
						</div>


						<div class="form-group">
							<label class="col-sm-3 control-label">End Date</label>

							<div class="col-sm-6">
								{{ Form::text('end_date', format_date($popup->getEndDate()), array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Type</label>
							<div class="col-sm-6">
								{{ Form::select('popup_type', $options["popup_types"], $popup->getPopupType(), array("class" => "populate placeholder select2")) }}
							</div>
						</div>


                        <div id="PopupTypePackage">
                            <div class="form-group">
                                <label class="col-sm-3 control-label">Package</label>
                                <div class="col-sm-6">
                                    {{ Form::select('popup_target_id_package', $options["packages"], $popup->getPopupTargetId(), array("class" => "populate placeholder select2")) }}
                                </div>
                            </div>
                        </div>

                        <hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Pages</label>
                            <div class="col-sm-6">
                                {{ Form::select('pages[]', $options["pages"], $options["used_pages"], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Redirect Rule</label>
                            <div class="col-sm-6">
                                {{ Form::select('rule_set_id', $options["redirect_rules_sets"], $popup->getRuleSetId(), array("class" => "populate placholder select2")) }}
                            </div>
                        </div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">Save Popup</a>
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
				<a href="/admin/popup/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
