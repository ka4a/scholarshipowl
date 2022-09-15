<div class="requirement-input" data-index="{{ $index }}">
    {{ Form::hidden("requirement_input[$index][id]", isset($requirementInput) ? $requirementInput->getId() : '') }}
    <div class="col-xs-12">
        <h3>Input requirement ({{ isset($requirementInput) ? $requirementInput->getId() : 'not saved' }})</h3>
    </div>
    <div class="col-xs-12">
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
                            <div class="col-xs-8">
                                <h4>Title</h4>
                                {{ Form::input('text', "requirement_input[$index][title]", isset($requirementInput) ? $requirementInput->getTitle() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-4">
                                <h4>Tag <i class="fa fa-question-circle" data-toggle="tooltip" title="Tag mast be unique in terms of a scholarship. Allowed characters [a-zA-Z0-9_-]. Example: essayText1. Later on this tag might be used in email template to reference this requirement."></i></h4>
                                {{ Form::input('text', "requirement_input[$index][permanentTag]", isset($requirementInput) ? $requirementInput->getPermanentTag() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        <h4>Description</h4>
                        {{ Form::textarea("requirement_input[$index][description]", isset($requirementInput) ? $requirementInput->getDescription() : '', [
                            'class' => 'form-control',
                        ])}}
                    </td>
                    <td>
                        <h4>Requirement Name</h4>
                        {{ Form::select("requirement_input[$index][requirementName]", $requirementInputNames, isset($requirementInput) ? $requirementInput->getRequirementName()->getId() : null, [
                            'class' => 'form-control',
                        ]) }}
                        @include('admin.scholarships.requirements.includes.requirement_is_optional', ['requirement_name' => 'requirement_input', 'requirement' => $requirementInput])
                    </td>
                    <td>
                        <a href='#' class='btn btn-danger btn-requirement-delete'>Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
