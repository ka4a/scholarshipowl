@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-exchange"></i>
					<span>Results ({{ count($affiliates) }})</span>
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
							<th>Name</th>
							<th>Goals</th>
							<th>Api Key</th>
							<th>Active</th>
							<th>Actions</th>
						</tr>
					</thead>
					
					<tbody>
						@foreach ($affiliates as $affiliateId => $affiliate)
							<tr>
								<td><a href="{{$affiliate->getWebsite()}}" target="_blank" title="View Affiliate Website">{{ $affiliate->getName() }}</a></td>
								
								<td>
									<table class="table table-bordered table-striped">
										<thead>
											<tr><th>Name</th><th>URL</th></tr>
										</thead>
										
										<tbody>
											@foreach ($affiliate->getAffiliateGoals() as $goal)
											<tr>
												<td>{{ $goal->getName() }}</td>
												<td>
													<p><a href="{{ $goal->getUrl() }}" target="_blank">{{ $goal->getUrl() }}</a></p>
													<pre>{{ url('/') }}/affiliate/{{$affiliate->getApiKey()}}/{account_id}/{{$goal->getAffiliateGoalId()}}</pre>
													<!-- 
													<pre>{{ $goal->getDescription() }}</pre>
													 -->
												</td>
											</tr>
											@endforeach
										</tbody>
									</table>
								</td>
								
								<td>{{ $affiliate->getApiKey() }}</td>
								<td>@if ($affiliate->isActive()) <i class='fa fa-check'></i> @endif</td>
								<td>
									<a href="/admin/marketing/affiliates/save?id={{$affiliateId}}" class="btn btn-primary">Edit</a>
									<a data-toggle="modal" data-target="#affiliate{{$affiliateId}}" href="#" class="btn btn-warning">Details</a>
								</td>
							</tr>
						@endforeach
					</tbody>
				</table>			
			</div>
		</div>
	</div>
</div>

@foreach ($affiliates as $affiliateId => $affiliate)
		<div id="affiliate{{$affiliateId}}" class="modal fade">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
						<h4 class="modal-title">{{ $affiliate->getName() }}</h4>
					</div>
					
					<div class="modal-body">
						<table class="table table-bordered table-striped">
							<tbody>
								<tr>
									<th>Name</th>
									<td>{{ $affiliate->getName() }}</td>
								</tr>
							
								<tr>
									<th>Description</th>
									<td>{{ $affiliate->getDescription() }}</td>
								</tr>
							
								<tr>
									<th>Email</th>
									<td>{{ $affiliate->getEmail() }}</td>
								</tr>
								
								<tr>
									<th>Phone</th>
									<td>{{ $affiliate->getPhone() }}</td>
								</tr>
								
								<tr>
									<th>Website</th>
									<td><a href="{{$affiliate->getWebsite()}}" target="_blank" title="View Affiliate Website">{{ $affiliate->getWebsite() }}</a></td>
								</tr>
							</tbody>
						</table>
					</div>
					
					<div class="modal-footer">
						<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
@endforeach


@stop
