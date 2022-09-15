<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-content">
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>Subject</th>
							<th>Sender</th>
							<th>Recipient</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>	
						@foreach ($emails as $email)
							<tr>
								<td>
									@if (!$email->getIsRead())
										<b>{{ $email->getSubject() }}</b>
									@else
										{{ $email->getSubject() }}
									@endif
								</td>

								<td>{{ ($email->getSender()) }}</td>
								<td>{{ ($email->getRecipient()) }}</td>
								<td>{{ $email->getDate()->format('Y-d-m') }}</td>
								<td>
									<a target="_blank" href="/admin/accounts/mailbox/email/{{$email->getEmailId()}}?mailbox={{$email->getMailbox()}}" class="btn btn-primary">View</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
