{{ Form::open([ 'method' => 'post', 'files' => true, 'class' => 'form-horizontal', 'url' => '/admin/scholarships/post-save-information']) }}
	{{ Form::hidden('scholarship_id', $scholarship->getScholarshipId()) }}

	<fieldset>
        @if($scholarship->getApplicationType() == $scholarship::APPLICATION_TYPE_SUNRISE)
        <div class="form-group">
            <label class="col-sm-3 control-label">External id</label>
            <div class="col-sm-3">
                {{ $scholarship->getExternalScholarshipId() }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">External template id</label>
            <div class="col-sm-3">
                {{ $scholarship->getExternalScholarshipTemplateId() }}
            </div>
        </div>
        @endif

        <div class="form-group">
            <label class="col-sm-3 control-label">Status</label>
            <div class="col-sm-3">
                {{ Form::select('status', $options['status'], $scholarship->getStatus(), array("class" => "form-control")) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Is Active</label>
            <div class="col-sm-3">
                {{ Form::select('is_active', $options['active'], $scholarship->isActive(), array("class" => "populate placeholder select2")) }}
            </div>
        </div>

        <hr />

        <div class="form-group">
			<label class="col-sm-3 control-label">Title</label>
			<div class="col-sm-6">
				{{ Form::text('title', $scholarship->getTitle(), array("class" => "form-control")) }}
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Description</label>
			<div class="col-sm-6">
				{{ Form::textarea('description', $scholarship->getDescription(), array("class" => "form-control")) }}
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">URL</label>
			<div class="col-sm-6">
				{{ Form::text('url', $scholarship->getUrl(), array("class" => "form-control")) }}
			</div>
		</div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Logo</label>
            <div class="col-sm-6">
                {{ Form::file('logo') }}

                @if ($scholarship->getLogo())
                    <br />
                    <img width="550" src="{{ Storage::public($scholarship->getLogo()) }}" />
                    <br /><br />
                    <button id="remove-scholarship-logo" class="btn btn-danger">Remove Logo</button>
                @endif
            </div>
        </div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Image</label>
			<div class="col-sm-6">
				{{ Form::file('image') }}

				@if ($image = $scholarship->getImage())
					<br />
					<img width="550" src="{{  \Storage::public ($image) }}" />
					<br /><br />
				@endif
			</div>
		</div>

		<hr />

        <div class="form-group">
            <label class="col-xs-3 control-label">Timezone</label>
            <div class="col-xs-6">
                {{ Form::select('timezone', $options['timezones'], isset($scholarshipEntity) ? $scholarshipEntity->getTimezone() : 'US/Pacific', array("class" => "populate placeholder select2")) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-3 control-label">Start Date</label>
            <div class="col-xs-6">
                <div class="row">
                    @php($startDate = $scholarship->getStartDate() ?: (new \DateTime())->format('Y-m-d'))
                    <div class="col-xs-6">{{ Form::text('start_date', format_date($startDate), ['class' => 'form-control date_picker']) }}</div>
                    <div class="col-xs-3">{{ Form::select('start_date_hour', $options['hours'], isset($scholarshipEntity) ? $scholarshipEntity->getStartDate()->format('H') : null, ['class' => 'form-control']) }}</div>
                    <div class="col-xs-3">{{ Form::select('start_date_minutes', $options['minutes'], isset($scholarshipEntity) ? $scholarshipEntity->getStartDate()->format('i') : null, ['class' => 'form-control']) }}</div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::checkbox('recurrence_start_now', 1, isset($scholarshipEntity) ? $scholarshipEntity->getRecurrenceStartNow() : null , ['id' => 'recurrence-start-now']) }}
                        <label for="recurrence-start-now">Start after recurrence.</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group">
			<label class="col-sm-3 control-label">Deadline</label>
			<div class="col-sm-6">
                <div class="row">
                    <div class="col-xs-6">{{ Form::text('expiration_date', format_date($scholarship->getExpirationDate()), array("class" => "form-control date_picker")) }}</div>
                    <div class="col-xs-3">{{ Form::select('expiration_date_hour', $options['hours'], isset($scholarshipEntity) ? $scholarshipEntity->getExpirationDate()->format('H') : 23, ['class' => 'form-control']) }}</div>
                    <div class="col-xs-3">{{ Form::select('expiration_date_minutes', $options['minutes'], isset($scholarshipEntity) ? $scholarshipEntity->getExpirationDate()->format('i') : 59, ['class' => 'form-control']) }}</div>
                </div>
                <div class="row">
                    <div class="col-xs-12">
                        {{ Form::checkbox('recurrence_end_month', 1, isset($scholarshipEntity) ? $scholarshipEntity->getRecurrenceEndMonth() : null, ['id' => 'recurrence-end-month']) }}
                        <label for="recurrence-end-month">End of month</label>
                    </div>
                </div>
            </div>
		</div>

        <hr />

        <div class="form-group">
            <label class="col-xs-3 control-label">Recurrent</label>
            <div class="col-xs-6">
                {{ Form::select('is_recurrent', $options['is_recurrent'], isset($scholarshipEntity) ? $scholarshipEntity->getIsRecurrent()?1:0 : 0, array("class" => "populate placeholder select2")) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-xs-3 control-label">Recurrence Period Type</label>
            <div class="col-xs-6">
                {{ Form::select('recurring_type', \App\Entity\Traits\Recurrable::$recurrenceTypes, isset($scholarshipEntity) ? $scholarshipEntity->getRecurringType() : null, array("class" => "populate placeholder select2")) }}
            </div>
        </div>

        <div class="form-group">
            <label class="col-sm-3 control-label">Recurrence Period Value</label>
            <div class="col-sm-6">
                {{ Form::text('recurring_value', isset($scholarshipEntity) ? $scholarshipEntity->getRecurringValue() : null, array("class" => "form-control")) }}
            </div>
        </div>

        <hr />

		<div class="form-group">
			<label class="col-sm-3 control-label">Awards</label>
			<div class="col-sm-3">
				{{ Form::text('awards', $scholarship->getAwards(), array("class" => "form-control")) }}
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Amount</label>
			<div class="col-sm-3">
				{{ Form::text('amount', $scholarship->getAmount(), array("class" => "form-control")) }}
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Up To</label>
			<div class="col-sm-3">
				{{ Form::text('up_to', $scholarship->getUpTo(), array("class" => "form-control")) }}
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Is Free</label>
			<div class="col-sm-3">
				{{ Form::select('is_free', $options['free'], $scholarship->isFree(), array("class" => "populate placeholder select2")) }}
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Is Automatic</label>
			<div class="col-sm-3">
				{{ Form::select('is_automatic', $options['automatic'], $scholarship->isAutomatic(), array("class" => "populate placeholder select2")) }}
			</div>
		</div>

		<hr />

		<div class="form-group">
			<label class="col-sm-3 control-label">Terms Of Service URL</label>
			<div class="col-sm-6">
				{{ Form::text('terms_of_service_url', $scholarship->getTermsOfServiceUrl(), array("class" => "form-control")) }}
			</div>
		</div>

		<div class="form-group">
			<label class="col-sm-3 control-label">Privacy Policy URL</label>
			<div class="col-sm-6">
				{{ Form::text('privacy_policy_url', $scholarship->getPrivacyPolicyUrl(), array("class" => "form-control")) }}
			</div>
		</div>

        <hr />

        <div class="form-group">
            <label class="col-xs-3 control-label">Notes</label>
            <div class="col-xs-6">
                {{ Form::textarea('notes', isset($scholarshipEntity) ? $scholarshipEntity->getNotes() : null, ['class' => 'form-control']) }}
            </div>
        </div>

    </fieldset>

	<fieldset>
		<div class="form-group">
			<div class="col-sm-6">
				{{ Form::submit('Save Information', ['class' => 'btn btn-primary']) }}
			</div>
		</div>
	</fieldset>
{{ Form::close() }}
