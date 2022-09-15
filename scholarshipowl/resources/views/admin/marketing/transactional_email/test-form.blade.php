{{ Form::open(['method' => 'post', 'route' => ['admin::marketing.transactional_email.testTransactionalEmail'], 'class' => 'form-horizontal']) }}
    <fieldset>
        @if (!isset($transactionalEmailId))
            <div class="form-group">
                <label class="control-label col-xs-3">Event name</label>
                <div class="col-xs-6">
                    {{ Form::select('transactionalEmailId', \App\Entity\TransactionalEmail::options(), null, ['class' => 'form-control']) }}
                </div>
            </div>
        @else
            {{ Form::hidden('transactionalEmailId', $transactionalEmailId) }}
        @endif
        <div class="form-group">
            <label class="control-label col-xs-3">Account Id</label>
            <div class="col-xs-6">
                {{ Form::text('accountId', isset($accountId) ? $accountId : null, ['class' => 'form-control']) }}
            </div>
        </div>
    </fieldset>
    <hr/>
    <fieldset>
        <div class="col-xs-3"></div>
        {{ Form::submit('Send test email', ['class' => 'btn btn-success']) }}
    </fieldset>
{{ Form::close() }}
