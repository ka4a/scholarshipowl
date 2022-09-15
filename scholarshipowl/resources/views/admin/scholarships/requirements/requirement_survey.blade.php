<div class="requirement-survey" data-index="{{ $index }}">
    {{ Form::hidden("requirement_survey[$index][id]", isset($requirementSurvey) ? $requirementSurvey->getId() : '') }}
    <div class="col-xs-12">
        <h3>Survey requirement ({{ isset($requirementSurvey) ? $requirementSurvey->getId() : 'not saved' }})</h3>
    </div>
    <div class="col-sm-12">
        <table class="table table-bordered table-heading table-hover">
            <thead>
                <tr>
                    <th>Properties</th>
                    <th>Limitations</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="row">
                            <div class="col-xs-6">
                                <h4>Title</h4>
                                {{ Form::input('text', "requirement_survey[$index][title]", isset($requirementSurvey) ? $requirementSurvey->getTitle() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-6">
                                <h4>Tag <i class="fa fa-question-circle" data-toggle="tooltip" title="Tag mast be unique in terms of a scholarship. Allowed characters [a-zA-Z0-9_-]. Example: essayText1. Later on this tag might be used in email template to reference this requirement."></i></h4>
                                {{ Form::input('text', "requirement_survey[$index][permanentTag]", isset($requirementSurvey) ? $requirementSurvey->getPermanentTag() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        <h4>Description</h4>
                        {{ Form::textarea("requirement_survey[$index][description]", isset($requirementSurvey) ? $requirementSurvey->getDescription() : '', [
                            'class' => 'form-control',
                        ])}}

                    </td>
                    <td>
                        <h4>Requirement Name</h4>
                        {{ Form::select("requirement_survey[$index][requirementName]", $requirementSurveyNames, isset($requirementSurvey) ? $requirementSurvey->getRequirementName()->getId() : null, [
                            'class' => 'form-control',
                        ]) }}
                        @include('admin.scholarships.requirements.includes.requirement_is_optional', ['requirement_name' => 'requirement_survey', 'requirement' => $requirementSurvey])
                    </td>

                    <td>
                        <a href='#' class='btn btn-danger btn-requirement-survey-delete'>Delete</a>
                    </td>

            </tbody>
        </table>
        <table class="table table-bordered table-heading table-hover" style="margin-top: -22px;">
            <thead>
            <tr>
                <th>Questions</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <div class="row survey-box">
                        @if( isset($requirementSurvey))
                        @foreach($requirementSurvey->getSurvey() as $i => $question)
                            <div class="col-xs-12 survey-containter">
                                {{ Form::hidden("", $i, [ 'class' => 'survey_id']) }}
                                <table class="table">
                                    <tr>
                                        <td colspan="3">
                                            <a class='btn btn-danger btn-question-delete'>Delete question</a>
                                        </td>
                                    </tr>
                                    <tr>

                                    <td>Question type</td>
                                    <td>
                                        {{ Form::select("requirement_survey[$index][survey][$i][type]", \App\Entity\RequirementSurvey::getQuestionTypes(), $question['type'], [
                                        'class' => 'form-control',
                                        ]) }}
                                        <div class="alert alert-warning" role="alert">
                                            <p>-Multiple choice: user can select multiple answers</p>
                                            <p>-Single answer: user can select only one answer</p>
                                        </div>
                                    </td>
                                    </tr>
                                    <tr>
                                        <td>Short description/instruction</td>
                                        <td>
                                            {{ Form::textarea("requirement_survey[$index][survey][$i][description]", isset($question['description']) ? $question['description'] : '', [
                                                'class' => 'form-control', 'placeholder' => 'On scale 1-5 grade year interest in getting this scholarship.',
                                            ])}}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Question</td>
                                        <td>
                                            {{ Form::textarea("requirement_survey[$index][survey][$i][question]", isset($question['question']) ? $question['question'] : '', [
                                                'class' => 'form-control', 'placeholder' => 'Enter question text',
                                            ])}}
                                        </td>
                                    </tr>
                                    <tr >
                                        <td>Suggested answers/options:</td>
                                        <td>
                                            <table class="options table">
                                                <tbody>
                                                    @if ( isset($question['options']))
                                                        @foreach($question['options'] as $key => $options)
                                                            <tr class="options-container">
                                                                <td> {{ Form::input('text', "requirement_survey[$index][survey][$i][options][$key]", isset($options) ? $options : '', [
                                                                        'class' => 'form-control', 'placeholder' => 'Enter a suggested answer'
                                                                    ]) }}
                                                                </td>
                                                                <td>
                                                                    <a class='btn btn-danger btn-option-delete'>Remove option</a>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    @endif
                                                </tbody>
                                            </table>
                                            <a class='btn btn-success btn-option-add'>Add option</a>
                                        </td>
                                    </tr>

                                </table>

                            </div>
                        @endforeach
                        @endif
                        <div class="col-xs-12 center-block" style="text-align: center">
                            <a class='btn btn-success btn-new-question' style="font-size: 17px;">Add question</a>
                        </div>
                    </div>
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>
