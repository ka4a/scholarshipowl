@extends("base")

@section("styles")
  {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle34') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('select2') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('zuusa') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('register') !!}
@endsection

@section('content')
    @if(!empty($zuUsaText))
        <section role="region" aria-labelledby="zuusa-title">
            <div id="registered" class="blue-bg clearfix">
                <div class="container">
                    <div class="row">
                        <div class="text-container text-center text-white">
                            <h2 class="text-large text-light">
                                {!! $zuUsaText !!}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section role="region" aria-labelledby="zuusa-form" id="zuusa-form">
        <div class="zuusa-form clearfix">
            <div class="container">
                <form id="zuusaMediaForm{!! $formId !!}" name="zuusaMediaForm{!! $formId !!}"
                      action="{!! url_builder("post-zuusa") !!}" method="post" class="center-block clearfix ajax_form">

                    <div class="row">
                        {!! Form::token() !!}
                        {!! Form::hidden("form_id", $formId, ["id" => "form_id"]) !!}
                        {!! Form::hidden("xxCampaignId", $campaignId, ["id" => "campaign_id"]) !!}

                        <div class="form-wrapper center-block clearfix">
                            @if(isset($campaignSettings["cq1"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="cq1" class="label col-xs-12 col-sm-6">High School Graduation
                                            Year</label>
                                        {!! Form::selectRange("cq1", $campaignSettings["cq1"]["to"], $campaignSettings["cq1"]["from"], $campaignSettings["cq1"]["to"], ["class" => "selectpicker col-xs-12 col-sm-6"]) !!}
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["cq3"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="cq3" class="label col-xs-12 col-sm-6">Level of
                                            Education</label>
                                        <select name="cq3" id="cq3" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["cq3"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["grad_year"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="grad_year" class="label col-xs-12 col-sm-6">High School Graduation
                                            Year</label>
                                        {!! Form::selectRange("grad_year", $campaignSettings["grad_year"]["to"], $campaignSettings["grad_year"]["from"], $campaignSettings["grad_year"]["to"], ["class" => "selectpicker col-xs-12 col-sm-6"]) !!}
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["gradyear"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="gradyear" class="label col-xs-12 col-sm-6">High School Graduation
                                            Year</label>
                                        {!! Form::selectRange("gradyear", $campaignSettings["gradyear"]["to"], $campaignSettings["gradyear"]["from"], $campaignSettings["gradyear"]["to"], ["class" => "selectpicker col-xs-12 col-sm-6"]) !!}
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["hs_grad_year"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="hs_grad_year" class="label col-xs-12 col-sm-6">High School
                                            Graduation Year</label>
                                        {!! Form::selectRange("hs_grad_year", $campaignSettings["hs_grad_year"]["to"], $campaignSettings["hs_grad_year"]["from"], $campaignSettings["hs_grad_year"]["to"], ["class" => "selectpicker col-xs-12 col-sm-6"]) !!}
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["yearHSGED"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="yearHSGED" class="label col-xs-12 col-sm-6">Year of High School Graduation</label>
                                        {!! Form::selectRange("yearHSGED", $campaignSettings["yearHSGED"]["to"], $campaignSettings["yearHSGED"]["from"], $campaignSettings["yearHSGED"]["to"], ["class" => "selectpicker col-xs-12 col-sm-6"]) !!}
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["edu_completed"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="edu_completed" class="label col-xs-12 col-sm-6">Last Degree
                                            Completed</label>
                                        <select name="edu_completed" id="edu_completed"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["edu_completed"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["edu_level"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="edu_level" class="label col-xs-12 col-sm-6">Last Degree
                                            Completed</label>
                                        <select name="edu_level" id="edu_level"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["edu_level"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["edulevelid"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="edulevelid" class="label col-xs-12 col-sm-6">Last Degree
                                            Completed</label>
                                        <select name="edulevelid" id="edulevelid"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["edulevelid"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["EducationLevel"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="EducationLevel" class="label col-xs-12 col-sm-6">Last Degree
                                            Completed</label>
                                        <select name="EducationLevel" id="EducationLevel"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["EducationLevel"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["LevelOfEducation"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="LevelOfEducation" class="label col-xs-12 col-sm-6">Last Degree
                                            Completed</label>
                                        <select name="LevelOfEducation" id="LevelOfEducation"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["LevelOfEducation"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["educationCompleted"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="educationCompleted" class="label col-xs-12 col-sm-6">Last Degree
                                            Completed</label>
                                        <select name="educationCompleted" id="educationCompleted"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["educationCompleted"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($campuses))
                                @if(isset($campaignSettings["location_id"]))
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="location_id" class="label col-xs-12 col-sm-6">Desired
                                                Campus</label>
                                            <select name="location_id" id="location_id"
                                                    class="selectpicker col-xs-12 col-sm-6">
                                                @foreach($campuses as $campus)
                                                    <option value="{!! $campus->getSubmissionValue() !!}">{!! $campus->getDisplayValue() !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif(isset($campaignSettings["campus"]))
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="campus" class="label col-xs-12 col-sm-6">Desired Campus</label>
                                            <select name="campus" id="campus" class="selectpicker col-xs-12 col-sm-6">
                                                @foreach($campuses as $campus)
                                                    <option value="{!! $campus->getSubmissionValue() !!}">{!! $campus->getDisplayValue() !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    @elseif(isset($campaignSettings["remoteCampaignId"]))
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="remoteCampaignId" class="label col-xs-12 col-sm-6">Desired Campus</label>
                                            <select name="remoteCampaignId" id="campus" class="selectpicker col-xs-12 col-sm-6">
                                                @foreach($campuses as $campus)
                                                    <option value="{!! $campus->getSubmissionValue() !!}">{!! $campus->getDisplayValue() !!}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                @elseif(isset($campaignSettings["campus_hidden"]))
                                    <input type="hidden" name="campus" id="campus" value="{{ $campuses{0}->getSubmissionValue() }}">
                                @endif
                            @endif
                            @if(isset($campaignSettings["start_date"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="start_date" class="label col-xs-12 col-sm-6">When do you plan to
                                            start</label>
                                        <select name="start_date" id="start_date"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["start_date"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["start_time"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="start_date" class="label col-xs-12 col-sm-6">When do you plan to
                                            start</label>
                                        <select name="start_time" id="start_time"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["start_time"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["cq2"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="cq2" class="label col-xs-12 col-sm-6">When do you plan to
                                            start</label>
                                        <select name="cq2" id="cq2"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["cq2"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_rn"]))
                                @if($formId == 35 || $formId == 36 || $formId == 37 || $formId == 38)
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="custom_rn" class="label col-xs-12 col-sm-6">Do you possess a current
                                                license as a registered nurse?</label>
                                            <select name="custom_rn" id="custom_rn" class="selectpicker col-xs-12 col-sm-6">
                                                <option value="Y">Y</option>
                                                <option value="N">N</option>
                                            </select>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label for="custom_rn" class="label col-xs-12 col-sm-6">Do you possess a current
                                                license as a registered nurse?</label>
                                            <select name="custom_rn" id="custom_rn" class="selectpicker col-xs-12 col-sm-6">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>
                                @endif
                            @endif
                            @if(isset($campaignSettings["custom_lpn"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_lpn" class="label col-xs-12 col-sm-6">Are you currently a licensed practical nurse?</label>
                                        <select name="custom_lpn" id="custom_lpn" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_teacher"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_teacher" class="label col-xs-12 col-sm-6">Do you have an active teaching license?</label>
                                        <select name="custom_teacher" id="custom_teacher" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["rn_license"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="rn_license" class="label col-xs-12 col-sm-6">Do you possess a
                                            current license as a registered nurse</label>
                                        <select name="rn_license" id="rn_license"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["bs_nursing"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="bs_nursing" class="label col-xs-12 col-sm-6">Do you have a Bachelor
                                            of Science in Nursing</label>
                                        <select name="bs_nursing" id="bs_nursing"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_emt"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_emt" class="label col-xs-12 col-sm-6">	Do you have an active EMT License?</label>
                                        <select name="custom_emt" id="custom_emt"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_assoc"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_assoc" class="label col-xs-12 col-sm-6">Do you have a minimum of an Associate's degree?</label>
                                        <select name="custom_assoc" id="custom_assoc" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["textmessage"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="textmessage" class="label col-xs-12 col-sm-6">Receive text via phone (Standard rate may apply)?</label>
                                        <select name="textmessage" id="textmessage"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Y">Yes</option>
                                            <option value="N">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_computer"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_computer" class="label col-xs-12 col-sm-6">Do you own a
                                            personal computer with unrestricted, reliable access</label>
                                        <select name="custom_computer" id="custom_computer"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_internet"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_internet" class="label col-xs-12 col-sm-6">Do you have a computer with internet?</label>
                                        <select name="custom_internet" id="custom_internet"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_financial_aid"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_financial_aid" class="label col-xs-12 col-sm-6">Have you ever defaulted on a federally-funded student loan?</label>
                                        <select name="custom_financial_aid" id="custom_financial_aid"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["phone1_type"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="phone1_type" class="label col-xs-12 col-sm-6">Phone Type</label>
                                        <select name="phone1_type" id="phone1_type"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Home">Home</option>
                                            <option value="Work">Work</option>
                                            <option value="Cell">Cell</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["phone2"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="phone2" class="label col-xs-12 col-sm-6">Phone2</label>
                                        <div class="col-xs-12 col-sm-6">
                                            <input type="text" name="phone2" id="phone2"
                                                   class="form-control">
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="program_id" class="label col-xs-12 col-sm-6">Program of interest</label>
                                    <select name="program_id" id="program_id" class="selectpicker col-xs-12 col-sm-6">
                                        <option value="">Select other parameters</option>
                                    </select>
                                </div>
                            </div>
                            @if(isset($campaignSettings["timeframe"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="timeframe" class="label col-xs-12 col-sm-6">When do you plan to
                                            start</label>
                                        <select name="timeframe" id="timeframe" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["timeframe"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["militarystatus"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="militarystatus" class="label col-xs-12 col-sm-6">Military Status</label>
                                        <select name="militarystatus" id="militarystatus" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["militarystatus"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_aoi"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_aoi" class="label col-xs-12 col-sm-6">Area of Interest</label>
                                        <select name="custom_aoi" id="custom_aoi" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["custom_aoi"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["how_dedicated"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="how_dedicated" class="label col-xs-12 col-sm-6">How dedicated are
                                            you to continuing your education</label>
                                        <select name="how_dedicated" id="how_dedicated"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["how_dedicated"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["enroll_percentage"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="enroll_percentage" class="label col-xs-12 col-sm-6">How likely are
                                            you to enroll in a degree program within 6 months</label>
                                        <select name="enroll_percentage" id="enroll_percentage"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["enroll_percentage"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["contact_time"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="contact_time" class="label col-xs-12 col-sm-6">Best Time to
                                            Call</label>
                                        <select name="contact_time" id="contact_time"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["contact_time"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["besttime"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="besttime" class="label col-xs-12 col-sm-6">Best Time to
                                            Call</label>
                                        <select name="besttime" id="besttime"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["besttime"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["military_funding"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="military_funding" class="label col-xs-12 col-sm-6">Do you plan on
                                            using Military and/or Veteran benefits</label>
                                        <select name="military_funding" id="military_funding"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["military_affiliated"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="military_affiliated" class="label col-xs-12 col-sm-6">Are you
                                            affiliated with the military</label>
                                        <select name="military_affiliated" id="military_affiliated"
                                                class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["consent"]))
                                <div class="formGroupContainer form-group col-xs-12">
                                    <label for="consent" class="label">
                                        <input type="checkbox" name="consent" id="confirm_consent" value="Yes"
                                               class="agree_terms"/>
                                        <span class="lbl padding-12 mod-checkbox">{!! $campaignSettings["consent"] !!}</span>
                                    </label>
                                </div>
                            @endif

                            @if(isset($campaignSettings["uleadid"]))
                                <input id="leadid_token" name="universal_leadid" type="hidden" value=""/>

                                <script id="LeadiDscript" type="text/javascript">
                                    // <!--
                                    (function () {
                                        var s = document.createElement('script');
                                        s.id = 'LeadiDscript_campaign';
                                        s.type = 'text/javascript';
                                        s.async = true;
                                        s.src = '//create.lidstatic.com/campaign/fe5f409d-feed-beef-cafe-5b7be103dea1.js?snippet_version=2';
                                        var LeadiDscript = document.getElementById('LeadiDscript');
                                        LeadiDscript.parentNode.insertBefore(s, LeadiDscript);
                                    })();
                                    // -->
                                </script>
                                <noscript><img
                                            src='//create.leadid.com/noscript.gif?lac=fe5f409d-bc58-55b7-582b-5b7be103dea1&lck=fe5f409d-feed-beef-cafe-5b7be103dea1&snippet_version=2'/>
                                </noscript>
                            @endif
                        </div>

                        <div class="button-wrapper">
                            <div class="btn btn-lg compact btn-block btn-warning text-center">
                                <button class="register-btn-txt text-uppercase DaneMediaButton" id="btnDaneMedia"
                                        value="" type="submit">Submit
                                </button>
                            </div>
                            <a href="{!! url_builder($isMobile?setting("register.redirect_page_mobile"):setting("register.redirect_page")) !!}"
                               class="btn btn-gray btn-block center-block text-uppercase mod-user-profile-btn">
                                Skip
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
