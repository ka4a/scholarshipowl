@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    {{ (isset($contentSet) ? 'Edit ' . $contentSet->getName() : 'Create content set') }}
                </div>
            </div>
            <div class="box-content">
                {{ Form::open(['route' => ['admin::features.content_sets.edit', isset($contentSet) ? $contentSet->getId() : null], 'files' => true, 'class' => 'form-horizontal']) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Name</label>
                        <div class="col-xs-6">
                            {{ Form::text('name', isset($contentSet) ? $contentSet->getName() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <hr/>
                    <h3>Home page</h3>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Page header</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('homepage_header', isset($contentSet) ? $contentSet->getHomepageHeader() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Hide Double Promotion bubble</label>
                        <div class="col-xs-6">
                            {{ Form::hidden('hp_double_promotion_flag', 0) }}
                            {{ Form::checkbox('hp_double_promotion_flag', 1, isset($contentSet) ? $contentSet->isHpDoublePromotionFlag() : false, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Hide YDI scholarship badge</label>
                        <div class="col-xs-6">
                            {{ Form::hidden('hp_ydi_flag', 0) }}
                            {{ Form::checkbox('hp_ydi_flag', 1, isset($contentSet) ? $contentSet->isHpYdiFlag() : false, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Home page CTA Text</label>
                        <div class="col-xs-6">
                            {{ Form::text('hp_cta_text', isset($contentSet) ? $contentSet->getHpCtaText() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <hr/>
                    <h3>Register page</h3>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Page header</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('register_header', isset($contentSet) ? $contentSet->getRegisterHeader() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>



                    {{ Form::hidden('register_hide_footer', 0) }}

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Select button text</label>
                        <div class="col-xs-6">
                            {{ Form::text('select_apply_now', isset($contentSet) ? $contentSet->getSelectApplyNow() : 'apply now', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Select hide checkboxes after register3</label>
                        <div class="col-xs-6">
                            {{ Form::hidden('select_hide_checkboxes', 0) }}
                            {{ Form::checkbox('select_hide_checkboxes', 1, isset($contentSet) ? $contentSet->isSelectHideCheckboxes() : false, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Heading text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('register_heading_text', isset($contentSet) ? $contentSet->getRegisterHeadingText() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label" id="riverroad2">Subheading text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('register_subheading_text', isset($contentSet) ? $contentSet->getRegisterSubheadingText() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">CTA</label>
                        <div class="col-xs-6">
                            {{ Form::text('register_cta_text', isset($contentSet) ? $contentSet->getRegisterCtaText() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Illustration</label>
                        <div class="col-xs-6 illustration-container">
                            {{ Form::file('register_illustration') }}
                            @if (isset($contentSet) && $image = $contentSet->getRegisterIllustration())
                                <img style="margin-bottom: 10px; margin-top: 10px;" class="illustration-img" width="550" src="{{  $image }}" />
                                <br>
                                <input class="register_illustration-flag hidden" name="register_illustration-remove" type="checkbox" value="1"/>
                                <input class="btn btn-danger remove-illustration" type="button" value="Remove"/>
                            @endif
                        </div>
                    </div>

                    <hr/>
                    <h3>Register2 page</h3>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Heading text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('register2_heading_text', isset($contentSet) ? $contentSet->getRegister2HeadingText() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label" id="riverroad2">Subheading text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('register2_subheading_text', isset($contentSet) ? $contentSet->getRegister2SubheadingText() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">CTA</label>
                        <div class="col-xs-6">
                            {{ Form::text('register2_cta_text', isset($contentSet) ? $contentSet->getRegister2CtaText() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Illustration </label>
                        <div class="col-xs-6 illustration-container">
                            {{ Form::file('register2_illustration') }}
                            @if (isset($contentSet) && $image = $contentSet->getRegister2Illustration())
                                <img style="margin-bottom: 10px; margin-top: 10px;" class="illustration-img" width="550" src="{{ $image }}" />
                                <br />
                                <input class="register_illustration-flag hidden" name="register2_illustration-remove" type="checkbox" value="1"/>
                                <input class="btn btn-danger remove-illustration" type="button" value="Remove"/>
                            @endif
                        </div>
                    </div>

                    <hr/>
                    <h3>Register3 page</h3>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Heading text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('register3_heading_text', isset($contentSet) ? $contentSet->getRegister3HeadingText() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label" id="riverroad2">Subheading text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('register3_subheading_text', isset($contentSet) ? $contentSet->getRegister3SubheadingText() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">CTA</label>
                        <div class="col-xs-6">
                            {{ Form::text('register3_cta_text', isset($contentSet) ? $contentSet->getRegister3CtaText() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Illustration </label>
                        <div class="col-xs-6 illustration-container">
                            {{ Form::file('register3_illustration') }}
                            @if (isset($contentSet) && $image = $contentSet->getRegister3Illustration())
                                <img style="margin-bottom: 10px; margin-top: 10px;" class="illustration-img" width="550" src="{{  $image }}" />
                                <br />
                                <input class="register_illustration-flag hidden" name="register3_illustration-remove" type="checkbox" value="1"/>
                                <input class="btn btn-danger remove-illustration" type="button" value="Remove"/>
                            @endif
                        </div>
                    </div>

                    <hr/>
                    <h3>Scholarships Freemium Page</h3>
                    <h4>Upgrade block</h4>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('upgradeBlockText', isset($contentSet) ? $contentSet->getUpgradeBlockText() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">CTA 1</label>
                        <div class="col-xs-6">
                            {{ Form::text('upgradeBlockLinkUpgrade', isset($contentSet) ? $contentSet->getUpgradeBlockLinkUpgrade() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">CTA 2</label>
                        <div class="col-xs-6">
                            {{ Form::text('upgradeBlockLinkVip', isset($contentSet) ? $contentSet->getUpgradeBlockLinkVip() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <h3>Application sent screen</h3>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Title</label>
                        <div class="col-xs-6">
                            {{ Form::text('applicationSentTitle', isset($contentSet) ? $contentSet->getApplicationSentTitle() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Description</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('applicationSentDescription', isset($contentSet) ? $contentSet->getApplicationSentDescription() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">No credits text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('applicationSentContent', isset($contentSet) ? $contentSet->getApplicationSentContent() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>

                    <hr>
                    <h3>No credits page</h3>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Title</label>
                        <div class="col-xs-6">
                            {{ Form::text('noCreditsTitle', isset($contentSet) ? $contentSet->getNoCreditsTitle() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Description</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('noCreditsDescription', isset($contentSet) ? $contentSet->getNoCreditsDescription() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('noCreditsContent', isset($contentSet) ? $contentSet->getNoCreditsContent() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>

                    <hr>
                    <h3>Plans page</h3>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Header text</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('pp_header_text', isset($contentSet) ? $contentSet->getPpHeaderText() : '', ['class' => 'form-control tinymce']) }}
                            Generic Tags:
                            <div>
                                <code class="tag"> [[email]] </code>
                                <code class="tag"> [[private_email]] </code>
                                <code class="tag"> [[password]] </code>
                                <code class="tag"> [[username]] </code>
                                <code class="tag"> [[first_name]] </code>
                                <code class="tag"> [[last_name]] </code>
                                <code class="tag"> [[full_name]] </code>
                                <code class="tag"> [[phone]] </code>
                                <code class="tag"> [[phone_mask]] </code>
                                <code class="tag"> [[gender]] </code>
                                <code class="tag"> [[citizenship]] </code>
                                <code class="tag"> [[ethnicity]] </code>
                                <code class="tag"> [[country]] </code>
                                <code class="tag"> [[state]] </code>
                                <code class="tag"> [[state_name]] </code>
                                <code class="tag"> [[state_abbreviation]] </code>
                                <code class="tag"> [[city]] </code>
                                <code class="tag"> [[address]] </code>
                                <code class="tag"> [[zip]] </code>
                                <code class="tag"> [[school_level]] </code>
                                <code class="tag"> [[degree]] </code>
                                <code class="tag"> [[degree_type]] </code>
                                <code class="tag"> [[enrollment_year]] </code>
                                <code class="tag"> [[enrollment_month]] </code>
                                <code class="tag"> [[gpa]] </code>
                                <code class="tag"> [[career_goal]] </code>
                                <code class="tag"> [[graduation_year]] </code>
                                <code class="tag"> [[graduation_month]] </code>
                                <code class="tag"> [[study_online]] </code>
                                <code class="tag"> [[highschool]] </code>
                                <code class="tag"> [[highschool_address]] </code>
                                <code class="tag"> [[university]] </code>
                                <code class="tag"> [[university_address]] </code>
                                <code class="tag"> [[date_of_birth]] </code>
                                <code class="tag"> [[age]] </code>
                                <code class="tag"> [[eligible_scholarships_count]] </code>
                                <code class="tag"> [[eligible_scholarships_amount]] </code>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Sub Header text</label>

                        <div class="col-xs-6">
                            {{ Form::textarea('pp_header_text2', isset($contentSet) ? $contentSet->getPpHeaderText2() : '', ['class' => 'form-control tinymce']) }}
                            Generic Tags:
                            <div>
                                <code class="tag"> [[email]] </code>
                                <code class="tag"> [[private_email]] </code>
                                <code class="tag"> [[password]] </code>
                                <code class="tag"> [[username]] </code>
                                <code class="tag"> [[first_name]] </code>
                                <code class="tag"> [[last_name]] </code>
                                <code class="tag"> [[full_name]] </code>
                                <code class="tag"> [[phone]] </code>
                                <code class="tag"> [[phone_mask]] </code>
                                <code class="tag"> [[gender]] </code>
                                <code class="tag"> [[citizenship]] </code>
                                <code class="tag"> [[ethnicity]] </code>
                                <code class="tag"> [[country]] </code>
                                <code class="tag"> [[state]] </code>
                                <code class="tag"> [[state_name]] </code>
                                <code class="tag"> [[state_abbreviation]] </code>
                                <code class="tag"> [[city]] </code>
                                <code class="tag"> [[address]] </code>
                                <code class="tag"> [[zip]] </code>
                                <code class="tag"> [[school_level]] </code>
                                <code class="tag"> [[degree]] </code>
                                <code class="tag"> [[degree_type]] </code>
                                <code class="tag"> [[enrollment_year]] </code>
                                <code class="tag"> [[enrollment_month]] </code>
                                <code class="tag"> [[gpa]] </code>
                                <code class="tag"> [[career_goal]] </code>
                                <code class="tag"> [[graduation_year]] </code>
                                <code class="tag"> [[graduation_month]] </code>
                                <code class="tag"> [[study_online]] </code>
                                <code class="tag"> [[highschool]] </code>
                                <code class="tag"> [[highschool_address]] </code>
                                <code class="tag"> [[university]] </code>
                                <code class="tag"> [[university_address]] </code>
                                <code class="tag"> [[date_of_birth]] </code>
                                <code class="tag"> [[age]] </code>
                                <code class="tag"> [[eligible_scholarships_count]] </code>
                                <code class="tag"> [[eligible_scholarships_amount]] </code>
                            </div>
                        </div>

                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Carousel's items count <samp>*</samp></label>
                        <div class="col-xs-6">
                            {{ Form::number('pp_carousel_items_cnt', isset($contentSet) ? $contentSet->getPpCarouselItemsCnt() : '', ['class' => 'form-control']) }}
                            <blockquote>
                                <footer>* with 0 value all eligible scholarships will be displayed on the carousel.</footer>
                            </blockquote>
                        </div>
                    </div>
                </fieldset>
                <hr/>
                <div class="row">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-9">{{ Form::submit('Save', ['class' => 'btn btn-success']) }}</div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
@endsection