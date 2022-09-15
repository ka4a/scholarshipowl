@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-history"></i>
					<span>Results (0)</span>
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
							<th>Award</th>
							<th>Full Name</th>
							<th>Awarded As</th>
							<th>Awarded Date</th>
						</tr>
					</thead>
					<tbody>
						
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


@stop
