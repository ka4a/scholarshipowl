@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-exchange"></i>
					<span>Results ({{ count($affiliate_goal_mappings) }})</span>
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
							<th>Goal ID</th>
							<th>Redirect Rules Set</th>
							<th>URL Parameter</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($affiliate_goal_mappings as $mapping)
							<tr>
								<td>
								    {{ $mapping->getAffiliateGoalMappingId() }}
								</td>
								<td>
									<a target="_blank" href="/admin/marketing/affiliates?save={{ $mapping->getAffiliateGoalId() }}">{{ $mapping->getAffiliateGoalId() }}</a>
								</td>
                                <td>{{ $mapping->redirectRulesSetName }}</td>
								<td>{{ $mapping->getUrlParameter() }}</td>
								<td>
								    <a href="/admin/marketing/affiliate_goal_mapping/save?id={{ $mapping->getAffiliateGoalMappingId() }}" title="Edit Mapping" class="btn btn-primary">Edit</a>
                                    <a 	href="#"
                                        data-delete-url="/admin/marketing/affiliate_goal_mapping/delete?id={{ $mapping->getAffiliateGoalMappingId() }}"
                                        data-delete-message="Delete Mapping ?"
                                        title="Delete Mapping"
                                        class="btn btn-warning DeleteAffiliateGoalMappingButton">
                                        Delete Mapping
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
