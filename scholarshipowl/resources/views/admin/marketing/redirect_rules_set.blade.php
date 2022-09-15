@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-exchange"></i>
					<span>Results ({{ count($redirect_rules_sets) }})</span>
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
							<th>ID</th>
							<th>Name</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($redirect_rules_sets as $redirect_rules_set)
							<tr>
								<td>
								    {{ $redirect_rules_set->getId() }}
								</td>
								<td>
									{{ $redirect_rules_set->getName() }}
								</td>
								<td>
								    <a href="/admin/marketing/redirect_rules_set/save?id={{ $redirect_rules_set->getId() }}" title="Edit Rule Set" class="btn btn-primary">Edit</a>
                                    <a 	href="#"
                                        data-delete-url="/admin/marketing/redirect_rules_set/delete?id={{ $redirect_rules_set->getId() }}"
                                        data-delete-message="Delete Rules Set?"
                                        title="Delete Rules Set"
                                        class="btn btn-warning DeleteRulesSetButton">
                                        Delete Rules Set
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
