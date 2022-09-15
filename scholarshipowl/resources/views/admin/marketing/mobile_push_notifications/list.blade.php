@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-exchange"></i>
					<span>Results ({{ count($mobilePushNotifications) }})</span>
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
							<th>Notification name</th>
							<th>Event</th>
							<th>Active</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($mobilePushNotifications as $notification)
							<tr>
								<td>
								    {{ $notification->getPushNotificationId() }}
								</td>
								<td>
									{{ $notification->getNotificationName() }}
								</td>
								<td>
									{{ $notification->getEventName() }}
								</td>
								<td>
									{{ $notification->isActive()?"Yes":"No" }}
								</td>
								<td>
									@if ($notification->isActive())
										<a href="/admin/marketing/mobile_push_notifications/status-switch/{{ $notification->getPushNotificationId() }}/deactivate" class="btn btn-default">Deactivate</a>
									@else
										<a href="/admin/marketing/mobile_push_notifications/status-switch/{{ $notification->getPushNotificationId() }}/activate" class="btn btn-success">Activate</a>
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


@stop
