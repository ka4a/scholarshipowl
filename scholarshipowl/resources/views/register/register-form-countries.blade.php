<form id="registerForm1" name="registerForm1" action="{{ url_builder('post-register-new') }}" method="post"
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
                        <input type="hidden" name="country_code" data-validation="country_code" />
                        <input type="hidden" name="phone" />
                        <input id="phone" value="{{ @$session['phone'] }}" class="form-control" type="tel" required
                               data-input-id="Phone"
                               data-validation="phone"
                               data-inputmask-regex="\+[0-9]{6,20}"
                        />
                    </div>
                </div>
            </div>

            <div id="study-country-container" style="display: none;" class="formGroupContainer col-xs-12 col-sm-12 col-md-12">
                <div class="study-country">
                    <div class="form-group">
                        <div class="input-group">
                        <h5 class="study-country__title">Plan to study in the US?</h5>
                            <div class="study-country__switch">
                               <label class="switch">
                                 Yes
                                 <input type="checkbox" checked name="want_to_study">
                                 <span class="switch__lever"></span>
                                 No
                               </label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group study-country__list" style="display: none">
                        <div class="input-group">
                            <label for="study_country">Where do you want to study?</label>
                            <select id="study_country" data-validation="study_country" data-maximum-selection-length="5" style="width: 100%" name="study_country[][id]" multiple="multiple">
                                @foreach(\App\Entity\Country::options([], false) as $id => $name)
                                    <option value="{{ $id }}">{{ $name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="formGroupContainer col-xs-12 col-sm-12 col-md-12">

            </div>
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

        <div class="button-wrapper">
            <div class="btn btn-lg compact btn-block btn-warning text-center">
                <button class="register-btn-txt text-uppercase RegisterButton" id="btnRegister1" value=""
                        type="submit">{{ isset($buttonText) ? $buttonText : 'Register for free' }}</button>
            </div>
            @if (Request::is('awards/you-deserve-it-scholarship'))
                <span>Existing user?
                <a href="#" id="btnLogin2" value="" data-toggle="modal" data-target="#LoginFormModal">click here</a>
                </span>
            @endif
            @section('fb-checkbox')
            @show
            <p>*Details and qualifications for participation in this promotion may apply</p>
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

        <div class="value-proposition">
            <p>We provide you access to our proprietary scholarship management interface technology and services which help you apply to as many scholarships as possible in the least amount of time.</p>
        </div>

    </div>
</form>
