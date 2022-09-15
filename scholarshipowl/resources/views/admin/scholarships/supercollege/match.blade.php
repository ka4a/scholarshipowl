@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Information</p>

                        <p><b>Eligible: </b>{{ $scholarship->getSuperCollegeScholarshipMatches()->count() }}</p>
						<p><b>Title: </b>{{ $scholarship->getTitle() }}</p>
						<p><b>Patron: </b>{{ $scholarship->getPatron() }}</p>
						<p><b>Amount: </b>{{ $scholarship->getAmount() }}</p>
						<p><b>Deadline: </b>{{ $scholarship->getDeadline() }}</p>
						<p><b>UUID: </b>{{ $scholarship->getUuid() }}</p>
						<p><b>URL: </b> <a href="{{ $scholarship->getUrl() }}" target="_blank">{{ $scholarship->getUrl() }}</a></p>
					</div>
				</div>
			</div>

			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">How To Apply</p>
						<p>{{ $scholarship->getHowToApply() }}</p>
					</div>
				</div>

				<div class="box">
					<div class="box-content">
						<p class="page-header">Eligibility</p>
						<p>{{ $scholarship->getEligibility() }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-sm-4">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Location</p>

						<p><b>City: </b>{{ $scholarship->getCity() }}</p>
						<p><b>Zip: </b>{{ $scholarship->getZip() }}</p>
						<p><b>State: </b>{{ $scholarship->getState() }}</p>

						@if (!empty($scholarship->getAddress1()))
						<p><b>Address 1: </b>{{ $scholarship->getAddress1() }}</p>
						@endif

						@if (!empty($scholarship->getAddress2()))
						<p><b>Address 2: </b>{{ $scholarship->getAddress2() }}</p>
						@endif

						@if (!empty($scholarship->getAddress3()))
						<p><b>Address 3: </b>{{ $scholarship->getAddress3() }}</p>
						@endif
					</div>
				</div>
			</div>

			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Other</p>

						<p><b>Min. Level: </b>{{ $scholarship->getLevelMin() }}</p>
						<p><b>Max. Level: </b>{{ $scholarship->getLevelMax() }}</p>
						<p><b>Awards: </b>{{ $scholarship->getAwards() }}</p>
						<p><b>Renew: </b>{{ $scholarship->getRenew() }}</p>
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
				<a href="/admin/scholarships/super-college-eligibility" class="btn btn-default">Back To List</a>
			</p>
		</div>
	</div>
</div>


@stop
