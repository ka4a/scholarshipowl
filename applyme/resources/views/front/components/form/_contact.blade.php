<div class="form-group">
    {!! Form::text('name', null, ['class' => 'form-control input-lg', 'placeholder' => 'Your name']) !!}
    <span class="text-danger">{{ $errors->first('name') }}</span>
</div>

<div class="form-group">
    {!! Form::email('email', null, ['class' => 'form-control input-lg', 'placeholder' => 'Your Email Address']) !!}
    <span class="text-danger">{{ $errors->first('email') }}</span>
</div>

<div class="form-group">
    {!! Form::textarea('message', null, ['class' => 'form-control input-lg', 'rows' => 5, 'placeholder' => 'Message']) !!}
    <span class="text-danger">{{ $errors->first('message') }}</span>
</div>

<div class="contact-btn">
    <button type="submit" class="btn btn-lg btn-default btn-am-default">SEND MESSAGE</button>
</div>
