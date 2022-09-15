@extends("admin/base")
@section("content")


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
							<th>Mission</th>
							<th>Goals</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($data as $row)
							<tr>
								<td>
									<a href="/admin/accounts/view?id={{$row->getAccount()->getAccountId()}}" target="_blank">
										{{ $row->getAccount()->getProfile()->getFullName() }}
									</a>
								</td>
								
								<td>
									<table class="table table-bordered table-striped">
										<thead>
											<tr><th></th><th></th></tr>
										</thead>
										
										<body>
											<tr>
												<th>Name</th>
												<td>{{ $row->getMission()->getName() }}</td>
											</tr>
											
											<tr>
												<th>Status</th>
												<td>{{ $options["statuses"][$row->getStatus()] }}</td>
											</tr>
											
											<tr>
												<th>Active</th>
												<td>@if ($row->getMission()->isActive()) Yes @else No @endif</td>
											</tr>
											
											<tr>
												<th>Start Date</th>
												<td>{{ format_date($row->getDateStarted()) }}</td>
											</tr>
											
											<tr>
												<th>End Date</th>
												<td>{{ format_date($row->getDateEnded()) }}</td>
											</tr>
										</body>
									</table>
								</td>
								
								<td>
									@foreach ($row->getMissionGoalAccounts() as $missionGoalAccountId => $missionGoalAccount)
									<table class="table table-bordered table-striped">
										<thead>
											<tr><th></th><th></th></tr>
										</thead>
										
										<body>
											<tr>
												<th>Name</th>
												<td>{{ $missionGoalAccount->getMissionGoal()->getName() }}</td>
											</tr>
											
											<tr>
												<th>Type</th>
												<td>{{ $missionGoalAccount->getMissionGoal()->getMissionGoalType() }}</td>
											</tr>
											
											<tr>
												<th>Points</th>
												<td>{{ $missionGoalAccount->getMissionGoal()->getPoints() }}</td>
											</tr>
											
											<tr>
												<th>Accomplished</th>
												<td>@if ($missionGoalAccount->isAccomplished()) Yes @else No @endif</td>
											</tr>
											
											<tr>
												<th>Affiliate</th>
												<td>{{ $missionGoalAccount->getMissionGoal()->getAffiliateGoal()->getAffiliate()->getName() }}</td>
											</tr>
											
											<tr>
												<th>Affiliate Goal</th>
												<td>{{ $missionGoalAccount->getMissionGoal()->getAffiliateGoal()->getName() }}</td>
											</tr>
										</body>
									</table>
									
									@endforeach
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


@stop
