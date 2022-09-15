@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-university"></i>
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
							<th>Name</th>
							<th>Address</th>
							<th>City</th>
							<th>State</th>
							<th>Zip</th>
							<th>Phone</th>
							<th>Website</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($universities as $id => $university)
							<tr>
								<td>
									{{ $university->getName() }}
								</td>
								
								<td>
									{{ $university->getAddress() }}
								</td>
								
								<td>
									{{ $university->getCity() }}
								</td>
								
								<td>
									{{ $university->getState() }}
								</td>
								
								<td>
									{{ $university->getZip() }}
								</td>
								
								<td>
									{{ $university->getPhone() }}
								</td>
								
								<td>
									<a href="https://{{$university->getWebsite()}}" target="_blank">{{ $university->getWebsite() }}</a>
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

@stop
