@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-sm-4">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Transaction Information</p>
						
						<p><b>Amount: </b>{{ $transaction->getAmount() }}</p>
						<p><b>Status: </b>{{ $transaction->getTransactionStatus()->getName() }}</p>
						<p><b>Failed Reason: </b>{{ $transaction->getFailedReason() }}</p>
						<p><b>Date: </b>{{ $transaction->getCreatedDate() }}</p>
					</div>
				</div>
			</div>
			
			<div class="col-sm-4">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-content">
							<p class="page-header">Payment Information</p>
                            @if ($transaction->getRecurrentNumber())
                                <p><b>Payment #: </b>{{ $transaction->getRecurrentNumber() }}</p>
                            @endif
							<p><b>Payment Method: </b>{{ $transaction->getPaymentMethod()->getName() }}</p>
							<p><b>Credit Card Type: </b>{{ $transaction->getCreditCardType() }}</p>
							<p><b>Provider Transaction ID: </b>{{ $transaction->getProviderTransactionId() }}</p>
							<p><b>Bank Transaction ID: </b>{{ $transaction->getBankTransactionId() }}</p>
						</div>
					</div>
				</div>
			</div>
			
			<div class="col-sm-4">
				<div class="col-xs-12">
					<div class="box">
						<div class="box-content">
							<p class="page-header">Account Information</p>
								
							<p><b>First Name: </b>{{ $transaction->getAccount()->getProfile()->getFirstName() }}</p>
							<p><b>Last Name: </b>{{ $transaction->getAccount()->getProfile()->getLastName() }}</p>
							<p><b>Phone: </b>{{ $transaction->getAccount()->getProfile()->getPhone() }}</p>
							<p><b>Device: </b>{{ ucfirst($transaction->getDevice()) }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-12">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Response Data</p>
						@if (json_decode($transaction->getResponseData(), true))
							@foreach (json_decode($transaction->getResponseData(), true) as $key => $value)
								<p><b>{{ $key }}: </b>{{ $value }}</p>
							@endforeach
						@else
							<p><b>****</b></p>
						@endif
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
                @can('access-route', 'transactions.edit')
				<a href="/admin/transactions/change-status?id={{ $transaction->getTransactionId() }}" class="btn btn-danger">Change Status</a>
                @endcan
				<a href="/admin/accounts/view?id={{ $transaction->getAccount()->getAccountId() }}" class="btn btn-success" target="_blank">View Account</a>
				<a href="/admin/transactions/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>


@stop
