@extends('admin.base')
@section('content')

@if($winner)
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    {{ $title }}
                </div>
            </div>
            <?php
                /** @var \App\Entity\Winner $winner */
            ?>
            <div class="box-content">
                {{ Form::open([
                    'route' => ['admin::winners.edit', $winner->getId()],
                    'class' => 'form-horizontal',
                    'method' => 'post',
                    'files' => true
                   ])
                }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Account ID</label>
                        <div class="col-xs-6">
                            {{ Form::text('account_id', $winner->getAccountId(), ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Scholarship ID</label>
                        <div class="col-xs-6">
                            {{ Form::text('scholarship_id', $winner->getScholarshipId(), ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-xs-3 control-label">Scholarship title</label>
                        <div class="col-xs-6">
                            {{ Form::text('scholarship_title', $winner->getScholarshipTitle(), ['class' => 'form-control', 'required'=> 'true']) }}
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-xs-3 control-label">Amount won</label>
                        <div class="col-xs-6">
                            {{ Form::text('amount_won', $winner->getAmountWon(), ['class' => 'form-control', 'required'=> 'true']) }}
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-xs-3 control-label">Won at</label>
                        <div class="col-xs-6">
                            {{ Form::text('won_at',  $winner->getWonAt() ? $winner->getWonAt()->format('Y-m-d') : null, ["class" => "form-control date_picker", 'required'=> 'true']) }}
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-xs-3 control-label">Winner name</label>
                        <div class="col-xs-6">
                            {{ Form::text('winner_name', $winner->getWinnerName(), ['class' => 'form-control', 'required'=> 'true']) }}
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-sm-3 control-label">Winner photo</label>
                        <div class="col-sm-6">
                            {{ Form::file('winner_photo') }}

                            @if ($image = $winner->getWinnerPhoto())
                                <br />
                                <img width="300" src="{{ $image }}" />
                                <br /><br />
                            @endif
                        </div>
                    </div>
                    <div class="form-group required">
                        <label class="col-xs-3 control-label">Testimonial text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('testimonial_text', $winner->getTestimonialText(), ['class' => 'form-control', 'required'=> 'true']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Testimonial video</label>
                        <div class="col-xs-6">
                            {{ Form::text('testimonial_video', $winner->getTestimonialVideo(), ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Is Published</label>
                        <div class="col-xs-6">
                            {{ Form::checkbox('published', 1, $winner->getPublished(), ['class' => 'form-control chb-small']) }}
                        </div>
                    </div>
                </fieldset>
                <hr/>
                <div class="row">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-9">
                        {{ Form::submit('Save', ['class' => 'btn btn-success']) }}
                        <a class="btn btn-primary" target="_blank" href=" {{ route('admin::winners.view', ['id' => $winner->getId()])  }}">View</a>
                        <a
                            class="btn btn-danger"
                            onclick="if(confirm('Delete the Winner with ID:{{$winner->getId()}}?')) {window.location = '{{ route('admin::winners.delete', ['id' => $winner->getId()]) }}'}"
                            href="#"
                        >
                            Delete
                        </a>
                    </div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@else
<div class="row">
	<div class="col-xs-12">
	<p>Winner not found!</p>
		<div class="col col-12 pull-right">
			<p>
				<a href="{{route('admin::winners.search')}}" class="btn btn-default">Back To Search</a>
			</p>
		</div>
	</div>
</div>
@endif
@endsection
