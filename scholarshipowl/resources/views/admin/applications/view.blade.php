@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Application Information</p>
						
						<p><b>Account ID: </b>{{ $application->getAccount()->getAccountId() }}</p>
						<p><b>First Name: </b>{{ $application->getAccount()->getProfile()->getFirstName() }}</p>
						<p><b>Last Name: </b>{{ $application->getAccount()->getProfile()->getLastName() }}</p>
						<hr />
						<p><b>Scholarship ID: </b>{{ $application->getScholarship()->getScholarshipId() }}</p>
						<p><b>Title: </b>{{ $application->getScholarship()->getTitle() }}</p>
						<hr />
						<p><b>Date Applied: </b>{{ format_date($application->getDateApplied()->format('Y-m-d'), false) }}</p>
						<p><b>Application Status: </b>{{ $application->getApplicationStatus() }}</p>
						<p><b>Application Type: </b>{{ ucfirst($application->getScholarship()->getApplicationType()) }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Submited Data</p>
						
						<pre>{{ highlight_string(preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "<JS>/* $1 */</JS>", $application->getSubmitedData())) }}</pre>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Comment</p>
						
						<pre>{{ highlight_string(preg_replace("/<script\b[^>]*>(.*?)<\/script>/is", "<JS>/* $1 */</JS>", $application->getComment())) }}</pre>
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
				<a href="/admin/applications/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>


@stop
