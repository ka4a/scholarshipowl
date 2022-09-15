@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-file-text"></i>
					<span>Login Events ({{ count($history) }})</span>
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
							<th>Action Taken</th>
                            <th>Feature Set</th>
                            <th>SRV</th>
							<th>IP Address</th>
                            <th>Agent</th>
							<th>Date Of Action</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($history as $item )
						<tr>
							<td>{{ $item->getAction() }}</td>
							<td>{{ $item->getFeatureSet() }}</td>
                            <td>{{ $item->getSrv() }}</td>
                            <td>{{ $item->getIpAddress() }} </td>
                            <td>{{ $item->getAgent() }} </td>
                            <td>{{ $item->getActionDate() }}</td>
						</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@stop
