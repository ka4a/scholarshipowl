@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-university"></i>
					<span>Results ({{ count($scholarships) }})</span>
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
							<th>Title</th>
							<th>Type</th>
							<th>Amount</th>
							<th>Free</th>
							<th>Deadline</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($scholarships as $id => $scholarship)
							<tr>
								<td>
									@if (!$scholarship->isActive())<strike>@endif
									<a target="_blank" href="/admin/scholarships/view?id={{ $id }}">{{ $scholarship->getTitle() }}</a>
									@if (!$scholarship->isActive())</strike>@endif
								</td>
								
								<td>
									@if ($scholarship->getApplicationType() == "email")
										Email
									@elseif ($scholarship->getApplicationType() == "online")
										Online
									@endif
								</td>
								
								<td>{{ $scholarship->getAmount() }}</td>
								<td>@if($scholarship->isFree()) {{ 'Yes' }} @else {{ 'No' }} @endif</td>
								
								<td>
									@if (!$scholarship->isExpired())<strike>@endif
									{{ format_date($scholarship->getExpirationDate()) }}
									@if (!$scholarship->isExpired())</strike>@endif
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
