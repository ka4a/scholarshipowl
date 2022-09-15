@extends("admin/base")
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-exchange"></i>
					<span>Results ({{ count($notifications) }})</span>
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
                            <th>Type</th>
                            <th>Active</th>
                            <th>Amount</th>
                            <th>Period</th>
                            <th>Delay</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($notifications as $notification)
                        <tr>
                            <td>{{ $notification->getType() }}</td>
                            <td>{{ $notification->isActive() ? 'Yes' : 'No' }}</td>
                            <td>{{ $notification->getCapAmount() ?: 'No limit' }}</td>
                            <td>{{ $notification->getCapValue() ? $notification->getCapValue() . ' '  . $notification->getCapType() : 'None' }}</td>
                            <td>{{ $notification->getDelayValue() ? $notification->getDelayValue() . ' '  . $notification->getDelayType() : 'None' }}</td>
                            <td>
                                <a class="btn btn-primary" href="{{ route('admin::notification.edit', ['app' => $notification->getApp(), 'type' => $notification->getType()->getId()])  }}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
