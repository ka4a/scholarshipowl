<form id="registerForm1" name="registerForm1" action="{{ url_builder('post-register') }}" method="post"
      class="center-block clearfix ajax_form invisible">

    <div class="row">

        {{ Form::token() }}
        {{ Form::hidden("_return", url_builder("register2")) }}

        <div class="form-wrapper center-block clearfix">

            <div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <label>First Name</label>
                        <div class="tooltip-register animated bounceInDown pre-tooltip">
                            <a id="register-tooltip" href="#" data-toggle="tooltip"
                               data-original-title="Follow the next step!" data-animation="true" data-placement="top"
                               class="center-block">Follow the next step!</a>
                        </div>
                        <label for="first_name" class="sr-only">First Name</label>

                        <input value="{{ @$session['first_name'] }}" class="form-control highlighted firstName" required
                               placeholder="First Name" name="first_name" type="text" data-input-id="First name"
                               data-validation="first_name">
                    </div>
                </div>
            </div>

            <div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <label>Last Name</label>
                        <label for="last_name" class="sr-only">Last Name</label>
                        <input value="{{ @$session['last_name'] }}" class="form-control" required
                               placeholder="Last Name" name="last_name" type="text" data-input-id="Last name"
                               data-validation="last_name">
                    </div>
                </div>
            </div>

            <div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <label for="email">Email</label>
                        <input value="{{ @$session['email'] }}" class="form-control" required placeholder="Email"
                               name="email" type="email" data-input-id="Email"
                               data-validation="email">
                    </div>
                </div>
            </div>

            <div class="formGroupContainer col-xs-12 col-sm-6 col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <label for="phone">Phone</label>
                        <input value="{{ @$session['phone'] }}" class="form-control" required placeholder="Phone"
                               name="phone" type="tel" data-input-id="Phone"
                               data-mask="true"
                               data-validation="phone">
                    </div>
                </div>
            </div>
        </div>

        <div class="clearfix checkboxes-above {{ (setting("register.checkbox.terms") == "no")?"checkboxes":"" }}">
            @if (!$coregs->isEmpty())
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

            @if(setting("register.checkbox.call_visible") == "yes" && setting("register.checkbox.call") == "no")
                @include("includes.call")
            @endif

            @if(setting("register.checkbox.terms") == "no")
                <div class="formGroupContainer formGroupCheckbox col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <label>
                                <input type="checkbox" name="agree_terms" value="on">
                                <span class="lbl padding-12 mod-checkbox">
                                      I agree with the <a href="/terms" target="_blank">Terms of Use</a> and <a
                                            href="/privacy" target="_blank">Privacy Policy</a>, the <a
                                            href="/terms#exhibit" target="_blank"> Official Rules of the You Deserve it Scholarship</a> and <a
                                            href="/promotion-rules" target="_blank">Official Rules of the Double Your Opportunity Promotion</a>
                                  </span>
                            </label>
                        </div>
                    </div>
                    @if (!$coregs->isEmpty())
                        @foreach($coregs as $coreg)
                            @if($coreg->getDisplayPosition() == "coreg3a")
                                {!! $coreg->getHtml() !!}
                            @endif
                        @endforeach
                    @endif
                </div>
            @endif
        </div>

        <div class="button-wrapper">
            <div class="btn btn-lg compact btn-block btn-warning text-center">
                @php
                    $ctaText = isset($buttonText) ? $buttonText : (isset($contentSet) ? $contentSet->getRegisterCtaText() : 'Register for free!')
                @endphp
                <button class="register-btn-txt text-uppercase RegisterButton" id="btnRegister1" value=""
                        type="submit">{!! $ctaText !!}</button>
            </div>
            @if (Request::is('awards/you-deserve-it-scholarship'))
                <span>Existing user?
                <a href="#" id="btnLogin2">click here</a>
                </span>
            @endif
            
            @section('fb-checkbox')            
            @show

            <p>*Details and qualifications for participation in this promotion may apply</p>
        </div>

        <div class="clearfix checkboxes-below {{ (setting("register.checkbox.terms") == "yes")?"checkboxes":"" }}">
            @if (!$coregs->isEmpty())
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

            @if(setting("register.checkbox.call_visible") == "yes" && setting("register.checkbox.call") == "yes")
                @include("includes.call")
            @endif

            @if(setting("register.checkbox.terms") == "yes")
                <div class="formGroupContainer formGroupCheckbox col-xs-12">
                    <div class="form-group">
                        <div class="input-group">
                            <label>
                                <input type="checkbox" name="agree_terms" value="on" checked>
                                <span class="lbl padding-12 mod-checkbox">
                                  I agree with the <a href="/terms" target="_blank">Terms of Use</a> and <a
                                            href="/privacy" target="_blank">Privacy Policy</a>, the <a
                                            href="/terms#exhibit" target="_blank"> Official Rules of the You Deserve it Scholarship</a> and <a
                                            href="/promotion-rules" target="_blank">Official Rules of the Double Your Opportunity Promotion</a>
                              </span>
                            </label>
                        </div>
                    </div>
                </div>
            @endif
            @if (!$coregs->isEmpty())
                @foreach($coregs as $coreg)
                    @if($coreg->getDisplayPosition() == "coreg3")
                        <div class="formGroupContainer formGroupCheckbox col-xs-12 position-coreg3">
                            {!! $coreg->getHtml() !!}
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        <div class="value-proposition">
            <p>We provide you access to our proprietary scholarship management interface technology and services which
                help you apply to as many scholarships as possible in the least amount of time.</p>
        </div>

    </div>
</form>
