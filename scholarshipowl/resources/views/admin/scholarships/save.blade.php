@extends("admin/base")
@section("content")


<div style="display: none">
	<div id="EligibilityTypesOptions">
		@foreach ($options["eligibility_types"] as $value => $text)
			<option value="{{ $value }}" data-multiple="@if(in_array($value, ['nin', 'in', 'between'])) true @endif">{{ $text }}</option>
		@endforeach
	</div>

	<div class="hidden" id="Fields">
		@foreach ($fields as $fieldId => $fieldName)
			<option value='{{ $fieldId }}'>{{ $fieldName }}</option>
		@endforeach
	</div>

	<div id="StaticDataFields">
		@foreach ($fields as $value => $text)
			<option value="{{ $value }}" data-multiple="@if (array_key_exists($value, $options['multi_values'])){{$options['multi_values'][$value]}}@else{{''}}@endif">{{ $text }}
			</option>
		@endforeach
	</div>

	<div id="StaticDataGender">
		@foreach ($options["gender"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

	<div id="StaticDataCitizenship">
		@foreach ($options["citizenship"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

	<div id="StaticDataEthnicity">
		@foreach ($options["ethnicity"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

    <div id="StaticDataCountry">
        @foreach ($options["country"] as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </div>

	<div id="StaticDataStudyCountry">
		@foreach ($options["country"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

	<div id="StaticDataState">
		@foreach ($options["state"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

	<div id="StaticDataSchoolLevel">
		@foreach ($options["schoollevel"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

	<div id="StaticDataDegree">
		@foreach ($options["degree"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

	<div id="StaticDataDegreeType">
		@foreach ($options["degreetype"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

	<div id="StaticDataGPA">
		@foreach ($options["gpa"] as $value => $text)
			<option value="{{ $value }}">{{ $text }}</option>
		@endforeach
	</div>

    <div id="StaticDataMilitaryAffiliation">
        @foreach ($options["militaryaffiliation"] as $value => $text)
            <option value="{{ $value }}">{{ $text }}</option>
        @endforeach
    </div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Scholarship</span>
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
						<li><a href="#tab-information">Information</a></li>
                        @if ($scholarship->getScholarshipId())
						<li><a href="#tab-application">Application</a></li>
						<li><a href="#tab-requirements">Requirements</a></li>
						<li><a href="#tab-eligibility">Eligibility</a></li>
                        <li><a href="#tab-metatags">Metatags (CMS)</a></li>
                        @endif
					</ul>

					<div id="tab-information">
						@include ("admin/scholarships/save_information")
					</div>

                    @if ($scholarship->getScholarshipId())
					<div id="tab-application">
						@include ("admin/scholarships/save_application")
					</div>

					<div id="tab-requirements">
						@include ("admin/scholarships/save_requirements")
					</div>

					<div id="tab-eligibility">
						@include ("admin/scholarships/save_eligibility")
					</div>

                    <div id="tab-metatags">
                        @include('admin.scholarships.save_metatags')
                    </div>
                    @endif
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				@if ($scholarship->getScholarshipId() > 0)
					<a href="/admin/scholarships/view?id={{ $scholarship->getScholarshipId() }}"  class="btn btn-primary">View</a>
					<a href="/admin/scholarships/copy?id={{ $scholarship->getScholarshipId() }}" title="Copy Scholarship" class="btn btn-warning">Copy</a>
					<a href="/admin/scholarships/test?id={{ $scholarship->getScholarshipId() }}" title="Test Scholarship" class="btn btn-info">Test</a>
					<a href="#" data-delete-url="/admin/scholarships/delete?id={{ $scholarship->getScholarshipId() }}" data-delete-message="Delete Scholarship ?" title="Delete Scholarship" class="btn btn-danger DeleteScholarshipButton">Delete</a>
                    @if($scholarship->getIsRecurrent())
                        <a href="{{ route('admin::scholarships.recur', $scholarship->getScholarshipId()) }}" data-confirm-message="Are you sure want force recurr the scholarship?!" class="btn btn-danger">Force Recur</a>
                    @endif
				@endif

				<a href="/admin/scholarships/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@include ("admin/scholarships/online_data_modal")
@stop
