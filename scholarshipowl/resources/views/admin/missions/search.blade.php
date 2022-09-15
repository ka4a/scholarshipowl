@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-cubes"></i>
					<span>Results ({{ count($missions) }})</span>
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
							<th>Description</th>
							<th>Package</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($missions as $missionId => $mission)
							<tr>
								<td>{{ $mission->getName() }}</td>
								<td>{{ nl2br($mission->getDescription()) }}</td>
								
								<td>
									<table class="table table-bordered table-striped">
										<thead>
											<tr><th></th><th></th></tr>
										</thead>
										
										<body>
											<tr>
												<th>Name</th>
												<td>{{ $mission->getPackage()->getName() }}</td>
											</tr>
											<tr>
												<th>Price</th>
												<td>{{ $mission->getPackage()->getPrice() }}$</td>
											</tr>
											<tr>
												<th>Scholarships</th>
												<td>
													@if ($mission->getPackage()->isScholarshipsUnlimited())
														UNLIMITED
													@else
														{{ $mission->getPackage()->getScholarshipsCount() }}
													@endif
												</td>
											</tr>
										</body>
									</table>
								</td>
								
								<td>{{ format_date($mission->getStartDate()) }}</td>
								<td>{{ format_date($mission->getEndDate()) }}</td>
								
								<td>
                                    @can('access-route', 'missions.edit')
									<a href="/admin/missions/save?id={{ $missionId }}" class="btn btn-primary">Edit</a>
									
									@if ($mission->isActive())
										<a href="/admin/missions/deactivate?id={{ $missionId }}" class="btn btn-default">Deactivate</a>	
									@else 
										<a href="/admin/missions/activate?id={{ $missionId }}" class="btn btn-success">Activate</a>
									@endif
                                    @endcan
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


@stop
