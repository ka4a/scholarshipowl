@extends("admin/base")
@section("content")

<div class="row">
    <div class="col-xs-12">
        @section('box-content')
            {{ Form::open(['method' => 'POST', 'route' => 'admin::scholarships.test', 'files' => true, 'class' => 'form-horizontal']) }}
                {{ Form::hidden('id', $scholarship->getScholarshipId()) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Sender</label>
                        <div class="col-sm-6">
                            {{ Form::input('text', 'from_name', $from[1], ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">From</label>
                        <div class="col-sm-6">
                            {{ Form::input('text', 'from_address', $from[0], ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">To</label>
                        <div class="col-sm-6">
                            {{ Form::input('text', 'to', $to, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Reply To</label>
                        <div class="col-sm-6">
                            {{ Form::input('text', 'replyTo', $replyTo ?? '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <hr />
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Subject</label>
                        <div class="col-sm-6">
                            {{ Form::input('text', 'subject', $subject, ['class' => 'form-control']) }}
                            <pre>{{$scholarship->getEmailSubject()}}</pre>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Body</label>
                        <div class="col-sm-6">
                            {{ Form::textarea('body', $body, ['class' => 'form-control']) }}
                            <pre>{{$scholarship->getEmailMessage()}}</pre>
                        </div>
                    </div>
                    <hr />
                </fieldset>
                <fieldset>
                    <h3>Requirements</h3>
                    @foreach($requirements as $requirement)
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Requirement {{ $requirement->getType() }} ({{ $requirement->getId() }})</label>
                            <div class="col-sm-6">
                                @include('admin.scholarships.test.requirement_fill', ['requirement' => $requirement])
                            </div>
                        </div>
                    @endforeach
                </fieldset>
                <fieldset>
                    <div class="form-group">
                        <div class="col-sm-6">
                            <input type="submit" class="btn btn-primary" value="Send Application" />
                        </div>
                    </div>
                </fieldset>
            {{ Form::close() }}
        @overwrite
        @include('admin.common.box', ['boxName' => 'Test Application Send Scholarship'])
    </div>
</div>
@stop
