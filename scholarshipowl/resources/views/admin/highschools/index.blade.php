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
						</tr>
					</thead>
					<tbody>
						@foreach ($highschools as $id => $highschool)
							<tr>
								<td>
									{{ $highschool->getName() }}
								</td>
								
								<td>
									{{ $highschool->getAddress() }}
								</td>
								
								<td>
									{{ $highschool->getCity() }}
								</td>
								
								<td>
									{{ $highschool->getState() }}
								</td>
								
								<td>
									{{ $highschool->getZip() }}
								</td>
								
								<td>
									{{ $highschool->getPhone() }}
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
