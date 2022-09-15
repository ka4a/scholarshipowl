@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Email Information</p>
						
						<p><b>Subject: </b>{{ $email->getSubject() }}</p>
						<p><b>Folder: </b>{{ ucfirst($email->getFolder()) }}</p>
						<p><b>Date: </b>{{ $email->getDate()->format('Y-m-d') }}</p>
						<hr />
						
						<p><b>Sender: </b>{{ $email->getSender() }}</p>
						<p><b>Recipient: </b>{{ $email->getRecipient() }}</p>
						<hr />
						
						@if (isset($scholarship))
							<p><b>Scholarship Title: </b>{{ $scholarship->getTitle() }}</p>
							<p><b>Scholarship Id: </b>{{ $scholarship->getScholarshipId() }}</p>
							<p>
							<a href="/admin/scholarships/view?id={{ $scholarship->getScholarshipId() }}" target="_blank">
								{{ $scholarship->getTitle() }}
							</a>
							</p>
							<hr />
						@endif

						<p>{{ ($email->getClearBody()) }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/accounts/mailbox/folders/{{ $account->getAccountId() }}" class="btn btn-danger">Mailbox Folders</a>
			</p>
		</div>
	</div>
</div>


@stop
