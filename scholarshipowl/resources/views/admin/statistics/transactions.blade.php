@extends("admin/base")
@section("content")

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
				<form method="get" class="form-horizontal">
					<fieldset>
						{{ Form::hidden('for', 'transactions') }}
						<div class="form-group">
							<label class="col-sm-3 control-label">Starting Date</label>

							<div class="col-sm-3">
								{{ Form::text('from', $search['from'], array("class" => "form-control date_picker")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">End Date</label>
							<div class="col-sm-3">
								{{ Form::text('to', $search['to'], array("class" => "form-control date_picker")) }}
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
					<i class="fa fa-star"></i>
					<span>Results
					@if (!empty($search['from']))
					starting from <b>{{ $search['from'] }}</b>
					@endif
					@if (!empty($search['to']))
					ending at <b>{{ $search['to'] }}</b>
					@endif
					</span>
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
			<div class="box-content">
				<p><b>Total transactions: </b>{{ count($transactions) }}</p>
				<p><b>Paying customers: </b>{{ $customers }}</p>
				<p><b>Scholarship applications sold: </b>{{ $applications }}</p>
				<p><b>Total transaction amount: </b>{{ $amount }}</p>
				<p><b># of packages sold: </b>{{ count($transactions) }}</p>
			</div>
			<div class="box-content">
				<p class="page-header"><b>Total Transaction Amount and Number of Applications by package</b></p>
				<table class="table table-hover table-striped table-bordered table-heading">
					<thead>
						<tr>
							<th>Package</th>
							<th>Transaction Amount</th>
							<th>Number of applications</th>
						</tr>
					</thead>
					<tbody>
					@foreach($packages as $package)
					<tr>
						<td>{{ $package->getName() }}</td>
						@if (array_key_exists($package->getPackageId(), $packageTotals))
						<td>
							{{ $packageTotals[$package->getPackageId()]['amount'] }}
						</td>
						<td>
							{{ $packageTotals[$package->getPackageId()]['scholarships'] }}
						</td>
						@else
						<td>0</td><td>0</td>
						@endif
					</tr>
					@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>

@stop
