@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/missions-progress?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
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
				<form method="get" action="/admin/missions/progress" class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Mission</label>
							<div class="col-sm-6">
								{{ Form::select('mission_id[]', $options['missions'], $search['mission_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Mission Status</label>
							<div class="col-sm-6">
								{{ Form::select('mission_status[]', $options['missions_statuses'], $search['mission_status'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal</label>
							<div class="col-sm-6">
								{{ Form::select('affiliate_goal_id[]', $options['affiliate_goals'], $search['affiliate_goal_id'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Status</label>
							<div class="col-sm-3">
								{{ Form::select('affiliate_goal_status', $options['affiliate_goals_statuses'], $search['affiliate_goal_status'], array("class" => "populate placeholder select2")) }}
							</div>
						</div>
						
						<hr />
						
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
							<label class="col-sm-3 control-label">Mission Started From</label>
							
							<div class="col-sm-3">
								{{ Form::text('mission_started_from', $search['mission_started_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Mission Started To</label>
							
							<div class="col-sm-3">
								{{ Form::text('mission_started_to', $search['mission_started_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Mission Ended From</label>
							
							<div class="col-sm-3">
								{{ Form::text('mission_ended_from', $search['mission_ended_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Mission Ended To</label>
							
							<div class="col-sm-3">
								{{ Form::text('mission_ended_to', $search['mission_ended_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<hr />
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Started From</label>
							
							<div class="col-sm-3">
								{{ Form::text('affiliate_goal_started_from', $search['affiliate_goal_started_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Started To</label>
							
							<div class="col-sm-3">
								{{ Form::text('affiliate_goal_started_to', $search['affiliate_goal_started_to'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Accomplished From</label>
							
							<div class="col-sm-3">
								{{ Form::text('affiliate_goal_accomplished_from', $search['affiliate_goal_accomplished_from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>
						
						<div class="form-group">
							<label class="col-sm-3 control-label">Goal Accomplished To</label>
							
							<div class="col-sm-3">
								{{ Form::text('affiliate_goal_accomplished_to', $search['affiliate_goal_accomplished_to'], array("class" => "form-control date_picker")) }}
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
					<i class="fa fa-trophy"></i>
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
							<th>Mission</th>
							<th>Mission Status</th>
							<th>Mission Started</th>
							<th>Mission Ended</th>
							<th>Goal</th>
							<th>Goal Status</th>
							<th>Goal Started</th>
							<th>Goal Ended</th>
						</tr>
					</thead>
					
					<tbody>
						@foreach ($data as $row)
							<tr>
								<td>
									<a target="_blank" href="/admin/accounts/view?id={{$row->account_id}}">{{ $row->first_name }} {{ $row->last_name }}</a>
								</td>
								
								<td>{{ format_date($row->created_date) }}</td>
								<td>{{ $row->mission_name }}</td>
								<td>{{ ucwords(str_replace("_", " ", $row->mission_status)) }}</td>
								<td>{{ $row->mission_date_started }}</td>
								<td>@if ($row->mission_date_ended != "0000-00-00 00:00:00") {{ $row->mission_date_ended }} @endif</td>
								<td>{{ $row->mission_goal_name }}</td>
								<td>
									@if ($row->mission_goal_is_accomplished)
										Accomplished
									@elseif ($row->mission_goal_is_started)
										Started
									@else
										Pending
									@endif
								</td>
								<td>@if ($row->mission_goal_date_started != "0000-00-00 00:00:00") {{ $row->mission_goal_date_started }} @endif</td>
								<td>@if ($row->mission_goal_date_accomplished != "0000-00-00 00:00:00") {{ $row->mission_goal_date_accomplished }} @endif</td>
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
		<p align="right">Export ({{ $count }}): <a href="/admin/export/missions-progress?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>

@stop
