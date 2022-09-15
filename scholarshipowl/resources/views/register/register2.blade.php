@extends('base')

@section("styles")
  {!! HTML::style("https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css") !!}
  {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle17') !!}
@endsection

@section("scripts")
  {!! \App\Extensions\AssetsHelper::getJSBundle('bundle14') !!}
@endsection


@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle15') !!}
@endsection

      @section('content')


      @if ($offerId == "30")
      <?php
      $entityAccount = \EntityManager::findById(\App\Entity\Account::class, $user->getAccountId());
      \HasOffers::info(
        "HasOffers pixel: ".
        "URL: ".Request::path()."; ".
        "Account details: ".print_r(logHasoffersAccount($entityAccount), true)."; ".
        "TransactionId: ".$marketingSystemAccount->getHasOffersTransactionId()."; ".
        "AffiliateId: ".$marketingSystemAccount->getHasOffersAffiliateId()."; ".
        "GoalId: 16"
      );
      ?>
      <img src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=16&transaction_id={{$marketingSystemAccount->getHasOffersTransactionId()}}" width="1" height="1" />
      @endif

      @if ($offerId == "32")
      <?php
      $entityAccount = \EntityManager::findById(\App\Entity\Account::class, $user->getAccountId());
      \HasOffers::info(
        "HasOffers iframe: ".
        "URL: ".Request::path()."; ".
        "Account details: ".print_r(logHasoffersAccount($entityAccount), true)."; ".
        "TransactionId: ".$marketingSystemAccount->getHasOffersTransactionId()."; ".
        "AffiliateId: ".$marketingSystemAccount->getHasOffersAffiliateId()."; ".
        "GoalId: 22"
      );
      ?>
      <iframe src="https://scholarship.go2cloud.org/aff_goal?a=l&goal_id=22&transaction_id={{$marketingSystemAccount->getHasOffersTransactionId()}}" scrolling="no" frameborder="0" width="1" height="1"></iframe>
      @endif

      @if(is_mobile())
        <div id="vue-register2-form"></div>
      @else
      <section id="registerSteps" class="register-wrapper center-block hidden-sm hidden-xs">
        @include('register/steps')
      </section>

      <section id="registration-form-step-2" class="register-step2-form">
        <form id="registerForm2" method="POST" action="{{ url_builder('post-register2') }}" accept-charset="UTF-8" name="registerForm2">
          {{ Form::token() }}
          {{ Form::hidden("_return", url_builder("register3")) }}

          <div class="container">
            <div class="row">
              <div class="formRegister2 clearfix">
                <div class="col-md-8 col-lg-8 push-left padBot40 clearfix">
                  <div class="reg-step2-wrapper center-block">
                    <div id="profileType" class="profile-type-wrapper ">
                      <div class="form-group">
                        <label for="profileType">I am a </label>
                        {{
                          Form::select('profile_type', $options['profile_type'], @$session['profile_type'],
                          ['class' => 'student_or_parent',
                          'data-width' => '100%',
                          'data-type' => @$session['profile_type'] ?
                          @$session['profile_type'] : \App\Entity\Profile::PROFILE_TYPE_STUDENT
                          ])
                        }}
                      </div>
                    </div>
                    <div class="edu-level-wrapper">
                      <div class="form-group">
                        <div id="eduLevel" class="form-group">
                          <label for="goal">What is your level of education?</label>
                          {{
                            Form::select('school_level_id', $options['school_levels'], @$session['school_level_id'],
                            array('class' => 'selectpicker', 'data-width' => '100%', 'title' => 'What is your education level ?', 'required' => ''))
                          }}
                          <div class="error"></div>
                        </div>
                      </div>
                    </div>
                    <div id="gender" class="gender-wrapper ">
                      <div class="form-group">
                        <label>Gender</label>
                        <div class="checkboxes clearfix">
                          @foreach($options['genders'] as $key => $label)
                          <div class="pull-left chkbox">
                            <label>
                              <input id="{{ $key }}" type="radio" value="{{ $key }}" name="gender" {{ @$session['gender'] == $key ? 'checked' : '' }} />
                              <span class="lbl padding-8">
                                <span class="lblClr">{{ $label }}</span>
                              </span>
                            </label>
                          </div>
                          @endforeach
                        </div>
                      </div>
                    </div>
                    <div id="date_of_birth" class="birth-wrapper ">
                      <div class="form-group">
                        <label for="dob">Date of birth</label>

                        <div id="birth-date" class="text-left ">
                          <!-- Select month -->
                          <label class="sr-only">Month</label>
                          {{
                            Form::select('birthday_month', $options['birthday_months'], @$session['birthday_month'],
                            array('class'=>'selectpicker pull-left', 'data-width' => '30%', 'data-size'=>'8', 'data-live-search'=>'true', 'title' => 'Month', 'required' => ''))
                          }}

                          <!-- Select date -->
                          <label class="sr-only">Day</label>
                          {{
                            Form::select('birthday_day', $options['birthday_days'], @$session['birthday_day'],
                            array('class'=>'selectpicker pull-left','data-width' => '30%', 'data-size'=>'8', 'data-live-search'=>'true', 'title' => 'Day', 'required' => ''))
                          }}


                          <!-- Select date -->
                          <label class="sr-only">Year</label>
                          {{
                            Form::select('birthday_year', $options['birthday_years'], @$session['birthday_year'],
                            array('class'=>'selectpicker pull-left', 'data-width' => '40%', 'data-size'=>'8', 'data-live-search'=>'true', 'title' => 'Year', 'required' => ''))
                          }}
                        </div>
                      </div>
                    </div>

                    <div id="ethnicBackground" class="ethnicity-wrapper ">
                      <div class="form-group">
                        <label for="ethnicity">Ethnic background</label>
                        {{
                          Form::select('ethnicity_id', $options['ethnicities'], @$session['ethnicity_id'], array('class' =>"selectpicker", 'data-width' => '100%', 'title' => 'Ethnic background'))
                        }}
                      </div>
                    </div>

                    <div id="Citizenship" class="citizenship-wrapper ">
                      <div class="form-group">
                        <label for="citizenship" >Citizenship</label>
                        {{
                          Form::select('citizenship_id', $options['citizenships'], @$session['citizenship_id'], array('class' =>"selectpicker", 'data-width' => '100%', 'title' => 'Citizenship', 'data-live-search' => true))
                        }}
                      </div>
                    </div>
                    <div class="high-school-wrapper ">
                      <div class="form-group">
                        <div class="form-group">
                          <label for="goal">High School</label>
                          <select id="highSchoolPicker" name="highschool" style="width: 100%">
                            <option value="x">--- Select ---</option>
                          </select>
                          <div class="error"></div>
                        </div>
                      </div>
                    </div>
                    <div id="enrolled" class="enrolled-wrapper ">
                      <div class="form-group">
                        <label>Enrolled in College</label>
                        <div class="checkboxes clearfix">
                          <div class="pull-left chkbox">
                            <label>
                              <input id="enrolledYes" type="radio" value="1" name="enrolled" {{ set_checked(isset($session['enrolled']) && $session['enrolled'])  }} />
                              <span class="lbl padding-8">
                                <span class="lblClr">Yes</span>
                              </span>
                            </label>
                          </div>
                          <div class="pull-left chkbox noRight">
                            <label>
                              <input id="enrolledNo" type="radio" value="0" name="enrolled" {{ set_checked(isset($session['enrolled']) && !$session['enrolled']) }}/>
                              <span class="lbl padding-8">
                                <span class="lblClr">No</span>
                              </span>
                            </label>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="enrollmentDate" class="enrollment-date-wrapper  ">
                      <div class="form-group">
                        <label for="enrollment_date">College / University Enrollment Date</label>
                        <div id="month" class="btn-month pull-left">
                          <label for="month" class="sr-only">Month</label>
                          {{
                            Form::select('enrollment_month', $options['enrollment_months'], @$session['enrollment_month'], array('class' =>"selectpicker", 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true', 'title' => 'Month'))
                          }}
                        </div>
                        <div id="year" class="btn-year pull-right">
                          <label for="year" class="sr-only">Year</label>
                          {{
                            Form::select('enrollment_year', $options['enrollment_years'], @$session['enrollment_year'], array('class' =>"selectpicker", 'name'  => 'enrollment_year', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true', 'Title' => 'Year'))
                          }}
                        </div>
                      </div>
                    </div>
                    <div class="college-wrapper ">
                      <div class="form-group">
                        <label for="goal">College</label>
                        <select id="collegePicker" name="university[]" style="width: 100%">
                          <option value="x">--- Select ---</option>
                        </select>
                        <div class="error"></div>
                      </div>
                    </div>
                    <div class="gpa-wrapper">
                      <div class="form-group">
                        <label for="gpa">GPA</label>
                        {{
                          Form::select('gpa', $options['gpas'], @$session['gpa'], array('class' =>"selectpicker", 'data-size'=>'8', 'data-live-search'=>'true', 'data-width' => '100%', 'title' => 'GPA'))
                        }}
                      </div>
                    </div>
                    <div id="graduationDate" class="graduation-date-wrapper ">
                      <div class="form-group">
                        <label for="month">Graduation Date</label>

                        <div id="month" class="btn-month pull-left">
                          <label for="graduation_month" class="sr-only">Month</label>
                          {{ Form::select('graduation_month', $options['graduation_months'], @$session['graduation_month'], array('class' => 'selectpicker hs_grad_month', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}
                        </div>

                        <div id="year" class="btn-year pull-right">
                          <label for="graduation_year" class="sr-only">Year</label>
                          {{ Form::select('graduation_year', $options['graduation_years'], @$session['graduation_year'], array('class' => 'selectpicker hs_grad_year', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}
                        </div>
                      </div>
                    </div>

                    <div id="major" class="major-wrapper ">
                      <div class="form-group">
                        <label for="major">What do you want to study (Major)?</label>
                        {{
                          Form::select('degree_id', $options['degrees'], @$session['degree_id'], array('class' =>"selectpicker", 'data-live-search' => 'true', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true', 'title' => 'What you want to study (Major) ?'))
                        }}
                      </div>
                    </div>

                    <div id="Degree" class="degree-wrapper ">
                      <div class="form-group">
                        <label for="degree">What type of degree do you want?</label>
                        {{
                          Form::select('degree_type_id', $options['degree_types'], @$session['degree_type_id'], array('class' =>"selectpicker", 'data-width' => '100%', 'title' => 'Select one'))
                        }}
                      </div>
                    </div>

                    <div id="careerGoal" class="career-goal-wrapper ">
                      <div class="form-group">
                        <label for="goal">What is your career goal?</label>
                        {{
                          Form::select('career_goal_id', $options['career_goals'], @$session['career_goal_id'], array('class' =>"selectpicker",'data-width' => '100%', 'title' => 'What is your career goal?'))
                        }}
                      </div>
                    </div>
                    <div id="interested" class="study-online-wrapper ">
                      <div class="form-group">
                        <label>Are you interested in studying online?</label>
                        <div class="checkboxes clearfix">

                          @foreach($options['study_online'] as $key => $label)
                          <div class="pull-left chkbox">
                            <label>
                              <input id="{{ $key }}" type="radio" value="{{ $key }}" name="study_online" {{ @$session['study_online'] == $key ? 'checked' : '' }} />
                              <span class="lbl padding-8">
                                <span class="lblClr">{{ $label }}</span>
                              </span>
                            </label>
                          </div>
                          @endforeach
                        </div>
                      </div>
                    </div>

                    <div id="militaryAffiliation" class="military-affiliation-wrapper ">
                      <div class="form-group">
                        <div class="form-group">
                          <label for="goal">Military affiliation</label>
                          {{
                            Form::select('military_affiliation_id', $options['military_affiliations'], @$session['military_affiliation_id'], array('class' =>"selectpicker", "data-width" => "100%", "data-live-search" => "true"))
                          }}
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-md-3 col-md-offset-1 col-lg-3 col-lg-offset-1 push-right hidden-xs">

                  <div class="row">

                    <div class="upsale-wrapper clearfix center-block">
                      <div class="col-sm-6 col-md-12">
                        <div class="row">
                          <div class="upsale pull-left">
                            <div class="bars">
                              <div class="title">What you Gain</div>
                              <div class="text1">
                                <strong>Save time applying for financial aid and let ScholarshipOwl do the work for you.</strong>
                                <span class="text1-a">Complete the next <i>two</i> steps to enter the <strong>You Deserve It</strong> scholarship. <i>ScholarshipOwl also offers a paid premium service</i>.</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-6 col-md-12">
                        <div class="row">
                          <div class="upsale pull-right">
                            <div class="bars">
                              <div class="title">Whats Next?</div>
                              <div class="text1">
                                <strong>Take full advantage of ScholarshipOwl's application engine.</strong>
                                <span class="text1-a">Finish registering and you will be eligible for the <strong>Double Your Scholarship</strong> grant. We match the value of scholarships you are awarded through our website.</span>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>

                      <div class="col-sm-6 col-md-12">
                        <div class="row">
                          <div class="upsale pull-right">
                            <div class="bars testimonial-bar">
                              <div class="text1">
                                <div class="user2 testimonials-images"></div>
                                <ul>
                                  <li>How was Your overall experience with ScholarshipOwl?</li>
                                  <li>I love it, thanks guys</li>
                                </ul>
                              </div>
                              <div class="user">
                                Emily C.
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>

                  </div>
                </div>
              </div>
            </div>

            <div class="sub hidden-xs">
              ScholarshipOwl keeps your personal details confidential and only collects information required to find scholarships and process applications.
            </div>

          </div>


          <div id="continue" class="section--continue">
            <div class="container-fluid continue">
              <div class="row">
                <div class="col-xs-12">
                  <div class="button-wrapper center-block btn-transition">
                    <button type="submit" class="Register2Button btn-arrow btn-arrow__old margin-center" id="btnRegister2">
                      <span class="btn__arrow"><i></i></span>
                      <span class="btn__loader"><i></i></span>
                      <span class="btn__text">{!! $contentSet->getRegister2CtaText() !!}</span>
                    </button>
                  </div>
                </div>
              </div>
              <div class="clearfix apply-testimonial">
                <div class="static-testimonial clearfix">
                  <blockquote>
                    <p>A staggering 93% of users would definitely recommend ScholarshipOwl to their friends!</p>
                  </blockquote>
                </div>
              </div>

            </div>
          </div>

        </form>
        <div class="upsale-wrapper clearfix center-block visible-xs-block">
          <div class="upsale">
            <div class="bars">
              <div class="title">What you gain</div>
              <div class="text1">
                <strong>Save time applying for financial aid and let ScholarshipOwl do the work for you.</strong>
                <span class="text1-a">Complete the next <i>two</i> steps to enter the <strong>You Deserve It</strong> scholarship. <i>ScholarshipOwl also offers a paid premium service</i>.</span>
              </div>
            </div>
          </div>
          <div class="upsale">
            <div class="bars">
              <div class="title">Whats Next?</div>
              <div class="text1">
                <strong>Take full advantage of ScholarshipOwl's application engine.</strong>
                <span class="text1-a">Finish registering and you will be eligible for the <strong>Double Your Scholarship</strong> grant. We match the value of scholarships you are awarded through our website.</span>
              </div>
            </div>
          </div>
          <div class="upsale">
            <div class="bars testimonial-bar">
              <div class="text1">
                <div class="user2 testimonials-images"></div>
                <ul>
                  <li>How was Your overall experience with ScholarshipOwl?</li>
                  <li>I love it, thanks guys</li>
                </ul>
              </div>
              <div class="user">
                Emily C.
              </div>
            </div>
          </div>
        </div>
        <div class="sub visible-xs-block">
          ScholarshipOwl keeps your details confidential and will always only use information asked for by the scholarship providers.
        </div>
      </section>
      @endif
      @include('includes/marketing/mixpanel_pageview')
      @stop
