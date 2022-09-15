<form method="post" action="/admin/scholarships/post-save-application" class="form-horizontal ajax_form" id="SaveScholarshipForm">
	{{ Form::token() }}
	{{ Form::hidden('scholarship_id', $scholarship->getScholarshipId()) }}

	<fieldset>
		<div class="form-group">
			<label class="col-sm-3 control-label">Application Type</label>
			<div class="col-sm-6">
			    @if($scholarship->getApplicationType() !== \App\Entity\Scholarship::APPLICATION_TYPE_SUNRISE)
				    {{ Form::select('application_type', $options['application_types'], $scholarship->getApplicationType(), array("class" => "populate placeholder select2")) }}
			    @else
                    <span style="position:relative;top: 5px">Sunrise</span>
			    @endif
			</div>
		</div>

		<div class="form-group" id="ApplyUrl">
			<label class="col-sm-3 control-label">Apply URL</label>
			<div class="col-sm-6">
				{{ Form::text('apply_url', $scholarship->getApplyUrl(), array("class" => "form-control")) }}
			</div>
		</div>

		<hr />

		<div id="ApplicationTypeOnlinePanel" style="display: none;">
			<div class="form-group">
				<label class="col-sm-3 control-label">Form Method</label>
				<div class="col-sm-6">
					{{ Form::select('form_method', $options['form_methods'], $scholarship->getFormMethod(), array("class" => "populate placeholder select2", "id" => "form_method")) }}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Form Action</label>
				<div class="col-sm-6">
					{{ Form::text('form_action', $scholarship->getFormAction(), array("class" => "form-control", "id" => "form_action")) }}
				</div>
			</div>

            <hr />

            <table class="table table-hover" id="OnlineDataTable">
                <thead>
                    <tr>
                        <th>Form Field</th>
                        <th>System Field</th>
                        <th>Value</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($online_data as $od)
                        <tr data-form-field="{{ $od['form_field'] }}">
                            <td>{{ $od['form_field'] }}</td>
                            <td>{{ $od['system_field'] }}</td>
                            <td>{{ $od['value'] }}</td>
                            <td>
                                <a class="btn btn-danger pull-right OnlineApplicationFormFieldButton" data-name="{{ $od['form_field'] }}"><i class="fa fa-edit"></i> Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td>
                            <button class="btn btn-success pull-right OnlineApplicationFormFieldButton"><i class="fa fa-plus"></i> Create Field</button>
                        </td>
                    </tr>
                </tfoot>
            </table>

			<hr />

			<div class="form-group">
				<div class="col-sm-9">
					<div id="OnlineApplicationFormContainer"></div>
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label"></label>

				<div class="col-sm-6">
					<a href="#" class="btn btn-success OnlineApplicationFetchFormButton">Get Online Form</a>
				</div>

				<div class="clearfix">&nbsp;</div>
			</div>
		</div>

		<div id="ApplicationTypeEmailPanel" style="display: none;">
			<div class="form-group">
				<label class="col-sm-3 control-label">Email</label>
				<div class="col-sm-6">
					{{ Form::text('email', $scholarship->getEmail(), array("class" => "form-control")) }}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Subject</label>
				<div class="col-sm-6">
					{{ Form::text('email_subject', $scholarship->getEmailSubject(), array("class" => "form-control")) }}
				</div>
			</div>

			<div class="form-group">
				<label class="col-sm-3 control-label">Body</label>
				<div class="col-sm-6">
					{{ Form::textarea('email_message', $scholarship->getEmailMessage(), array("class" => "form-control")) }}
				</div>
			</div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Dynamic Tags</label>
                <div class="col-sm-6 tags-control">
                    @foreach ($scholarshipEntity->getRequirementTexts() as $requirement)
                        @if($requirement->getSendType() === \App\Entity\RequirementText::SEND_TYPE_BODY)
                            <div class="row">
                                <div class="col-sm-12">
                                    <code class="tag">[[{{ $requirement->getPermanentTag() }}]]</code>
                                    <span>{{ $requirement->getTitle() }}</span>
                                </div>
                            </div>
                        @endif
                    @endforeach
                    @foreach ($scholarshipEntity->getRequirementInputs() as $requirement)
                        <div class="row">
                            <div class="col-sm-12">
                                <code class="tag">[[{{ $requirement->getPermanentTag() }}]]</code>
                                <span>{{ $requirement->getTitle() }}</span>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($scholarshipEntity->getRequirementSurvey() as $requirementSurvey)
                        <div class="row">
                            <div class="col-sm-12">
                                <code class="tag">[[{{ $requirementSurvey->getPermanentTag() }}]]</code>
                                <span>Survey answers</span>
                            </div>
                        </div>
                    @endforeach
                    @foreach ($scholarshipEntity->getRequirementSpecialEligibility() as $requirement)
                        <div class="row">
                            <div class="col-sm-12">
                                <code class="tag">[[{{ $requirement->getPermanentTag() }}]]</code>
                                <span>{{ $requirement->getTitle() }} (Special Eligibility)</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="form-group">
                <label class="col-sm-3 control-label">Generic Tags</label>
                <div class="col-sm-6 tags-control">
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[email]]</code><span>Account email</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[private_email]]</code><span>Private account email</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[first_name]]</code><span>Account first name</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[last_name]]</code><span>Account last name</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[full_name]]</code><span>Account full name `first_name last_name`</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[phone]]</code><span>Account phone</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[phone_mask]]</code><span>Account phone in format (XXX) XXX - XXXX</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[gender]]</code><span>Account gender</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[citizenship]]</code><span>Account citizenship</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[ethnicity]]</code><span>Account ethnicity</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[country]]</code><span>Account country</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[state]]</code><span>Account US state</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[state_name]]</code><span>Account non-US state</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[state_abbreviation]]</code><span>Account US state abbreviation (AL)</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[city]]</code><span>Account city</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[address]]</code><span>Account address</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[zip]]</code><span>Account ZIP/Postal code</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[school_level]]</code><span>Account school level</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[degree]]</code><span>Account degree</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[degree_type]]</code><span>Account degree type</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[enrollment_year]]</code><span>Account enrollment year</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[enrollment_month]]</code><span>Account enrollment month</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[gpa]]</code><span>Account GPA</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[career_goal]]</code><span>Account career goal</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[graduation_year]]</code><span>Account graduation year</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[graduation_month]]</code><span>Account graduation month</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[hs_graduation_year]]</code><span>Account high school graduation year</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[hs_graduation_month]]</code><span>Account high school graduation month</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[study_online]]</code><span>Account study online</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[highschool]]</code><span>Account highschool</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[highschool_address]]</code><span>Account highs school address</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[university]]</code><span>Account university</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[university_address]]</code><span>Account university address</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[date_of_birth]]</code><span>Account date of birth (MM/DD/YYYY)</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[age]]</code><span>Account age</span>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <code class="tag">[[username]]</code><span>Account username</span>
                        </div>
                    </div>
                </div>
            </div>

		</div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Send to private e-mail</label>
            <div class="col-sm-3">
                {{ Form::select('send_to_private', $options['send_to_private'], $scholarship->getSendToPrivate(), array("class" => "populate placeholder select2")) }}
            </div>
        </div>
	</fieldset>

	<fieldset>
		<div class="form-group">
			<div class="col-sm-6">
				<a class="btn btn-primary SaveButton" href="#">Save Application</a>
			</div>
		</div>
	</fieldset>
</form>


