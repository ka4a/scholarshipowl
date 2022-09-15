@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-suitcase"></i>
					<span>Results ({{ count($packages) }})</span>
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
                            <th>Id</th>
							<th>Name</th>
							<th>Price</th>
							<th>Freemium</th>
							<th>Scholarships</th>
                            <th>Free Trial</th>
							<th>Active</th>
							<th>Marked</th>
							<th>Priority</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($packages as $packageId => $package)
							<tr>
                                <td>{{ $packageId }}</td>
								<td>{{ $package->getName() }}</td>
								<td>${{ $package->getPrice() }}</td>
								<td>@if ($package->isFreemium()) YES @else NO @endif</td>
								<td>@if ($package->isScholarshipsUnlimited()) UNLIMITED @else {{ $package->getScholarshipsCount() }} @endif</td>
                                <td>@if ($package->isFreeTrial()) YES @else NO @endif</td>
								<td>@if ($package->isActive()) YES @else NO @endif</td>
								<td>@if ($package->isMarked()) YES @else NO @endif</td>
								<td>{{ $package->getPriority() }}</td>

								<td>
                                    @can('access-route', 'packages.edit')
									<a href="/admin/packages/save?id={{ $packageId }}" class="btn btn-primary">Edit</a>

									@if ($package->isActive())
										<a href="/admin/packages/deactivate?id={{ $packageId }}" class="btn btn-default">Deactivate</a>
									@else
										<a href="/admin/packages/activate?id={{ $packageId }}" class="btn btn-success">Activate</a>
									@endif

									@if ($package->isMobileActive())
										<a href="/admin/packages/deactivate_mobile?id={{ $packageId }}" class="btn btn-default">Mobile Deactivate</a>
									@else
										<a href="/admin/packages/activate_mobile?id={{ $packageId }}" class="btn btn-success">Mobile Activate</a>
									@endif

									@if ($package->isMarked())
										<a href="/admin/packages/unmark?id={{ $packageId }}" class="btn btn-default">Unmark</a>
									@else
										<a href="/admin/packages/mark?id={{ $packageId }}" class="btn btn-success">Mark</a>
									@endif

									@if ($package->isMobileMarked())
										<a href="/admin/packages/unmark_mobile?id={{ $packageId }}" class="btn btn-default">Mobile Unmark</a>
									@else
										<a href="/admin/packages/mark_mobile?id={{ $packageId }}" class="btn btn-success">Mobile Mark</a>
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
