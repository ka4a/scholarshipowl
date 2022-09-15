@extends("admin/base")
@section("content")


<div class="row">
	@foreach ($packages as $packageId => $package)
		<div class="col-xs-12 col-sm-3">
			<div class="box box-pricing">
				<div class="box-header">
					<div class="box-name">
						{{ $package->getName() }} 
						<br /> 
						<small>{{ nl2br($package->getDescription()) }}</small>
					</div>
					<div class="no-move"></div>
				</div>
				
				<div class="box-content no-padding">
					<div class="row-fluid centered">
						<div class="col-sm-12">
							Scholarships: 
							@if ($package->isScholarshipsUnlimited())
								UNLIMITED
							@else
								{{ $package->getScholarshipsCount() }}
							@endif
						</div>
						
						<div class="col-sm-12">
							Expires: 
							@if ($package->isExpirationTypeDate())
								{{ substr($package->getExpirationDate(), 0, 10) }}
							@elseif ($package->isExpirationTypeNoExpiry())
								NEVER
							@else
								After {{ $package->getExpirationPeriodValue() }} {{ $package->getExpirationPeriodType() }}(s)
							@endif
						</div>
						
						<div class="col-sm-12">
							Active: 
							
							@if ($package->isActive())
								YES
							@else
								NO
							@endif				
						</div>
						
						<div class="col-sm-12">
							<b>${{ $package->getPrice() }}</b>
						</div>
						
						<div class="clearfix"></div>
					</div>

                    @can('access-route', 'packages.edit')
					<div class="row-fluid bg-default">
						<div class="col-sm-6">
							<a href="/admin/packages/save?id={{ $packageId }}" class="btn btn-primary btn-block">Edit</a>
						</div>
						
						<div class="col-sm-6">
							@if ($package->isActive())
								<a href="/admin/packages/deactivate?id={{ $packageId }}" class="btn btn-danger btn-block">Deactivate</a>	
							@else 
								<a href="/admin/packages/activate?id={{ $packageId }}" class="btn btn-success btn-block">Activate</a>
							@endif
						</div>
					
						<div class="clearfix"></div>
					</div>
                    @endcan
				</div>
			</div>
		</div>
	@endforeach
</div>


@stop
