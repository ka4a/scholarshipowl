<h4 class="sr-only sr-only-focusable">Interests</h4>

<form action="post-interests" method="post" class="ajax_from">
	{{ Form::token() }}
	@php
		$careerGoal = $profile->getCareerGoal();
		$careerGoalId = $careerGoal ? $careerGoal->getId() : null;
		$studyOnline = $profile->getStudyOnline();
	@endphp
	<div class="form-group col-sm-6">
		<label for="career_goal_id">What is your career goal ?</label>
		{{ Form::select('career_goal_id', $options['career_goals'], $careerGoalId, array('class' => 'selectpicker', 'data-width' => '100%'))}}

		<small data-error="career_goal_id" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">
		<label>Are you interested to study online ?</label>
		<div class="chkbox">
			<label>
				<input {{$studyOnline == 'yes' ? 'checked' : ''}} name="study_online" value="yes" type="radio">
				<span class="lbl padding-4">
		   			<span class="lblClr">Yes</span>
		   		</span>
			</label>
			<label>
				<input {{$studyOnline == 'no' ? 'checked' : ''}} name="study_online" value="no" type="radio">
				<span class="lbl padding-4">
		   			<span class="lblClr">No</span>
		   		</span>
			</label>
			<label>
				<input {{$studyOnline == 'maybe' ? 'checked' : ''}} name="study_online" value="maybe" type="radio">
				<span class="lbl padding-4">
		   			<span class="lblClr">Maybe</span>
		   		</span>
			</label>

		</div>

		<small data-error="study_online" class="help-block" style="display: none;"></small>
	</div>

	<div class="clearfix"></div>

	<div class="form-group col-sm-6 saveProfileChanges">
		<a class="btn btn-primary btn-block SaveProfile text-uppercase mod-user-profile-btn" href="#"  data-toggle="modal" data-target="#saveModal">save changes</a>
	</div>
</form>

