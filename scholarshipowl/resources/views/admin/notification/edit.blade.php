@extends("admin/base")
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<div class="box-name">
					<span>{{ $notification->getType() }}</span>
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
            {{ Form::open(['method' => 'post', 'route' => ['admin::notification.edit', 'app' => $notification->getApp(), 'type' => $notification->getType()->getId()], 'class' => 'form-horizontal']) }}
                <div class="form-group">
                    <label class="col-xs-3 control-label">Template Id</label>
                    <div class="col-xs-6">
                        {{ Form::text('template_id', $notification->getTemplateId(), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">Title</label>
                    <div class="col-xs-6">
                        {{ Form::textarea('title', $notification->getTitle(), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 control-label">Content</label>
                    <div class="col-xs-6">
                        {{ Form::textarea('content', $notification->getContent(), ['class' => 'form-control']) }}
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label class="col-xs-3 control-label">Active</label>
                    <div class="col-xs-6">
                        {{ Form::select('active', [0 => 'No', 1 => 'Yes'],(int) $notification->isActive(), ['class' => 'form-control']) }}
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 text-right">Maximum</label>
                    <div class="col-xs-1">{{ Form::text('cap_amount', $notification->getCapAmount(), ['class' => 'form-control']) }}</div>
                    <div class="col-xs-8">0 - No limit</div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 text-right">In period</label>
                    <div class="col-xs-1">{{ Form::text('cap_value', $notification->getCapValue(), ['class' => 'form-control']) }}</div>
                    <div class="col-xs-2">{{ Form::select('cap_type', \App\Entity\OnesignalNotification::$periodTypes, $notification->getCapType(), ['class' => 'form-control']) }}</div>
                    <div class="col-xs-6">Send maximum in this period</div>
                </div>
                <div class="form-group">
                    <label class="col-xs-3 text-right">Delay</label>
                    <div class="col-xs-1">{{ Form::text('delay_value', $notification->getDelayValue(), ['class' => 'form-control']) }}</div>
                    <div class="col-xs-2">{{ Form::select('delay_type', \App\Entity\OnesignalNotification::$periodTypes, $notification->getDelayType(), ['class' => 'form-control']) }}</div>
                    <div class="col-xs-6">Send after this period</div>
                </div>
                <hr/>
                <div class="row">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-9">{{ Form::submit('Save', ['class' => 'btn btn-success']) }}</div>
                </div>
            {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection
