@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-sm-8">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Information</p>
						
						<p><b>Title: </b>{{ $scholarship->SCHOL_NM }}</p>
						<p><b>Patron: </b>{{ $scholarship->PATRON_NM }}</p>
						<p><b>Amount: </b>{{ $scholarship->AMOUNT }}</p>
						<p><b>Deadline: </b>{{ $scholarship->DEADLINE }}</p>
						<p><b>UUID: </b>{{ $scholarship->S_UUID }}</p>
						<p><b>URL: </b> <a href="{{ $scholarship->WEBSITE }}" target="_blank">{{ $scholarship->WEBSITE }}</a></p>
					</div>
				</div>
			</div>
			
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">How To Apply</p>
						<p>{{ $scholarship->GET_APP }}</p>						
					</div>
				</div>
				
				<div class="box">
					<div class="box-content">
						<p class="page-header">Eligibility</p>
						<p>{{ $scholarship->SCHOL_ELIG }}</p>						
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<div class="col-sm-4">
		<div class="row">
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Location</p>
						
						<p><b>City: </b>{{ $scholarship->CITY }}</p>
						<p><b>Zip: </b>{{ $scholarship->ZIP }}</p>
						<p><b>State: </b>{{ $scholarship->STATE }}</p>
						
						@if (!empty($scholarship->ADDRESS_1))
						<p><b>Address 1: </b>{{ $scholarship->ADDRESS_1 }}</p>
						@endif
						
						@if (!empty($scholarship->ADDRESS_2))
						<p><b>Address 2: </b>{{ $scholarship->ADDRESS_2 }}</p>
						@endif
						
						@if (!empty($scholarship->ADDRESS_3))
						<p><b>Address 3: </b>{{ $scholarship->ADDRESS_3 }}</p>
						@endif						
					</div>
				</div>
			</div>
			
			<div class="col-xs-12">
				<div class="box">
					<div class="box-content">
						<p class="page-header">Other</p>
						
						<p><b>Min. Level: </b>{{ $scholarship->LEVEL_MIN }}</p>
						<p><b>Max. Level: </b>{{ $scholarship->LEVEL_MAX }}</p>
						<p><b>Awards: </b>{{ $scholarship->NUM_AWARDS }}</p>
						<p><b>Renew: </b>{{ $scholarship->RENEW }}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p>
				<a href="/admin/scholarships/super-college" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>


@stop
