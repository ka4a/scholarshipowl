@extends("admin/base")
@section("content")

<div class="row">
	<div class="col-xs-12 col-sm-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<i class="fa fa-save"></i>
					<span>{{ $data['title'] }}</span>
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
				<form method="post" action="/admin/accounts/conversations/post-{{ $data['action'] }}" class="form-horizontal ajax_form">
					{!! Form::token() !!}

          @if ($data['conversation_id'] != '')
          {!! Form::hidden('conversation_id', $data['conversation_id']) !!}
          @endif

          {!! Form::hidden('account_id', $data['account_id']) !!}

					<fieldset>
						<div class="form-group">
							<label class="col-sm-3 control-label">Conversation Status</label>
							<div class="col-sm-6">
								{!! Form::select('status', $options['statuses'], $data['status'], array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<div class="form-group">
							<label class="col-sm-3 control-label">Conversation Potential</label>
							<div class="col-sm-6">
								{!! Form::select('potential', $options['potentials'], $data['potential'], array("class" => "populate placeholder select2")) !!}
							</div>
						</div>

						<hr />

						<div class="form-group">
							<label class="col-sm-3 control-label">Comment</label>
							<div class="col-sm-6">
								{!! Form::textarea('comment', $data['comment'], array("class" => "form-control")) !!}
							</div>
						</div>

					</fieldset>

					<fieldset>
						<div class="form-group">
							<div class="col-sm-6">
								<a href="#" class="btn btn-primary SaveButton">{{ $data['action_msg'] }} conversation</a>
							</div>
						</div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>
@stop
