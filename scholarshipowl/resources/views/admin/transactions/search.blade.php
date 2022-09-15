@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<p class="pull-left">Results ({{ $count }}/${{ $amount }})</p>
		<p align="right">Export ({{ $count }}): <a href="/admin/export/transactions?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-search-plus"></i>
					<span>Filter Search</span>
				</div>

				<div class="box-icons">
					<a class="collapse-link">
						<i class="fa fa-chevron-down"></i>
					</a>
					<a class="expand-link">
						<i class="fa fa-expand"></i>
					</a>
				</div>

				<div class="no-move"></div>
			</div>

			<div class="box-content" style="display: none;">
				<form method="get" action="/admin/transactions/search" class="form-horizontal">
					<fieldset>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Domain</label>
                            <div class="col-sm-6">
                                {!! Form::select('domain', $options['domains'], $search['domain'], ["class" => "form-control"]) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">First name</label>
                            <div class="col-sm-3">
                                {!! Form::text('first_name', $search['first_name'], array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Last name</label>
                            <div class="col-sm-3">
                                {!! Form::text('last_name', $search['last_name'], array('class' => 'form-control')) !!}
                            </div>
                        </div>

                        <hr/>

                        <div class="form-group">
							<label class="col-sm-3 control-label">Created From</label>

							<div class="col-sm-3">
								{!! Form::text('created_date_from', $search['created_date_from'], array("class" => "form-control date_picker")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Created To</label>

							<div class="col-sm-3">
								{!! Form::text('created_date_to', $search['created_date_to'], array("class" => "form-control date_picker")) !!}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">First billing date from</label>
                            <div class="col-sm-3">
                                {!! Form::text('subscription_start_date_from', $search['subscription_start_date_from'], array('class' => 'form-control date_picker')) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">First billing date to</label>
                            <div class="col-sm-3">
                                {!! Form::text('subscription_start_date_to', $search['subscription_start_date_to'], array('class' => 'form-control date_picker')) !!}
                            </div>
                        </div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Amount Min.</label>

							<div class="col-sm-3">
								{!! Form::text('amount_min', $search['amount_min'], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Amount Max.</label>

							<div class="col-sm-3">
								{!! Form::text('amount_max', $search['amount_max'], array("class" => "form-control")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Transaction Status</label>
							<div class="col-sm-3">
								{!! Form::select('transaction_status_id[]', $options['transaction_statuses'], $search['transaction_status_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Expiration Type</label>
                            <div class="col-sm-3">
                                {!! Form::select('subscription_expiration_type[]', $options['subscription_expiration_types'], $search['subscription_expiration_type'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
                            </div>
                        </div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Payment Processor</label>
							<div class="col-sm-3">
								{!! Form::select('payment_method_id[]', $options['payment_methods'], $search['payment_method_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Payment Method</label>
                            <div class="col-sm-3">
                                {!! Form::select('payment_type_id[]', $options['payment_types'], $search['payment_type_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
                            </div>
                        </div>

                        <!--
						<div class="form-group">
							<label class="col-sm-3 control-label">Credit Card Type</label>
							<div class="col-sm-3">
								{!! Form::select('credit_card_type[]', $options['credit_card_types'], $search['credit_card_type'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
							</div>
						</div>
						-->

						<div class="form-group">
							<label class="col-sm-3 control-label">Device Type</label>
							<div class="col-sm-3">
								{!! Form::select('device[]', $options['devices'], $search['device'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
							</div>
						</div>

						<hr/>

						<div class="form-group">
							<label class="col-sm-3 control-label">Payment number</label>

							<div class="col-sm-3">
                                <div class="row">
                                    <div class="col-xs-12 input-container input-icon">
                                        <i>=</i>{!! Form::text('recurrent_number', $search['recurrent_number'], ["class" => "form-control"]) !!}
                                    </div>
                                </div>
                                <div class="row"><div class="col-xs-12 text-center"><b>OR</b></div></div>
                                <div class="row">
                                    <div class="col-xs-12 input-container input-icon">
                                        <i>&gt;</i>{!! Form::text('recurrent_number_gt', $search['recurrent_number_gt'], ["class" => "form-control"]) !!}
                                    </div>
                                </div>
                                <div class="row"><div class="col-xs-12 text-center"><b>OR</b></div></div>
                                <div class="row">
                                    <div class="col-xs-12 input-container input-icon">
                                        <i>&lt;</i>{!! Form::text('recurrent_number_lt', $search['recurrent_number_lt'], ["class" => "form-control"]) !!}
                                    </div>
                                </div>
							</div>
                            <div class="col-sm-3">
                                <p>
                                    Set to "<strong>NULL</strong>" to get not recurrent transactions.
                                </p>
                            </div>
						</div>

                        <hr/>

						<div class="form-group">
							<label class="col-sm-3 control-label">Provider Transaction ID</label>

							<div class="col-sm-3">
								{!! Form::text('provider_transaction_id', $search['provider_transaction_id'], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Bank Transaction ID</label>

							<div class="col-sm-3">
								{!! Form::text('bank_transaction_id', $search['bank_transaction_id'], array("class" => "form-control")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Affiliate ID</label>

							<div class="col-sm-3">
								{!! Form::text('affiliate_id', $search['affiliate_id'], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Expiration Type</label>

							<div class="col-sm-3">
                                {!! Form::select('expiration_type[]', $options['expiration_types'], $search['expiration_type'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Package</label>

							<div class="col-sm-3">
                                {!! Form::select('package[]', $options['packages'], $search['package'], array("class" => "populate placeholder select2", "multiple" => "multiple")) !!}
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Free trial</label>

                            <div class="col-sm-3">
                                {!! Form::select('package_free_trial', $options['package_free_trial'], $search['package_free_trial'], ['class' => 'form-control']) !!}
                            </div>
                        </div>

                        <div class="form-group">
							<label class="col-sm-3 control-label">Account Id</label>

							<div class="col-sm-3">
                                {!! Form::text('account_id', $search['account_id'], array("class" => "form-control")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Phone Number</label>

							<div class="col-sm-3">
                                {!! Form::text('phone', $search['phone'], array("class" => "form-control")) !!}
							</div>
						</div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button class="btn btn-primary" type="submit">Search</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-money"></i>
					<span>Results ({{ $count }})</span>
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
							<th>Full Name</th>
							<th>Amount</th>
							<th>Status</th>
							<th>Date</th>
                            <th>First billing date</th>
                            <th>Expiration Type</th>
							<th>Payment Processor</th>
							<th>Payment Method</th>
							<!--<th>Credit Card</th>-->
							<th>Device</th>
							<th>HasOffers Affiliate ID</th>
							<th>HasOffers Transaction ID</th>
							<th>Package Name</th>
                            <th>Free trial</th>
                            <th>Payment Number</th>
                            <th>Consent to be Called</th>
                            <th>Phone Number</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($transactions as $transaction)
							<tr>
								<td><a href="{{ route('admin::accounts.view', ['id' => $transaction->getAccount()->getAccountId()]) }}"
									   target="_blank">{{ $transaction->getAccount()->getProfile()->getFirstName() }}
										{{ $transaction->getAccount()->getProfile()->getLastName() }}
									</a><br/>
                                    ({{ $transaction->getAccount()->getDomain() }})
								</td>
								<td>{{ $transaction->getAmount() }}</td>
								<td>{{ $transaction->getTransactionStatus()->getName() }}</td>
								<td>{{ $transaction->getCreatedDate() }}</td>
                                <td>{{ $transaction->getSubscription()->getStartDate()  }}</td>
                                <td>{{ $transaction->getSubscription()->getExpirationType()  }}</td>
								<td>{{ $transaction->getPaymentMethod()->getName() }}</td>
                                <td>{{ $transaction->getPaymentType()->getName() }}</td>
								<!--<td>{{ $transaction->getCreditCardType() }}</td>-->
								<td>{{ ucfirst($transaction->getDevice()) }}</td>
								<td>{{ $transaction->has_offers_affiliate_id }}</td>
								<td>{{ $transaction->has_offers_transaction_id}}</td>
								<td>{{ $transaction->getSubscription()->getName() }}</td>
                                <td>{{ yesno($transaction->getSubscription()->getPackage()->isFreeTrial()) }}</td>
                                <td>{{ $transaction->getRecurrentNumber() }}</td>
                                <td>{{ $transaction->getAccount()->getProfile()->getAgreeCall()?"Yes":"No" }}</td>
                                <td>{{ $transaction->getAccount()->getProfile()->getPhone() }}</td>
								<td>
									<a class="btn btn-primary" href=" {{ route('admin::transactions.view', ['id' => $transaction->getTransactionId()])  }}">View</a>
                                    @can('access-route', 'transactions.edit')
									<a class="btn btn-danger" href="{{ route('admin::transactions.changeStatus', ['id' => $transaction->getTransactionId()])  }}">Status</a>
                                    @endcan
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/transactions?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>

@stop
