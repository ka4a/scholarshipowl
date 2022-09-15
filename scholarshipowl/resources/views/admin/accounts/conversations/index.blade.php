@extends("admin/base")
@section("content")


<div class="row">
	<div class="col-xs-12">
		<div class="col col-12 pull-right">
			<p><a href="/admin/accounts/conversations/add?aid={{ $account_id }}" class="btn btn-primary btn-large">Add Conversation</a></p>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-phone"></i>
					<span>Conversations ({{ count($conversations) }})</span>
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
							<th>Status</th>
							<th>Potential</th>
							<th>Comments</th>
							<th>Date</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
					@foreach ($conversations as $conversation)
						<tr>
							<td>{{ $options['statuses'][$conversation->getStatus()] }}</td>
							<td>{{ $options['potentials'][$conversation->getPotential()] }}</td>
							<td>{{ $conversation->getComment() }}</td>
							<td>{{ $conversation->getLastConversationDate() }}</td>
              <td>
								<a href="/admin/accounts/conversations/edit?id={{ $conversation->getConversationId() }}" title="Edit Conversation" class="btn btn-primary">Edit</a>
								<a href="/admin/accounts/conversations/delete?id={{ $conversation->getConversationId() }}" title="Delete Conversation" class="btn btn-warning">Delete</a>
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
