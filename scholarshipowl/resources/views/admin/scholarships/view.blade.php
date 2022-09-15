@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-7">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Information</p>
						@php
							$title = $scholarship->getTitle();
							if ($scholarship->getApplicationType() === \App\Entity\Scholarship::APPLICATION_TYPE_SUNRISE) {
								$title .= ' <span class="translucent">(sunrise)</span>';
							}
						@endphp
						<p><b>Title: </b>{!! $scholarship->getTitle() !!}</p>
						<p><b>Slug: </b>{{ $scholarship->getTitle() }}</p>
						<p><b>Scholarship ID: </b>{{ $scholarship->getScholarshipId() }}</p>
						@if($scholarship->getApplicationType() == $scholarship::APPLICATION_TYPE_SUNRISE)
						<p><b>Scholarship external ID: </b>{{ $scholarship->getExternalScholarshipId() }}</p>
						<p><b>Scholarship external template ID: </b>{{ $scholarship->getExternalScholarshipTemplateId() }}</p>
						@endif
                        <p><b>Start Date: </b>{{ substr($scholarship->getStartDate(), 0, 10) }}</p>
						<p><b>Deadline: </b>{{ substr($scholarship->getExpirationDate(), 0, 10) }}</p>
						<p><b>Awards: </b>{{ $scholarship->getAwards() }}</p>
						<p><b>URL: </b> <a href="{{ $scholarship->getUrl() }}" target="_blank">{{ $scholarship->getUrl() }}</a></p>
						<p><b>Apply URL: </b> <a href="{{ $scholarship->getApplyUrl() }}" target="_blank">{{ $scholarship->getApplyUrl() }}</a></p>
                        <p><b>Public URL: </b> <a href="{{ $scholarship->getPublicUrl() }}" target="_blank">{{ $scholarship->getPublicUrl() }}</a></p>
						<p><b>Is Active: </b>@if($scholarship->isActive()) {{ 'Yes' }} @else {{ 'No' }} @endif</p>
						<p><b>Created Date: </b>{{ format_date($scholarship->getCreatedDate(), false) }}</p>
						<p><b>Last Updated Date: </b>{{ format_date($scholarship->getLastUpdatedDate(), false) }}</p>
                        <p><b>Recurrent: </b>{{ $scholarshipEntity->getIsRecurrent()?"Yes":"No" }}</p>
                        @if($scholarshipEntity->getIsRecurrent())
                            <p><b>Recurrence Period: </b>{{ $scholarshipEntity->getRecurringValue()." ".$scholarshipEntity->getRecurringType()  }}</p>
                            @if($scholarshipEntity->getParentScholarship())
                            <p><b>Parent scholarship: </b> <a href="{{ route('admin::scholarships.view', ['id' => $scholarshipEntity->getParentScholarship()->getScholarshipId()]) }}">{{  route('admin::scholarships.view', ['id' => $scholarshipEntity->getParentScholarship()->getScholarshipId()]) }}</a></p>
                            @endif
                            @if($scholarshipEntity->getCurrentScholarship())
                            <p><b>Current scholarship: </b> <a href="{{ route('admin::scholarships.view', ['id' => $scholarshipEntity->getCurrentScholarship()->getScholarshipId()]) }}">{{  route('admin::scholarships.view', ['id' => $scholarshipEntity->getCurrentScholarship()->getScholarshipId()]) }}</a></p>
                            @endif
                        @endif
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Application Type: {{ ucwords($scholarship->getApplicationType()) }}</p>

						@if ($scholarship->getApplicationType() == "online")
							<pre>Method: {{ strtoupper($scholarship->getFormMethod()) }}</pre>
							<pre>Action: {{ $scholarship->getFormAction() }}</pre>
						@elseif ($scholarship->getApplicationType() == "email")
							<pre>To: {{ $scholarship->getEmail() }}</pre>
							<pre>Subject: {{ $scholarship->getEmailSubject() }}</pre>

							<br />
							<pre>{{ $scholarship->getEmailMessage() }}</pre>
						@endif

					</div>
				</div>

				@if ($scholarship->getApplicationType() == "online")
					<div class="box">
						<div class="box-content">
							<p class="page-header">Form Fields</p>

							<table class="table table-striped table-hover table-heading">
							<thead>
								<tr>
									<th>Form Field</th>
									<th>System Field</th>
									<th>Value</th>
								</tr>
							</thead>
							<tbody>
								@foreach ($scholarship->getForms() as $form)
									<tr>
										<td>{{ $form->getFormField() }}</td>
										<td>{{ $form->getSystemField() }}</td>
										<td>{{ $form->getValue() }}</td>
									</tr>
								@endforeach
							</tbody>
						</table>
						</div>
					</div>
				@endif

				<div class="box">
					<div class="box-content">
						<p class="page-header">Eligibility</p>

						<table class="table table-striped table-hover table-heading">
						<thead>
							<tr>
								<th>Field</th>
								<th>Type</th>
								<th>Value</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($scholarship->getEligibilities() as $eligibility)
								<tr>
									<td>
										{{ $fields[$eligibility->getField()->getFieldId()] }}
									</td>

									<td>
										{{ $eligibility_types[$eligibility->getType()] }}
									</td>

									<td>
										@if (array_key_exists($eligibility->getField()->getFieldId(), $multi_values))
											@if ( ($multi_values[$eligibility->getField()->getFieldId()] != "Gender") && ($multi_values[$eligibility->getField()->getFieldId()] != "GPA"))
												{{ getinfo($multi_values[$eligibility->getField()->getFieldId()], $eligibility->getValue()) }}
											@else
												{{ strtoupper($eligibility->getValue()) }}
											@endif
										@else
											{{ $eligibility->getValue() }}
										@endif
									</td>
								</tr>
							@endforeach
						</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-5">
		<div class="row">
			<div class="col-xs-12">
                <div class="box">
                    <div class="box-content">
                        <div class="page-header">Notes</div>
                        <p>{{ $scholarshipEntity->getNotes() }}</p>
                    </div>
                </div>
				<div class="box">
					<div class="box-content">
						<p class="page-header">Price</p>

						<p><b>Amount: </b>{{ $scholarship->getAmount() }}</p>
						<p><b>Up To: </b>{{ $scholarship->getUpTo() }}</p>
						<p><b>Is Free: </b>@if($scholarship->isFree()) {{ 'Yes' }} @else {{ 'No' }} @endif</p>
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Description</p>
						<p>{{ nl2br($scholarship->getDescription()) }}</p>
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Terms Of Service</p>
						<p><a href="{{ $scholarship->getTermsOfServiceUrl() }}" target="_blank">{{ $scholarship->getTermsOfServiceUrl() }}</a></p>
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Privacy Policy</p>
						<p><a href="{{ $scholarship->getPrivacyPolicyUrl() }}" target="_blank">{{ $scholarship->getPrivacyPolicyUrl() }}</a></p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
                @can('access-route', 'scholarships.edit')
				<a href="/admin/scholarships/save?id={{ $scholarship->getScholarshipId() }}"  class="btn btn-primary">Edit</a>
				<a href="/admin/scholarships/copy?id={{ $scholarship->getScholarshipId() }}" title="Copy Scholarship" class="btn btn-warning">Copy</a>
				<a href="#" data-delete-url="/admin/scholarships/delete?id={{ $scholarship->getScholarshipId() }}" data-delete-message="Delete Scholarship ?" title="Delete Scholarship" class="btn btn-danger DeleteScholarshipButton">Delete</a>
                @endcan
				<a href="/admin/scholarships/test?id={{ $scholarship->getScholarshipId() }}" title="Test Scholarship" class="btn btn-info">Test</a>
				<a href="/admin/scholarships/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>


@stop
