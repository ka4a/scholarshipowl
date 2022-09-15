@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>Save Status</span>
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
				<form method="post" action="/admin/transactions/post-change-status" class="form-horizontal ajax_form">
					{!! Form::token() !!}
					{!! Form::hidden('transaction_id', $transaction->getTransactionId()) !!}
					
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Status: </label>
							<div class="col-sm-6">
								{!! Form::select('transaction_status_id', $statuses, $transaction->getTransactionStatus()->getId(), array("class" => "populate placeholder select2")) !!}
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Full Name:  </label>
							<div class="col-sm-6"><input type="text" disabled="disabled" class="form-control" value="{{ $transaction->getAccount()->getProfile()->getFullName() }}" /></div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Phone:  </label>
							<div class="col-sm-6"><input type="text" disabled="disabled" class="form-control" value="{{ $transaction->getAccount()->getProfile()->getPhone() }}" /></div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Amount:  </label>
							<div class="col-sm-6"><input type="text" disabled="disabled" class="form-control" value="{{ $transaction->getAmount() }}" /></div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Date:  </label>
							<div class="col-sm-6"><input type="text" disabled="disabled" class="form-control" value="{{ $transaction->getCreatedDate()->format('Y-m-d h:m:s') }}" /></div>
						</div>
					</fieldset>
					
					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">Save Status</a>
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
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/transactions/view?id={{ $transaction->getTransactionId() }}" class="btn btn-primary">View Transaction</a>
				<a href="/admin/transactions/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>
	
@stop
