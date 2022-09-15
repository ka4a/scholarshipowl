@extends("admin/base")
@section("content")

@if (!empty($flash))
	<input type="hidden" name="flash" class="NotificableElement" data-notification-message="{{ $flash['data'] }}" data-notification-type="{{ $flash['type'] }}" />
@endif


<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-file-excel-o"></i>
					<span>Batch Subscription</span>
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
				<form method="post" action="/admin/packages/post-batch-subscription" class="form-horizontal" enctype="multipart/form-data">
					{{ Form::token() }}

					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Package</label>
							<div class="col-sm-3">
								{{ Form::select('package_id', $options["packages"], "0", array("class" => "populate placeholder select2")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">CSV File</label>
							<div class="col-sm-3">
								<input type="file" name="file" class="form-control" />
								<p>Must contain Account ID per line</p>
							</div>
						</div>

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Action</label>
                            <div class="col-sm-3">
                                <label class="col-sm-6">
                                    Add
                                    {{ Form::radio("remove", "no", true) }}
                                </label>
                                <label class="col-sm-6">
                                    Remove
                                    {{ Form::radio("remove", "yes", false) }}
                                </label>
                            </div>
                        </div>
					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<button type="submit" href="#" class="btn btn-primary">Apply</button>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

@if (!empty($added))
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="box">
				<div class="box-content">
					<p><b>Added Subscriptions</b></p>
					<table class="table table-hover table-striped table-bordered table-heading">
						<thead>
							<tr>
								<th>Account ID</th>
								<th>Email</th>
								<th>Name</th>
								<th>Phone</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($added as $accountId => $account)
								<tr>
									<td><a target="_blank" href="/admin/accounts/view?id={{$accountId}}">{{ $accountId }}</a></td>
									<td><a href="mailto:{{ $account->getEmail() }}">{{ $account->getEmail() }}</a></td>
									<td>{{ $account->getProfile()->getFullName() }}</td>
									<td>{{ $account->getProfile()->getPhone() }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endif

@if (!empty($skipped))
	<div class="row">
		<div class="col-xs-12 col-sm-12">
			<div class="box">
				<div class="box-content">
					<p><b>Skipped Subscriptions</b></p>
					<table class="table table-hover table-striped table-bordered table-heading">
						<thead>
							<tr>
								<th>Account ID</th>
								<th>Email</th>
								<th>Name</th>
								<th>Phone</th>
							</tr>
						</thead>
						<tbody>
							@foreach ($skipped as $accountId => $account)
								<tr>
									<td><a target="_blank" href="/admin/accounts/view?id={{$accountId}}">{{ $accountId }}</a></td>
									<td><a href="mailto:{{ $account->getEmail() }}">{{ $account->getEmail() }}</a></td>
									<td>{{ $account->getProfile()->getFullName() }}</td>
									<td>{{ $account->getProfile()->getPhone() }}</td>
								</tr>
							@endforeach
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
@endif


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/packages/search" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>

@stop
