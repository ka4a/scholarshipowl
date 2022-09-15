@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/ab_tests_accounts/{{$ab_test_id}}?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
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
				<form method="get" action="" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">First Name</label>
							<div class="col-sm-6">
								{{ Form::text('first_name', $search['first_name'], array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Last Name</label>
							<div class="col-sm-6">
								{{ Form::text('last_name', $search['last_name'], array("class" => "form-control")) }}
							</div>
						</div>
					
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Test Group</label>
							<div class="col-sm-6">
								{{ Form::select('test_group[]', $options['test_groups'], $search['test_group'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>
								
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Email</label>
							<div class="col-sm-6">
								{{ Form::text('email', $search['email'], array("class" => "form-control")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Username</label>
							<div class="col-sm-6">
								{{ Form::text('username', $search['username'], array("class" => "form-control")) }}
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Conversion Date From</label>
							
							<div class="col-sm-3">
								{{ Form::text('conversion_date_from', $search['conversion_date_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Conversion Date To</label>
						
							<div class="col-sm-3">
								{{ Form::text('conversion_date_to', $search['conversion_date_to'], array("class" => "form-control date_picker")) }}
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
					<i class="fa fa-users"></i>
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
							<th>Account ID</th>
							<th>First Name</th>
							<th>Last Name</th>
							<th>Group</th>
							<th>Conversion Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>	
						@foreach ($data as $accountId => $abTestAccount)
							<tr>
								<td>{{ $accountId }}</td>
								<td>{{ $abTestAccount->getAccount()->getProfile()->getFirstName() }}</td>
								<td>{{ $abTestAccount->getAccount()->getProfile()->getLastName() }}</td>
								<td>{{ $abTestAccount->getTestGroup() }}</td>
								<td>{{ format_date($abTestAccount->getConversionDate()) }}</td>
								
								<td>
									<a href="/admin/accounts/view?id={{ $accountId }}"  class="btn btn-default" target="_blank">View</a>
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
		<p align="right">Export ({{ $count }}): <a href="/admin/export/ab_tests_accounts/{{$ab_test_id}}?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>


@stop
