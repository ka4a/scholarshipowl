@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/applications?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search-plus"></i>
					<span>Filter Search</span>
				</div>

				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>

				<div class="no-move"></div>
			</div>

			<div class="box-content" style="display: none;">
				<form method="get" action="/admin/applications/search" class="form-horizontal">
					<fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Account ID</label>

                            <div class="col-sm-3">
                                {{ Form::text('account_id', $search['account_id'], array("class" => "form-control")) }}
                            </div>
                        </div>

                        <div class="form-group">
							<label class="col-sm-3 control-label">First Name</label>

							<div class="col-sm-3">
								{{ Form::text('first_name', $search['first_name'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Last Name</label>

							<div class="col-sm-3">
								{{ Form::text('last_name', $search['last_name'], array("class" => "form-control")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Scholarship Id</label>

							<div class="col-sm-3">
								{{ Form::text('scholarship_id', $search['scholarship_id'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Scholarship Title</label>

							<div class="col-sm-3">
								{{ Form::text('title', $search['title'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Expires From</label>

							<div class="col-sm-3">
								{{ Form::text('expiration_date_from', $search['expiration_date_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Expires To</label>

							<div class="col-sm-3">
								{{ Form::text('expiration_date_to', $search['expiration_date_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Applied From</label>

							<div class="col-sm-3">
								{{ Form::text('date_applied_from', $search['date_applied_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Applied To</label>

							<div class="col-sm-3">
								{{ Form::text('date_applied_to', $search['date_applied_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Application Status</label>
							<div class="col-sm-3">
								{{ Form::select('application_status_id[]', $options['application_statuses'], $search['application_status_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Application Type</label>
							<div class="col-sm-3">
								{{ Form::select('application_type[]', $options['application_types'], $search['application_type'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary" type="submit">Search</button>
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
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-external-link-square"></i>
					<span>Results ({{ $count }})</span>
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
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
							<th>Scholarship</th>
							<th>Status</th>
							<th>Type</th>
							<th>Date Applied</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($applications as $application)
							<tr>
								<td><a href="/admin/accounts/view?id={{ $application->getAccount()->getAccountId() }}" target="_blank">{{ $application->getAccount()->getProfile()->getFullName() }}</a></td>
                                <td>{{ $application->getAccount()->getEmail() }}</td>
                                <td>{{ $application->getAccount()->getProfile()->getPhone() }}</td>
								<td><a href="/admin/scholarships/view?id={{ $application->getScholarship()->getScholarshipId() }}" target="_blank">{{ $application->getScholarship()->getTitle() }}</a></td>
								<td>{{ $application->getApplicationStatus() }}</td>
								<td>{{ ucfirst($application->getScholarship()->getApplicationType()) }}</td>
								<td>{{ $application->getDateApplied()->format('Y-m-d h:m:s') }}</td>
								<td>
									<a class="btn btn-primary" href="/admin/applications/view?account_id={{ $application->getAccount()->getAccountId() }}&scholarship_id={{ $application->getScholarship()->getScholarshipId() }}">View</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/applications?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>


@stop
