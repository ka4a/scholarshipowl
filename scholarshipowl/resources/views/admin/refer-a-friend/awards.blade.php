@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-gift"></i>
					<span>Results ({{ count($awards) }})</span>
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
							<th>Type</th>
							<th>Referrals Number</th>
							<th>Referral Package</th>
							<th>Referred Package</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($awards as $awardId => $award)
							<tr>
								<td>{{ $award->getName() }}</td>
								<td>{{ $award->getReferralAwardType() }}</td>
								<td>{{ $award->getReferralsNumber() }}</td>
								
								<td>
									@if ( !is_null($award->getReferralPackage()) && array_key_exists($award->getReferralPackage()->getPackageId(), $packages))
										<table class="table table-bordered table-striped">
											<thead>
												<tr><th></th><th></th></tr>
											</thead>
										
											<body>
												<tr>
													<th>Name</th>
													<td>{{ $packages[($award->getReferralPackage() != null ) ? $award->getReferralPackage()->getPackageId(): '']->getName() }}</td>
												</tr>
												<tr>
													<th>Price</th>
													<td>{{ $packages[($award->getReferralPackage() != null ) ?$award->getReferralPackage()->getPackageId() : '']->getPrice() }}$</td>
												</tr>
												<tr>
													<th>Scholarships</th>
													<td>
														@if ($packages[($award->getReferralPackage() != null ) ? $award->getReferralPackage()->getPackageId() : '']->isScholarshipsUnlimited())
															UNLIMITED
														@else
															{{ $packages[($award->getReferralPackage() != null ) ? $award->getReferralPackage()->getPackageId() : '']->getScholarshipsCount() }}
														@endif
													</td>
												</tr>
											</body>
										</table>
									@endif
								</td>
								
								<td>
									@if (array_key_exists($award->getReferredPackage()->getPackageId(), $packages))
										<table class="table table-bordered table-striped">
											<thead>
												<tr><th></th><th></th></tr>
											</thead>
										
											<body>
												<tr>
													<th>Name</th>
													<td>{{ $packages[($award->getReferredPackage() != null ) ? $award->getReferredPackage()->getPackageId() : '']->getName() }}</td>
												</tr>
												<tr>
													<th>Price</th>
													<td>{{ $packages[($award->getReferredPackage() != null ) ? $award->getReferredPackage()->getPackageId() : '']->getPrice() }}$</td>
												</tr>
												<tr>
													<th>Scholarships</th>
													<td>
														@if ($packages[($award->getReferredPackage() != null ) ? $award->getReferredPackage()->getPackageId() : '']->isScholarshipsUnlimited())
															UNLIMITED
														@else
															{{ $packages[($award->getReferredPackage() != null ) ? $award->getReferredPackage()->getPackageId() : '']->getScholarshipsCount() }}
														@endif
													</td>
												</tr>
											</body>
										</table>
									@endif
								</td>
								
								<td>
                                    @can('access-route', 'refer-a-friend.edit')
									<a href="/admin/refer-a-friend/awards/save?id={{ $awardId }}" class="btn btn-primary">Edit</a>
									
									@if ($award->isActive())
										<a href="/admin/refer-a-friend/awards/deactivate?id={{ $awardId }}" class="btn btn-default">Deactivate</a>	
									@else 
										<a href="/admin/refer-a-friend/awards/activate?id={{ $awardId }}" class="btn btn-success">Activate</a>
									@endif
                                    @endcan
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
