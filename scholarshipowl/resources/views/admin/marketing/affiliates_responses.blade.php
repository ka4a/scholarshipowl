@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/affiliates-responses?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
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
				<form method="get" action="/admin/marketing/affiliates_responses" class="form-horizontal">
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

						<div class="form-group">
							<label class="col-sm-3 control-label">Affiliate Name</label>
							<div class="col-sm-6">
								{{ Form::text('affiliate_name', $search['affiliate_name'], array("class" => "form-control")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Response Date From</label>
							<div class="col-sm-3">
								{{ Form::text('response_date_from', $search['response_date_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Response Date To</label>
							<div class="col-sm-3">
								{{ Form::text('response_date_to', $search['response_date_to'], array("class" => "form-control date_picker")) }}
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
					<i class="fa fa-exchange"></i>
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
							<th>Created Date</th>
							<th>Affiliate</th>
							<th>Goal</th>
							<th>URL</th>
							<th>Response Date</th>
							<th>Aff ID</th>
							<th>Aff Sub</th>
							<th>Aff Sub2</th>
							<th>Aff Sub3</th>
							<th>Aff Sub4</th>
							<th>Aff Sub5</th>
						</tr>
					</thead>
					
					<tbody>
						@foreach ($responses as $responsesId => $response)
							<tr>
								<td>
									<a target="_blank" href="/admin/accounts/view?id={{$response['account_id']}}">{{ $response["first_name"] }} {{ $response["last_name"] }}</a>
								</td>
								
								<td>{{ format_date($response["created_date"]) }}</td>
								<td>{{ $response["affiliate_name"] }}</td>
								<td>{{ $response["goal_name"] }}</td>
								<td>{{ $response["url"] }}</td>
								<td>{{ format_date($response["response_date"]) }}</td>
								
								@if (array_key_exists($response["account_id"], $has_offers))
									<td>{{ @$has_offers[$response["account_id"]]["affiliate_id"] }}</td>
									<td>{{ @$has_offers[$response["account_id"]]["aff_sub"] }}</td>
									<td>{{ @$has_offers[$response["account_id"]]["aff_sub2"] }}</td>
									<td>{{ @$has_offers[$response["account_id"]]["aff_sub3"] }}</td>
									<td>{{ @$has_offers[$response["account_id"]]["aff_sub4"] }}</td>
									<td>{{ @$has_offers[$response["account_id"]]["aff_sub5"] }}</td>
								@else
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
									<td></td>
								@endif
							</tr>
						@endforeach
					</tbody>
				</table>
				
				@include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))		
			</div>
		</div>
	</div>
</div>


@stop
