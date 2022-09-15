@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12">
		<p align="right">Export ({{ $count }}): <a href="/admin/export/submissions?{{ http_build_query($pagination['url_params']) }}">CSV</a></p>
	</div>
</div>

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
				<form method="get" action="/admin/marketing/submissions" class="form-horizontal">
					<fieldset>

						<div class="form-group">
							<label class="col-sm-3 control-label">Name</label>
							<div class="col-sm-6">
								{{ Form::select('name[]', $options['names'], $search['name'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Status</label>
							<div class="col-sm-6">
								{{ Form::select('status[]', $options['statuses'], $search['status'], array("class" => "populate placeholder select2", "multiple" => "multiple")) }}
							</div>
						</div>

						<hr />

                        <div class="form-group">
                            <label class="col-sm-3 control-label">Send Date From</label>
                            <div class="col-sm-3">
                                {{ Form::text('send_date_from', $search['send_date_from'], array("class" => "form-control date_picker")) }}
                            </div>
                        </div>

						<div class="form-group">
                            <label class="col-sm-3 control-label">Send Date To</label>
                            <div class="col-sm-3">
                                {{ Form::text('send_date_to', $search['send_date_to'], array("class" => "form-control date_picker")) }}
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
					<i class="fa fa-user"></i>
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
							<th>Full Name</th>
							<th>Email</th>
							<th>IP</th>
							<th>Name(Coreg plugin name)</th>
							<th>Status</th>
							<th>Send Date</th>
							<th>Source</th>
							<th>Actions</th>
						</tr>
					</thead>

					<tbody>
						@foreach ($submissions as $entity)
							<tr>
								<td><a href="/admin/accounts/view?id={{ $entity->getAccount()->getAccountId() }}" target="_blank">{{ $entity->getAccount()->getProfile()->getFullName() }}</a></td>
								<td>{{ $entity->getAccount()->getEmail() }}</td>
								<td>{{ $entity->getIpAddress() }}</td>
								<td>{{ $entity->getName() }} ({{$entity->getCoregPlugin()->getName()}})</td>
								<td>{{ ucfirst($entity->getStatus()) }}</td>
								<td>{{ format_date($entity->getSendDate()) }}</td>
								<td>{{ is_null($entity->getSource())? "-" : $entity->getSource()->getSource()  }}</td>
								<td><a data-toggle="modal" data-target="#submission{{$entity->getSubmissionId()}}" href="#" class="btn btn-warning">Response</a></td>
							</tr>
						@endforeach
					</tbody>
				</table>

				@include ('admin/common/pagination', array('page' => $pagination['page'], 'pages' => $pagination['pages'], 'url' => $pagination['url'], 'url_params' => $pagination['url_params']))
			</div>
		</div>
	</div>
</div>


@foreach ($submissions as $entity)
	<div id="submission{{$entity->getSubmissionId()}}" class="modal fade">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
					<h4 class="modal-title">
						{{ $entity->getAccount()->getProfile()->getFullName() }}
					</h4>
				</div>

				<div class="modal-body">
					<p readonly="readonly" style="border: none; width: 100%; height: 200px; overflow: hidden;">{{ $entity->getResponse() }}</p>
				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				</div>
				</div>
			</div>
		</div>
@endforeach

@stop
