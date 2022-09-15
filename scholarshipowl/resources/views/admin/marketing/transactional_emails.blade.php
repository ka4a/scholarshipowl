@extends("admin/base")
@section("content")

<div class="box">
    <div class="box-header">
        <div class="box-name">
            Actions
        </div>
    </div>
    <div class="box-content">
        <a href="{{ route('admin::marketing.transactional_email.testTransactionalEmail') }}" class="btn btn-primary">
            Test Email
        </a>
    </div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-exchange"></i>
					<span>Results ({{ count($transactionalEmails) }})</span>
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
							<th>Event Name</th>
							<th>Template Slug</th>
							<th>Max. Amount</th>
							<th>Period</th>
							<th>Active</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($transactionalEmails as $transactionalEmail)
							<tr>
								<td>
								    {{ $transactionalEmail->getTransactionalEmailId() }}
								</td>
								<td>
									{{ $transactionalEmail->getEventName() }}
								</td>
								<td>
									{{ $transactionalEmail->getTemplateName() }}
								</td>
								<td>
									{{ $transactionalEmail->getSendingCap() }}
								</td>
								<td>
									{{ $transactionalEmail->getCapPeriod() }}
								</td>
								<td>
									{{ $transactionalEmail->isActive()?"Yes":"No" }}
								</td>
								<td>
								    <a href="/admin/marketing/transactional_email/save/{{ $transactionalEmail->getTransactionalEmailId() }}" title="Edit Transactional Email" class="btn btn-primary">Edit</a>
                                    <a 	href="#"
                                        data-delete-url="/admin/marketing/transactional_email/delete/{{ $transactionalEmail->getTransactionalEmailId() }}"
                                        data-delete-message="Delete Transactional Email?"
                                        title="Delete Transactional Email"
                                        class="btn btn-warning DeleteTransactionalEmailButton">
                                        Delete
                                    </a>
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
