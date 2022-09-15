@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-cubes"></i>
					<span>Results ({{ count($popups) }})</span>
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
							<th>Description</th>
							<th>Display</th>
							<th>Start Date</th>
							<th>End Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						@foreach ($popups as $popupId => $popup)
							<tr>
								<td>{!! $popup->getPopupTitle() !!}</td>
								<td>{!! nl2br($popup->getPopupText()) !!}</td>
								<td>{!! $popup->getDisplayPosition() !!}</td>
								<td>{!! format_date($popup->getStartDate()) !!}</td>
								<td>{!! format_date($popup->getEndDate()) !!}</td>
								<td>
									<a href="/admin/popup/save?id={{ $popupId }}" class="btn btn-primary">Edit</a>
									<a href="#" data-delete-url="/admin/popup/delete?id={{ $popupId }}" data-delete-message="Delete Popup ?" title="Delete Popup" class="btn btn-danger DeletePopupButton">Delete</a>
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
