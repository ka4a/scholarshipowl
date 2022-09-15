@extends('admin.base')
@section('content')

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-money"></i>
					<span>{{ count($data) }} - accounts with double subscriptions</span>
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
							<th>ID</th>
							<th>Email</th>
							<th>Subscriptions count</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $row)
							<tr>
								<td>{{ $row['id'] }}</td>
                                <td>{{ $row['email'] }}</td>
                                <td>{{ $row['scount'] }}</td>
                                <td>
                                    <a href="/admin/accounts/view?id={{ $row['id'] }}"  class="btn btn-primary">View</a>
                                    <a href="{{ route('admin::accounts.subscriptions', $row['id']) }}"  class="btn btn-info">Subscriptions</a>
                                    @can('access-route', 'accounts.edit')
                                    <a href="/admin/accounts/edit?id={{ $row['id'] }}"  class="btn btn-warning">Edit</a>
                                    @endcan
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@endsection
