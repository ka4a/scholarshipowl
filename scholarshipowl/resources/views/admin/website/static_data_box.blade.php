<div class="box">
	<div class="box-header">
		<div class="box-name">
			<i class="fa fa-table"></i>
			<span>{{ $title }}</span>
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
		<table class="table table-hover table-border">
			<thead>
				<tr>
					<th>#</th>
					<th>Name</th>
				</tr>
			</thead>
			
			<tbody>
				@foreach ($data as $key => $value)
					<tr>
						<td>{{ $key }}</td>
						<td>{{ $value }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>
