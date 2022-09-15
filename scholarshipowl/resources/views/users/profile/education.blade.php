<h4 class="sr-only sr-only-focusable">Education</h4>

<form action="post-education" method="post" class="ajax_from">
    {{ Form::token() }}

    <div class="form-group col-sm-6">
        <label for="school_level_id">School Level</label>
        {{ Form::select('school_level_id', $options['school_levels'], !is_null($user->getProfile()->getSchoolLevel())? $user->getProfile()->getSchoolLevel()->getId() : '', array('class' => 'selectpicker', 'data-width' => '100%', 'data-size'=>'8')) }}

        <small data-error="school_level_id" class="help-block" style="display: none;"></small>
    </div>

    <div class="form-group col-sm-6">
        <label>GPA (if applicable)</label>
        {{ Form::select('gpa', $options['gpas'], $user->getProfile()->getGpa(), array('class' => 'selectpicker', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}

        <small data-error="gpa" class="help-block" style="display: none;"></small>
    </div>

    @if(!$user->isUSA())
        <div class="form-group col-sm-12">
            <label for="study_country">Where do you want to study?</label>
            {{ Form::select('study_country[][id]', \App\Entity\Country::options([], false), $studyCountries, array('class' => 'select2', 'data-width' => '100%', 'multiple' => '', 'id' => 'study_country', 'data-maximum-selection-length' => 5)) }}
            <small data-error="study_country" class="help-block" style="display: none;"></small>
        </div>
    @endif
    <div class="clearfix"></div>
    <div id="enrolled" class="col-xs-12 col-sm-6">
        <div class="form-group">
            <label>Enrolled in College</label>

            <div class="checkboxes clearfix">
                <div class="pull-left chkbox">
                    <label>
                        <input id="enrolledYes" type="radio" value="1" name="enrolled" {{ set_checked($user->getProfile()->getEnrolled() === 1) }}/>
                            <span class="lbl padding-8">
                                <span class="lblClr">Yes</span>
                            </span>
                    </label>
                </div>
                <div class="pull-left chkbox noRight">
                    <label>
                        <input id="enrolledNo" type="radio" value="0" name="enrolled" {{ set_checked($user->getProfile()->getEnrolled() === 0) }}/>
                            <span class="lbl padding-8">
                                <span class="lblClr">No</span>
                            </span>
                    </label>
                </div>
            </div>

            <small data-error="enrolled" class="help-block" style="display: none;"></small>
        </div>
    </div>

    <div id="enrollmentDate" class="form-group col-sm-6">
        <label for="enrollment_date">College / University Enrollment Date</label>

        <div class="btn-group">
            <div id="month" class="btn-month pull-left">
                <label for="enrollment_month" class="sr-only">Month</label>
                {{ Form::select('enrollment_month', $options['enrollment_months'], $user->getProfile()->getEnrollmentMonth(), array('class' => 'selectpicker', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}
            </div>


            <div id="year" class="btn-year pull-right">
                <label for="enrollment_year" class="sr-only">Year</label>
                {{ Form::select('enrollment_year', $options['enrollment_years'], $user->getProfile()->getEnrollmentYear(), array('class' => 'selectpicker', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}
            </div>
        </div>

        <small data-error="enrollment_date" class="help-block" style="display: none;"></small>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-sm-6">
        <label>What type of degree you want ?</label>
        {{ Form::select('degree_type_id', $options['degree_types'], !is_null($user->getProfile()->getDegreeType())? $user->getProfile()->getDegreeType()->getId() : '', array('class' => 'selectpicker', 'data-width' => '100%')) }}

        <small data-error="degree_type_id" class="help-block" style="display: none;"></small>
    </div>

    <div class="form-group col-sm-6">
        <label>What you want to study (Major)</label>
        {{ Form::select('degree_id', $options['degrees'], !is_null($user->getProfile()->getDegree()) ? $user->getProfile()->getDegree()->getId() : '', array('class' => 'selectpicker', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}

        <small data-error="degree_id" class="help-block" style="display: none;"></small>
    </div>
    <div class="clearfix"></div>
    <div class="form-group col-sm-6">
        <label for="goal">High School</label>
        <select id="highSchoolPicker" name="highschool" style="width: 100%">
            <option value="{{ $user->getProfile()->getHighSchool() }}">{{ $user->getProfile()->getHighSchool() }}</option>
        </select>

        <div class="error"></div>
        <small data-error="highschool" class="help-block" style="display: none;"></small>
    </div>
    <div class="form-group col-sm-6">
        <label for="goal">College</label>
        <select id="collegePicker" name="university[]" style="width: 100%" multiple="true">
            @foreach($universities as $university)
                <option value="{{$university}}" selected="selected">{{$university}}</option>
            @endforeach
        </select>

        <small data-error="university[]" class="help-block" style="display: none;"></small>
    </div>
    <div class="clearfix"></div>

    <div class="form-group col-sm-6 adress">
        <label>High school address</label>
        {{ Form::text('highschool_address1', $user->getProfile()->getHighschoolAddress1(), ['class' => 'form-control']) }}
        {{ Form::text('highschool_address2', $user->getProfile()->getHighschoolAddress2(), ['class' => 'form-control']) }}
    </div>
    <div class="form-group col-sm-6 adress">
        <label>College/University address</label>
        {{ Form::text('university_address1', $user->getProfile()->getUniversityAddress1(), ['class' => 'form-control']) }}
        {{ Form::text('university_address2', $user->getProfile()->getUniversityAddress2(), ['class' => 'form-control']) }}
    </div>

    <div class="clearfix"></div>

    <div class="hidden" id="singleCollege">
        @if(count($universities) == 1)
            <option value="{{$universities[0]}}">{{$universities[0]}}</option>
        @else
            <option value="x">Start typing to select your college</option>
        @endif
    </div>

    <div id="graduationDate" class="form-group col-sm-6">
        <label for="month">Graduation Date</label>

        <div class="btn-group">
            <div class="btn-month pull-left clearfix">
                <div id="month" class="">
                    <label for="graduation_month" class="sr-only">Month</label>
                    {{ Form::select('graduation_month', $options['graduation_months'], $user->getProfile()->getGraduationMonth(), array('class' => 'selectpicker hs_grad_month', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}
                </div>
            </div>

            <div class="btn-year pull-right clearfix">
                <div id="year" class="">
                    <label for="graduation_year" class="sr-only">Year</label>
                    {{ Form::select('graduation_year', $options['graduation_years'], $user->getProfile()->getGraduationYear(), array('class' => 'selectpicker hs_grad_year', 'data-width' => '100%', 'data-size'=>'8', 'data-live-search'=>'true')) }}
                </div>
            </div>
        </div>

        <small data-error="graduation_date" class="help-block" style="display: none;"></small>
    </div>
    <div class="form-group col-sm-6 saveProfileChanges">
        <a class="btn btn-primary btn-block SaveProfile text-uppercase mod-user-profile-btn" href="#"
           data-toggle="modal" data-target="#saveModal">save changes</a>
    </div>
</form>
