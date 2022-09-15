@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-university"></i>
					<span>Results ({{ count($applications) }})</span>
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
							<th>Scholarship</th>
							<th>Status</th>
							<th>Date Applied</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($applications as $application)
							<tr>
								<td>
									<a href="/admin/scholarships/view?id={{ $application->getScholarship()->getScholarshipId() }}" target="_blank">
										{{ $application->getScholarship()->getTitle() }}
									</a>
								</td>
								<td>{{ $application->getApplicationStatus() }}</td>
								<td>{{ !is_null($application->getDateApplied()) ? format_date($application->getDateApplied()->format('Y-m-d h-m-s'), false) : ''}}</\td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/accounts/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
