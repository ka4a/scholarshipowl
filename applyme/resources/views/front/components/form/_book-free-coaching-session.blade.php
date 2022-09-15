<div class="form-group">
    {!! Form::email('email', null, ['class' => 'form-control input-lg', 'placeholder' => 'Your Email Address', 'id' => 'eligibility-email']) !!}
    <span class="text-danger">{{ $errors->first('email') }}</span>
</div>

<button type="submit" id="bookFreeCoachingSessionSubmit" class="btn btn-lg btn-default btn-am-default">CHECK MY ELIGIBILITY</button>
