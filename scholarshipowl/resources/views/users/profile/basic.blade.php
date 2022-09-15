<h4 class="sr-only sr-only-focusable">Basic</h4>

<form action="post-basic" method="post" class="ajax_from">
	{{ Form::token() }}

	<div class="form-group col-sm-6">
		<label for="first_name">First Name</label>

		<div class="input-group">
			<label for="first_name" class="sr-only">First Name</label>
			{{ Form::text("first_name", $user->getProfile()->getFirstName(), array("placeholder" => "First Name", "required" => "", "class" => "form-control")) }}
		</div>

		<small data-error="first_name" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">

		<label for="last_name">Last Name</label>

		<div class="input-group">
			<label for="last_name" class="sr-only">Last Name</label>
			{{ Form::text("last_name", $user->getProfile()->getLastName(), array("placeholder" => "Last Name", "required" => "", "class" => "form-control")) }}
		</div>

		<small data-error="last_name" class="help-block" style="display: none;"></small>
	</div>


	<div class="form-group col-sm-6">
		<label for="phone">Phone</label>

		<div class="input-group">
			<label for="phone" class="sr-only">Phone</label>
			{{ Form::text("phone", $user->getProfile()->getPhone(), array("type" => "tel", "placeholder" => "Phone", "required" => "", "class" => "form-control")) }}
		</div>

		<small data-error="phone" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6" id="date_of_birth">
		<label for="dob">Birthday</label>

		<div class="btn-group">
			<label class="sr-only">Month</label>
			{!!
				Form::select("birthday_month", $options["birthday_months"], $user->getProfile()->getDateOfBirthMonth(false),
				array("class"=>"selectpicker pull-left", "data-width" => "30%", 'data-size'=>'7', 'data-live-search'=>'true', "title" => "Month", "required" => ""))
			!!}

			<label class="sr-only">Day</label>
			{!!
				Form::select("birthday_day", $options["birthday_days"], $user->getProfile()->getDateOfBirthDay(false),
				array("class"=>"selectpicker pull-left", "data-width" => "30%", 'data-size'=>'7', 'data-live-search'=>'true', "title" => "Day", "required" => ""))
			!!}

			<label class="sr-only">Year</label>
			{!!
				Form::select("birthday_year", $options["birthday_years"], $user->getProfile()->getDateOfBirthYear(),
				array("class"=>"selectpicker pull-left", "data-width" => "40%", 'data-size'=>'7', 'data-live-search'=>'true', "title" => "Year", "required" => ""))
			!!}
		</div>

		<small data-error="date_of_birth" class="help-block" style="display: none;"></small>
	</div>


	<div class="form-group col-sm-6 my-account__citizenship">
		<label for="citizenship_id">Citizenship</label>
		@php($citizenshipId = $user->getProfile()->getCitizenship() ? $user->getProfile()->getCitizenship()->getId() : '')
		{{ Form::select("citizenship_id", $options["citizenships"], $citizenshipId, array("class" => "selectpicker", "data-width" => "100%")) }}

		<small data-error="citizenship_id" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">
		<label for="ethnicity_id">Ethnic Background</label>
		@php($ethnicityId = $user->getProfile()->getEthnicity() ? $user->getProfile()->getEthnicity()->getId() : '')
		{{ Form::select("ethnicity_id", $options["ethnicities"], $ethnicityId, array("class" => "selectpicker", "data-width" => "100%")) }}

		<small data-error="ethnicity_id" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">
		<div class="input-group">
            <label for="zip">Zip / Postal code</label>
			{{ Form::text("zip", $user->getProfile()->getZip(), array("type" => "text", "class" => "form-control" )) }}
		</div>

		<small data-error="zip" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">
        <label for="state_id">State / Province / Region</label>
        @if($user->isUSA())
        	@php($stateId = $user->getProfile()->getState() ? $user->getProfile()->getState()->getId() : '')
            {{ Form::select("state_id", $options["states"], $stateId, array("class" => "selectpicker", "data-width" => "100%", 'data-size'=>'8', 'data-live-search'=>'true')) }}

            <small data-error="state_id" class="help-block" style="display: none;"></small>
        @else
            {{ Form::text("state_name", $user->getProfile()->getStateName(), array("class" => "form-control", "placeholder" => "State / Province / Region", "required" => "")) }}
            <small data-error="state_name" class="help-block" style="display: none;"></small>
        @endif
	</div>

	<div class="form-group col-sm-6">
		<div class="input-group">
			<label for="address">Address</label>
			{{ Form::text("address", $user->getProfile()->getAddress(), array("placeholder" => "Street address, P.O. box, company name", "class" => "form-control")) }}
		</div>

		<small data-error="address" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">
        <label for="address2">&nbsp;</label>
        {{ Form::text("address2", $user->getProfile()->getAddress2(), array("type" => "text", "class" => "form-control", "placeholder" => "Apartment, suite, unit, building, floor, etc.")) }}
	</div>

	<div class="form-group col-sm-6">
		<div class="input-group">
			<label>City</label>
			{{ Form::text("city", $user->getProfile()->getCity(), array("type" => "text", "class" => "form-control")) }}
		</div>

		<small data-error="city" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">
		<label>Gender</label>
		<div class="chkbox">
	   		@foreach($options["genders"] as $genderKey => $genderValue)
				<label>
				{{ Form::radio("gender", $genderKey, strtolower($user->getProfile()->getGender()) == $genderKey) }}

				<span class="lbl padding-8">
		   			<span class="lblClr">{{ $genderValue }}</span>
		   		</span>
				</label>
	   		@endforeach
		</div>

		<small data-error="gender" class="help-block" style="display: none;"></small>
	</div>

	<div class="form-group col-sm-6">
		<label>Receive Promotions</label>
		<div class="chkbox">
	   		@foreach($options["subscriptions"] as $subscriptionKey => $subscriptionValue)
				<label>
					{{ Form::radio("is_subscribed", $subscriptionKey, $user->getProfile()->getIsSubscribed() == $subscriptionKey) }}

					<span class="lbl padding-8">
			   			<span class="lblClr">{{ $subscriptionValue }}</span>
			   		</span>
				</label>
	   		@endforeach
		</div>

		<small data-error="is_subscribed" class="help-block" style="display: none;"></small>
	</div>

    <div class="col-xs-12 col-sm-6">
        <div class="form-group">
            <div class="form-group">
                <label for="goal">Military affiliation</label>
                @php($militaryAffiliationId = $military_affiliation ? $military_affiliation->getMilitaryAffiliationId() : '')
                {{ Form::select('military_affiliation_id', $options['military_affiliations'], $militaryAffiliationId, array('class' =>"selectpicker", "data-width" => "100%", "data-live-search" => "true")) }}
            </div>
        </div>
    </div>

    <div class="clearfix"></div>


	<div class="form-group col-xs-12 col-sm-6 saveProfileChanges">
		<a class="btn btn-primary btn-block text-uppercase SaveProfile mod-user-profile-btn" href="#"  data-toggle="modal" data-target="#saveModal">save changes</a>
	</div>
</form>
