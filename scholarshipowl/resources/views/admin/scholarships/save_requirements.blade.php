<div class="row scholarships-requirements-form">
    <div class="col-xs-12">
        {{ Form::open(['route' => 'admin::scholarships.saveRequirements', 'method' => 'POST']) }}
        {{ Form::token() }}
        {{ Form::hidden('scholarship_id', isset($scholarshipEntity) ? $scholarshipEntity->getScholarshipId() : null) }}

        <fieldset class="col-sm-12 container-requirement-texts">
            @if (isset($scholarshipEntity))
                @foreach($scholarshipEntity->getRequirementTexts() as $index => $requirementText)
                    @include('admin.scholarships.requirements.requirement_text', ['index' => $index, 'requirementText' => $requirementText])
                @endforeach
            @endif
        </fieldset>

        <fieldset class="col-sm-12 container-requirement-files">
            @if (isset($scholarshipEntity))
                @foreach($scholarshipEntity->getRequirementFiles() as $index => $requirementFile)
                    @include('admin.scholarships.requirements.requirement_file', ['index' => $index, 'requirementFile' => $requirementFile])
                @endforeach
            @endif
        </fieldset>

        <fieldset class="col-sm-12 container-requirement-images">
            @if (isset($scholarshipEntity))
                @foreach($scholarshipEntity->getRequirementImages() as $index => $requirementImage)
                    @include('admin.scholarships.requirements.requirement_image', ['index' => $index, 'requirementFile' => $requirementImage])
                @endforeach
            @endif
        </fieldset>

        <fieldset class="col-sm-12 container-requirement-inputs">
            @if (isset($scholarshipEntity))
                @foreach($scholarshipEntity->getRequirementInputs() as $index => $requirementInput)
                    @include('admin.scholarships.requirements.requirement_input', ['index' => $index, 'requirementInput' => $requirementInput])
                @endforeach
            @endif
        </fieldset>

        <fieldset class="col-sm-12 container-requirement-surveys">
            @if (isset($scholarshipEntity))
                @foreach($scholarshipEntity->getRequirementSurvey() as $index => $requirementSurvey)
                    @include('admin.scholarships.requirements.requirement_survey', ['index' => $index, 'requirementSurvey' => $requirementSurvey])
                @endforeach
            @endif
        </fieldset>

        <fieldset class="col-sm-12 container-requirement-special-eligibility">
            @if (isset($scholarshipEntity))
                @foreach($scholarshipEntity->getRequirementSpecialEligibility() as $index => $requirementSpecialEligibility)
                    @include('admin.scholarships.requirements.requirement_special_eligibility', ['index' => $index, 'requirementSpecialEligibility' => $requirementSpecialEligibility])
                @endforeach
            @endif
        </fieldset>

        <div class="action-buttons col-sm-12">
            <button type="submit" class="btn btn-success pull-left" id="scholarship-save-requirements" href="#">Save Requirements</button>
            <a class="btn btn-primary btn-add-requirement-survey pull-right"  >Add Survey Requirement</a>
            <a class="btn btn-primary btn-add-requirement-input pull-right" href="#">Add Input Requirement</a>
            <a class="btn btn-primary btn-add-requirement-text pull-right" href="#">Add Text Requirement</a>
            <a class="btn btn-primary btn-add-requirement-file pull-right" href="#">Add File Requirement</a>
            <a class="btn btn-primary btn-add-requirement-image pull-right" href="#">Add Image Requirement</a>
            <a class="btn btn-primary btn-add-requirement-spec-eligibility pull-right">Add Special Eligibility Requirement</a>

        </div>
        {{ Form::close() }}

        <div class="hidden template-requirement-text">
            @include('admin.scholarships.requirements.requirement_text', ['index' => '%index%', 'requirementText' => null])
        </div>
        <div class="hidden template-requirement-file">
            @include('admin.scholarships.requirements.requirement_file', ['index' => '%index%', 'requirementFile' => null])
        </div>
        <div class="hidden template-requirement-image">
            @include('admin.scholarships.requirements.requirement_image', ['index' => '%index%', 'requirementImage' => null])
        </div>
        <div class="hidden template-requirement-input">
            @include('admin.scholarships.requirements.requirement_input', ['index' => '%index%', 'requirementInput' => null])
        </div>
        <div class="hidden template-requirement-survey">
            @include('admin.scholarships.requirements.requirement_survey', ['index' => '%index%', 'requirementSurvey' => null])
        </div>
        <div class="hidden template-requirement-special-eligibility">
            @include('admin.scholarships.requirements.requirement_special_eligibility', ['index' => '%index%', 'requirementSpecialEligibility' => null])
        </div>
    </div>
</div>
