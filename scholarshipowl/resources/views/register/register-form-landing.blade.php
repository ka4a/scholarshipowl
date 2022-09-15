<form id="landingRegForm1" name="landingRegForm1" action="{{ url_builder('post-register') }}" method="post" class="form-inline clearfix">
	<input name="ref" value="" type="hidden">
	<div class="formContainer clearfix">

		<div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">

			<div class="form-group">
				<div class="input-group">
					<label for="first_name" class="sr-only">First Name</label>
					<input value="" class="form-control firstName" required placeholder="First Name" name="first_name" data-input-id="First name" type="text">
				</div>
			</div>
		</div>

		<div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">
			<div class="form-group">
				<div class="input-group">
					<label for="last_name" class="sr-only">Last Name</label>
					<input value="" class="form-control" required placeholder="Last Name" name="last_name" data-input-id="Last name" type="text">
				</div>
			</div>
		</div>

		<div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">

			<div class="form-group">
				<div class="input-group">
					<label for="email" class="sr-only">Email</label>
					<input value="" class="form-control" required placeholder="Email" name="email" data-input-id="Email" type="email">
				</div>
			</div>
		</div>

		<div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">
			<div class="form-group last">
				<div class="input-group">
					<label for="phone" class="sr-only">Phone</label>

					<input value="" class="form-control" required placeholder="Phone" name="phone" data-input-id="Phone" type="tel">
				</div>
			</div>
		</div>

		<div class="info"></div>
	</div>

    <div class="clearfix checkboxes-above {{ (setting("register.checkbox.terms") == "no")?"checkboxes":"" }}">
        @if (is_array($coregs))
            @foreach($coregs as $coreg)
                @if($coreg->getDisplayPosition() == "coreg1a")
                    <div class="formGroupContainer formGroupCheckbox col-xs-12">
                        {!! $coreg->getHtml() !!}
                    </div>
                @elseif($coreg->getDisplayPosition() == "coreg2a")
                    <div class="formGroupContainer formGroupCheckbox col-xs-12">
                        {!! $coreg->getHtml() !!}
                    </div>
                @endif
            @endforeach
        @endif

        @if(setting("register.checkbox.terms") == "no")
            <div class="formGroupContainer formGroupCheckbox col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <label>
                            <input type="checkbox" name="agree_terms" value="on">
                                  <span class="lbl padding-12 mod-checkbox">
                                      I agree with the <a href="/terms" target="_blank">Terms of Use</a> and <a href="/privacy" target="_blank">Privacy Policy</a>, the <a href="/terms#exhibit" target="_blank"> Official Rules of the You Deserve it Scholarship</a> and <a href="/promotion-rules" target="_blank">Official Rules of the Double Your Opportunity Promotion</a>
                                  </span>
                        </label>
                    </div>
                </div>
                @if (is_array($coregs))
                    @foreach($coregs as $coreg)
                        @if($coreg->getDisplayPosition() == "coreg3a")
                            {!! $coreg->getHtml() !!}
                        @endif
                    @endforeach
                @endif
            </div>
        @endif
    </div>

	<div class="row">
		<div class="applyButtonContainer col-xs-12 col-lg-offset-3 col-lg-6">
			<div class="btn-group clearfix">
				<div class="button-wrapper">
					<div class="block-center">
						<button class="btn btn-block btn-lg btn-warning RegisterButton" value="" type="submit">APPLY</button>
					</div>
                    <p>*Details and qualifications for participation in this promotion may apply</p>
				</div>
			</div>
		</div>
	</div>

    <div class="clearfix checkboxes-below {{ (setting("register.checkbox.terms") == "yes")?"checkboxes":"" }}">
        @if (is_array($coregs))
            @foreach($coregs as $coreg)
                @if($coreg->getDisplayPosition() == "coreg1")
                    <div class="formGroupContainer formGroupCheckbox col-xs-12">
                        {!! $coreg->getHtml() !!}
                    </div>
                @elseif($coreg->getDisplayPosition() == "coreg2")
                    <div class="formGroupContainer formGroupCheckbox col-xs-12">
                        {!! $coreg->getHtml() !!}
                    </div>
                @endif
            @endforeach
        @endif

        @if(setting("register.checkbox.terms") == "yes")
            <div class="formGroupContainer formGroupCheckbox col-xs-12">
                <div class="form-group">
                    <div class="input-group">
                        <label>
                            <input type="checkbox" name="agree_terms" value="on" checked>
                              <span class="lbl padding-12 mod-checkbox">
                                  I agree with the <a href="/terms" target="_blank">Terms of Use</a> and <a href="/privacy" target="_blank">Privacy Policy</a>, the <a href="/terms#exhibit" target="_blank"> Official Rules of the You Deserve it Scholarship</a> and <a href="/promotion-rules" target="_blank">Official Rules of the Double Your Opportunity Promotion</a>
                              </span>
                        </label>
                    </div>
                </div>
            </div>
        @endif
        @if (is_array($coregs))
            @foreach($coregs as $coreg)
                @if($coreg->getDisplayPosition() == "coreg3")
                    <div class="formGroupContainer formGroupCheckbox col-xs-12 position-coreg3">
                        {!! $coreg->getHtml() !!}
                    </div>
                @endif
            @endforeach
        @endif
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="value-proposition">
                <p>We provide you access to our proprietary scholarship management interface technology and services which help you apply to as many scholarships as possible in the least amount of time.</p>
            </div>
        </div>
    </div>

	{{ Form::token() }}
	{{ Form::hidden("_return", url_builder("register2")) }}
</form>
