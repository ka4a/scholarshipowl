@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<div class="box">

			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-exchange"></i>
					<span>Results ({{ count($coreg_plugins) }})</span>
				</div>

				<div class="box-icons">
					<a class="new-coreg" data-toggle="tooltip" title="Create new coreg" href="/admin/marketing/coreg_plugin/save">
						<i class="fa fa-plus-circle"></i>
					</a>
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
							<th>ID</th>
							<th>Name</th>
							<th>Visible</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($coreg_plugins as $coreg_plugin)
							<tr>
								<td>
								    {{ $coreg_plugin->getId() }}
								</td>
								<td>{{ $coreg_plugin->getName() }}</td>
								<td>{{ $coreg_plugin->getVisible()?"Yes":"No" }}</td>
								<td>
								    <a
                                        href="/admin/marketing/coreg_plugin/save?id={{ $coreg_plugin->getId() }}" title="Edit Mapping" class="btn btn-primary">Edit</a>
                                    <a 	href="#" data-delete-url="/admin/marketing/coreg_plugin/delete?id={{ $coreg_plugin->getId()  }}"
                                        data-delete-message="Delete Coreg Plugin ?"
                                        title="Delete Coreg Plugin"
                                        class="btn btn-warning DeleteCoregPluginButton">
                                        Delete
                                    </a>
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
