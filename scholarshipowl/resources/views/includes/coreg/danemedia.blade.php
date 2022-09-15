@extends("base")

@section("styles")
  {!! \App\Extensions\AssetsHelper::getCSSBundle('mainStyle') !!}
  {!! \App\Extensions\AssetsHelper::getCSSBundle('testimonialsCarousel') !!}
  {!! \App\Extensions\AssetsHelper::getCSSBundle('select2') !!}
  {!! \App\Extensions\AssetsHelper::getCSSBundle('danemedia') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('register') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('danemedia') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('select2') !!}
@endsection

@section('content')
    @if(!empty($daneMediaText))
        <section role="region" aria-labelledby="dane-title">
            <div id="registered" class="blue-bg clearfix">
                <div class="container">
                    <div class="row">
                        <div class="text-container text-center text-white">
                            <h2 class="text-large text-light">
                                {!! $daneMediaText !!}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif
    <section role="region" aria-labelledby="dane-media-form" id="dane-media-form">
        <div class="dane-media-form clearfix">
            <div class="container">
                <form id="daneMediaForm{!! $formId !!}" name="daneMediaForm{!! $formId !!}" action="{!! url_builder("post-dane-media") !!}" method="post" class="center-block clearfix ajax_form">

                    <div class="row">
                        {!! Form::token() !!}
                        {!! Form::hidden("form_id", $formId, ["id" => "form_id"]) !!}
                        {!! Form::hidden("xxCampaignId", $campaignId, ["id" => "campaign_id"]) !!}

                        <div class="form-wrapper center-block clearfix">
                            @if(isset($campaignSettings["hsgradyr"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="hsgradyr" class="label col-xs-12 col-sm-6">High School Graduation Year</label>
                                        {!! Form::selectRange("hsgradyr", $campaignSettings["hsgradyr"]["to"], $campaignSettings["hsgradyr"]["from"], $campaignSettings["hsgradyr"]["to"], ["class" => "selectpicker col-xs-12 col-sm-6"]) !!}
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["edulevelid"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="edulevelid" class="label col-xs-12 col-sm-6">Last Degree Completed</label>
                                        <select name="edulevelid" id="edulevelid" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["edulevelid"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(!empty($campuses))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="campusid" class="label col-xs-12 col-sm-6">Desired Campus</label>
                                        <select name="campusid" id="campus" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campuses as $campus)
                                                <option value="{!! $campus->getSubmissionValue() !!}">{!! $campus->getDisplayValue() !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["start_date"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="start_date" class="label col-xs-12 col-sm-6">When do you plan to start</label>
                                        <select name="start_date" id="start_date" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["start_date"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_rn"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_rn" class="label col-xs-12 col-sm-6">Do you possess a current license as a registered nurse</label>
                                        <select name="custom_rn" id="custom_rn" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["bs_nursing"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="bs_nursing" class="label col-xs-12 col-sm-6">Do you have a Bachelor of Science in Nursing</label>
                                        <select name="bs_nursing" id="bs_nursing" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="1">Yes</option>
                                            <option value="0">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_computer"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_computer" class="label col-xs-12 col-sm-6">Do you own a personal computer with unrestricted, reliable access</label>
                                        <select name="custom_computer" id="custom_computer" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["phone_type"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="phone_type" class="label col-xs-12 col-sm-6">Phone Type</label>
                                        <select name="phone_type" id="phone_type" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["phone_type"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label for="programid" class="label col-xs-12 col-sm-6">Program of interest</label>
                                    <select name="programid" id="program" class="selectpicker col-xs-12 col-sm-6">
                                        <option value="">Select other parameters</option>
                                    </select>
                                </div>
                            </div>
                            @if(isset($campaignSettings["timeframe"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="timeframe" class="label col-xs-12 col-sm-6">When do you plan to start</label>
                                        <select name="timeframe" id="timeframe" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["timeframe"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["how_dedicated"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="how_dedicated" class="label col-xs-12 col-sm-6">How dedicated are you to continuing your education</label>
                                        <select name="how_dedicated" id="how_dedicated" class="selectpicker col-xs-12 col-sm-6">
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
                                        <label for="enroll_percentage" class="label col-xs-12 col-sm-6">How likely are you to enroll in a degree program within 6 months</label>
                                        <select name="enroll_percentage" id="enroll_percentage" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["enroll_percentage"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["besttime"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="besttime" class="label col-xs-12 col-sm-6">Best Time to Call</label>
                                        <select name="besttime" id="besttime" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["besttime"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["motivation"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="motivation" class="label col-xs-12 col-sm-6">Why do you want to attend college</label>
                                        <select name="motivation" id="motivation" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["motivation"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["attend_class"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="attend_class" class="label col-xs-12 col-sm-6">Do you want to attend classes online and have access to a computer with a reliable internet connection? (*REQUIRED TO QUALIFY*)</label>
                                        <select name="attend_class" id="attend_class" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["military_funding"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="military_funding" class="label col-xs-12 col-sm-6">Do you plan on using Military and/or Veteran benefits</label>
                                        <select name="military_funding" id="military_funding" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["military_affiliated"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="military_affiliated" class="label col-xs-12 col-sm-6">Are you affiliated with the military</label>
                                        <select name="military_affiliated" id="military_affiliated" class="selectpicker col-xs-12 col-sm-6">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_contact"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_contact" class="label col-xs-12 col-sm-6">How do you prefer to be contacted?</label>
                                        <select name="custom_contact" id="custom_contact" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["custom_contact"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            @if(isset($campaignSettings["custom_receive_text"]))
                                <div class="col-xs-12">
                                    <div class="form-group">
                                        <label for="custom_receive_text" class="label col-xs-12 col-sm-6">Can you receive text via your phone?</label>
                                        <select name="custom_receive_text" id="custom_receive_text" class="selectpicker col-xs-12 col-sm-6">
                                            @foreach($campaignSettings["custom_receive_text"] as $submission => $display)
                                                <option value="{!! $submission !!}">{!! $display !!}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif


                            @if(isset($campaignSettings["consent"]))
                                <div class="formGroupContainer form-group col-xs-12">
                                    <label for="consent" class="label">
                                        <input type="checkbox" name="consent" id="confirm_consent" value="Yes" class="agree_terms" />
                                        <span class="lbl padding-12 mod-checkbox">{!! $campaignSettings["consent"] !!}</span>
                                    </label>
                                </div>
                            @endif

                            @if(isset($campaignSettings["uleadid"]))
                                <input id="leadid_token" name="uleadid" type="hidden" value=""/>

                                <script id="LeadiDscript" type="text/javascript">
                                    // <!--
                                    (function() {
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
                                <noscript><img src='//create.leadid.com/noscript.gif?lac=fe5f409d-bc58-55b7-582b-5b7be103dea1&lck=fe5f409d-feed-beef-cafe-5b7be103dea1&snippet_version=2' /></noscript>
                            @endif
                        </div>

                        <div class="button-wrapper">
                            <div class="btn btn-lg compact btn-block btn-warning text-center">
                                <button class="register-btn-txt text-uppercase DaneMediaButton" id="btnDaneMedia" value="" type="submit">Submit</button>
                            </div>
                            <a href="{!! url_builder($isMobile?setting("register.redirect_page_mobile"):setting("register.redirect_page")) !!}" class="btn btn-gray btn-block center-block text-uppercase mod-user-profile-btn">
                                Skip
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
