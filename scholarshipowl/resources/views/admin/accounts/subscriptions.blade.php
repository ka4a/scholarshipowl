@extends("admin/base")
@section("content")

<header>
	<div id="packages" class="modal fade">
		<div class="modal-dialog modal-sm">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">Add Another Package</h4>
				</div>
				<div class="modal-body">
					@foreach($packages as $package)
					<a href="#" class="btn btn-info btn-block AddPackageButton" data-package-id="{{$package->getPackageId() }}" data-account-id="{{ $accountId }}" title="{{ $package->getName() }}">Add Package: {{ $package->getName() }}</a>
					@endforeach
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
			</div><!-- /.modal-content -->
		</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</header>


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<input type="hidden" name="_token" value="{{ csrf_token() }}" />

			<p>
				<a class="btn btn-primary" id="AddSubscription" data-toggle="modal" data-target="#packages" href="#">Add Package</a>
			</p>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-star"></i>
					<span>Results ({{ count($subscriptions) }})</span>
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
							<th>Status</th>
							<th>Subscription</th>
							<th>Package</th>
							<th>Transactions</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($subscriptions as $subscriptionId => $subscription)
                            {{ Form::open() }}
							@php
								$subscriptionId = $subscription->getSubscriptionId()
							@endphp
                            {{ Form::hidden('subscription_id', $subscriptionId) }}
							<tr class="subscription-info">
								<td>
                                    <div>{{ $subscription->getSubscriptionStatus() }}</div>
                                    <small>({{ $subscription->getRemoteStatus() }})</small>
                                </td>

								<td>
									<table class="table table-bordered table-striped">
										<thead>
											<tr><th></th><th></th></tr>
										</thead>

										<tbody>
                                            <tr>
                                                <th>Subscription Id</th>
                                                <td>{{ $subscription->getSubscriptionId() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Source</th>
                                                <td>{{ $subscription->getSource() }}</td>
                                            </tr>
                                            <tr>
                                                <th>Payment Method</th>
												<td>{{  !is_null($subscription->getPaymentMethod()) ?  \App\Entity\PaymentMethod::options()[$subscription->getPaymentMethod()->getId()] ?? $subscription->getPaymentMethod()->getId() : '' }}</td>
                                            </tr>
                                            <tr>
                                                <th>External Id</th>
                                                <td>{{ $subscription->getExternalId() }}</td>
                                            </tr>
                                            @if ($subscription->getRecurrentCount())
                                                <tr>
                                                    <th>Recurrent Count</th>
                                                    <td>{{ $subscription->getRecurrentCount() }}</td>
                                                </tr>
                                            @endif
											<tr>
												<th>Start Date</th>
												<td>{{ format_date($subscription->getStartDate()->format('Y-m-d'), false) }}</td>
											</tr>
                                            <tr>
                                                <th>Renewal Date</th>
                                                <td>{{ format_date($subscription->getRenewalDate()->format('Y-m-d'), false) }}</td>
                                            </tr>
                                            <tr>
                                                <th>Active until</th>
                                                <td>{{ Form::text('active_until', !is_null($subscription->getActiveUntil()) ? format_date($subscription->getActiveUntil()->format('Y-m-d'), false) : '', ['class' => 'form-control date_picker', 'disabled' => true]) }}</td>
                                            </tr>
											<tr>
                                                <th>Terminated at</th>
												<td>{{ !is_null($subscription->getTerminatedAt()) ? format_date($subscription->getTerminatedAt()->format('Y-m-d'), false) : '' }}</td>
											</tr>
                                            <tr>
                                                <th>Is free trial</th>
                                                <td>{{ options()->yesNo()[$subscription->isFreeTrial()] }}</td>
                                            </tr>
                                            <tr>
                                                <th>Free trial end date</th>
                                                <td>{{ !is_null($subscription->getFreeTrialEndDate()) ? format_date($subscription->getFreeTrialEndDate()->format('Y-m-d'), false) : '' }}</td>
                                            </tr>
											<tr>
												<th>End Date</th>

												<td>{{ !is_null($subscription->getEndDate()) &&  $subscription->getEndDate()->format('Y-m-d') != '-0001-11-30' ? format_date($subscription->getEndDate()->format('Y-m-d'), false) : ''}}</td>
											</tr>
                                            <tr>
                                                <th>R. Status Update</th>
                                                <td>{{ !is_null($subscription->getRemoteStatusUpdatedAt()) ? format_date($subscription->getRemoteStatusUpdatedAt()->format('Y-m-d H:m:s'), false)  : '' }}</td>
                                            </tr>
											<tr>
												<th>Acquired Type</th>
                                                <td>{{ Form::select('subscription_acquired_type', \App\Entity\SubscriptionAcquiredType::options(), $subscription->getSubscriptionAcquiredType()->getId(), ['class' => 'form-control', 'disabled' => true]) }}</td>
											</tr>
											<tr>
												<th>Exp. Type</th>
												<td>{{ ucwords(str_replace("_", " ", $subscription->getExpirationType())) }}</td>
											</tr>

											@if ($subscription->getExpirationType() == 'recurrent')
												<tr>
													<th>Exp. Period Type</th>
													<td>{{ ucfirst($subscription->getExpirationPeriodType()) }}</td>
												</tr>
												<tr>
													<th>Exp. Period Value</th>
													<td>
														@if ($subscription->getExpirationPeriodValue() == 9999)
															UNLIMITED
														@else
															{{ $subscription->getExpirationPeriodValue() }}
														@endif
													</td>
												</tr>
											@endif
										</tbody>
									</table>
								</td>

								<td>
									<table class="table table-bordered table-striped">
										<thead>
											<tr><th></th><th></th></tr>
										</thead>
										<tbody>
                                            <tr>
                                                <th>Package Id</th>
                                                <td>{{ $subscription->getPackage()->getPackageId() }}</td>
                                            </tr>
											<tr>
												<th>Name</th>
												<td>{{ $subscription->getName() }}</td>
											</tr>
											<tr>
												<th>Price</th>
												<td>{{ $subscription->getPrice() }}</td>
											</tr>
											<tr>
												<th>Scholarships</th>
												<td>@if ($subscription->getIsScholarshipsUnlimited()) UNLIMITED @else {{ $subscription->getScholarshipsCount() }} @endif</td>
											</tr>
											<tr>
												<th>Priority</th>
												<td>{{ $subscription->getPriority() }}</td>
											</tr>
										</tbody>
									</table>
								</td>

								<td>
                                    @foreach($subscription->getTransactions() as $transaction)
                                        <table class="table table-bordered table-striped">
                                            <thead>
                                                <tr><th></th><th></th></tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <th>Transaction Id</th>
                                                    <td>{{ $transaction->getTransactionId() }}</td>
                                                </tr>
                                                @if ($transaction->getRecurrentNumber())
                                                    <tr>
                                                        <th>Payment #</th>
                                                        <td>{{ $transaction->getRecurrentNumber() }}</td>
                                                    </tr>
                                                @endif
                                                <tr>
                                                    <th>Method</th>
                                                    <td>{{ $transaction->getPaymentMethod() }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Amount</th>
                                                    <td>{{ $transaction->getAmount() }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Date</th>
                                                    <td>{{ !is_null($transaction->getCreatedDate()) ? $transaction->getCreatedDate()->format('Y-m-d') : ''  }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Provider Transaction Id</th>
                                                    <td>{{ $transaction->getProviderTransactionId() }}</td>
                                                </tr>
                                                <tr>
                                                    <th>Bank Transaction Id</th>
                                                    <td>{{ $transaction->getBankTransactionId() }}</td>
                                                </tr>
                                            </tbody>
                                        </table>
									@endforeach
								</td>

								<td>
									@if ($subscription->isActive())
										<a href="{{ route('admin::accounts.cancelSubscription', $subscriptionId) }}" class="btn btn-danger btn-cancel-subscription">Cancel</a>
									@endif
                                    <a class="btn btn-warning btn-edit" href="#">Edit</a>
                                    <button class="btn btn-success btn-save" style="display: none">Save</button>
								</td>
							</tr>
                            {{ Form::close() }}
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/accounts/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
