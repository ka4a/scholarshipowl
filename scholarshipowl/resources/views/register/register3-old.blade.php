@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle14') !!}

  @if(is_mobile())
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle15') !!}
  @endif
@endsection

@section("scripts")
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle11') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle12') !!}
@endsection

@section('scripts_custom')
    @if(Session::has("plugin.loan") && $user->overYearOld(18))
        <script type="text/javascript">
            window._register3_redirect_popup = true;
        </script>
    @endif
@endsection


@section('content')

<div class="register-wrapper center-block hidden-sm hidden-xs" id="registerSteps" >
	@include('register/steps')
</div>

	<div class="register-step3-header">
		<div class="container">
			<div class="row">
				<div class="center-block">
					{!! $contentSet->getRegister3Header() !!}
				</div>
			</div>
		</div>
	</div>

	<div id="registration-form-step-3" class="register-step3-form">
		<form id="registerForm3" class="ajax_form" method="POST" action="{{ url_builder('post-register3') }}"
        accept-charset="UTF-8" name="registerForm3" role="form" novalidate>
			{{ Form::token() }}

			<section role="region" aria-labelledby="registration-form-step-3">
				<div class="container">
                    @if(is_mobile())
                        <div id="vue-register3-form"></div>
                    @endif
					<div class="row">
						<div class="col-xs-12">
							<div class="row">
								<div class="form-wrapper center-block clearfix">
                                    @if(!is_mobile())
									<h3 class="sr-only" id="registration-form-step-3">Registration form - step 3</h3>
									<div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                                <div id="address" class="form-group">
                                                    <div class="input-group">
                                                        <label for="address1">Address</label>
                                                        <input type="text" name="address" value="{{@$session['address']}}" class="form-control" placeholder="Address" required >
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                                <div id="zipCode" class="form-group">
                                                    <div class="input-group">
                                                        <label for="zip">Zip code</label>
                                                        <input type="text" class="form-control" name="zip" pattern="[0-9]*" value="{{ @$session['zip'] }}" placeholder="Zip code" required >
                                                    </div>
                                                </div>
                                        </div>
									</div>
									<div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                                <div id="city" class="form-group">
                                                    <div class="input-group">
                                                        <label for="city">City</label>
                                                        <input type="text" name="city" value="{{@$session['city']}}" class="form-control" value="" placeholder="City" required >
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                                <div id="stateSelect" class="form-group">
                                                    <label for="state_id">State</label>
                                                    {{
                                                        Form::select('state_id', $options['states'], @$session['state_id'],
                                                        array('class' => 'selectpicker show-tick', 'data-size'=>'8', 'data-live-search'=>'true', 'data-width' => '100%', 'title' => 'Select state', 'required' => ''))
                                                    }}
                                                </div>
                                        </div>
									</div>
									<div class="row">
                                        <div class="col-xs-12 col-sm-6">
                                                <div id="password" class="form-group">
                                                    <div class="input-group">
                                                        <label for="password">Choose a password</label>
                                                        <input type="password" name="password" value="{{@$session['password']}}" class="form-control" placeholder="Password">
                                                    </div>
                                                </div>
                                        </div>
                                        <div class="col-xs-12 col-sm-6">
                                            <div id="confirmPassword" class="form-group">
                                                <div class="input-group">
                                                    <label for="confirmPassword">Confirm password</label>
                                                    <input type="password" class="form-control" name="confirmPassword" value="{{ @$session['confirmPassword'] }}" placeholder="Confirm password">
                                                </div>
                                            </div>
                                        </div>
									</div>
                                    @endif
                                    <div class="checkboxes checkbox-reg-3">
                                        @if (!$coregs->isEmpty())
                                            @foreach($coregs as $coreg)
                                                @if($coreg->getDisplayPosition() == "coreg6a")
                                                    <div class="formGroupContainer col-xs-12">
                                                        {!! $coreg->getHtml() !!}
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
                                    <div class="row checkboxes">
                                        @if (!$coregs->isEmpty())
                                            @foreach($coregs as $coreg)
                                                @if($coreg->getDisplayPosition() == "coreg5a")
                                                    <div class="formGroupContainer col-xs-12">
                                                        {!! $coreg->getHtml() !!}
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</section>

			<section role="region" aria-labelledby="continue">
		    <div id="continue" class="section--continue">
		        <div class="container-fluid continue">
		            <div class="row">
		                <div class="col-xs-12">
		                	<h3 class="sr-only" id="continue">Continue</h3>

											<div class="prevNext">
												<a id="previous" href="{{ url_builder('register2') }}" class="prevNextBtn text-right pull-left mod-pull-left">
													<span>
														Back
													</span>
													<div class="arrow-btn">
														<div class="arrow">
															<span class="a1"></span>
															<span class="a2"></span>
															<span class="a3"></span>
															<span class="a4"></span>
															<span class="a5"></span>
															<span class="a6"></span>
															<span class="a7"></span>
															<span class="a8"></span>
															<span class="a9"></span>
															<span class="a10"></span>
															<span class="a11"></span>
														</div>
													</div>
												</a>

		                    <div class="button-wrapper center-block btn-transition">
                          <button type="submit" class="Register3Button btn-arrow btn-arrow__old margin-center" id="btnRegister3">
                            <span class="btn__arrow"><i></i></span>
                            <span class="btn__loader"><i></i></span>
                            <span class="btn__text">continue</span>
                          </button>
		                    </div>

						</div>
                        <div class="checkboxes checkbox-reg-3">
                            @if (!$coregs->isEmpty())
                                @foreach($coregs as $coreg)
                                    @if($coreg->getDisplayPosition() == "coreg5")
                                        <div class="formGroupContainer col-xs-12">
                                            {!! $coreg->getHtml() !!}
                                        </div>
                                    @endif
                                @endforeach
                            @endif
                        </div>

		                </div>
		            </div>
		            <aside role="complementary" aria-labelledby="testimonial">
			            <div class="clearfix apply-testimonial">
			            	<h4 class="sr-only" id="testimonial">Testimonial</h4>
			                <div class="static-testimonial clearfix">
			                    <blockquote>
			                        <p>Over 70% of respondents rated the overall experience with ScholarshipOwl as amazing</p>
			                    </blockquote>
			                </div>
			            </div>
		            </aside>

		      	  </div>
		    	</div>
		    </section>

		</form>
	</div>
@include('includes/marketing/mixpanel_pageview')
@stop
