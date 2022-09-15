<div class="requirement-text" data-index="{{ $index }}">
    {{ Form::hidden("requirement_text[$index][id]", isset($requirementText) ? $requirementText->getId() : '') }}
    <div class="col-xs-12">
        <h3>Text requirement ({{ isset($requirementText) ? $requirementText->getId() : 'not saved' }})</h3>
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
                            <div class="col-xs-12">
                                <h4>Send type</h4>
                                {{Form::select("requirement_text[$index][sendType]", \App\Entity\RequirementText::$sendTypes[$scholarshipEntity->getApplicationType()], isset($requirementText) ? $requirementText->getSendType() : \App\Entity\RequirementText::SEND_TYPE_ATTACHMENT, [
                                    'class' => 'form-control send-type-select',
                                ]) }}
                            </div>
                        </div>
                        <div class="row attachment-config">
                            <div class="col-xs-4">
                                <h4>Convert file to <i class="fa fa-question-circle" data-toggle="tooltip" title="Entered text will be converted to this extension."></i></h4>
                                {{ Form::select("requirement_text[$index][attachmentType]", \App\Entity\RequirementText::$attachmentTypes, isset($requirementText) ? $requirementText->getAttachmentType() : \App\Entity\RequirementText::ATTACHMENT_TYPE_PDF, [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-4">
                                <h4>File name <i class="fa fa-question-circle" data-toggle="tooltip" title="File will be renamed on sending. Default: [[first_name]]_[[last_name]]__[[title]].[[attachment_type]]"></i></h4>
                                {{ Form::input('text', "requirement_text[$index][attachmentFormat]", isset($requirementText) ? $requirementText->getAttachmentFormat() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6">
                                <h4>Title</h4>
                                {{ Form::input('text', "requirement_text[$index][title]", isset($requirementText) ? $requirementText->getTitle() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-6">
                                <h4>Tag <i class="fa fa-question-circle" data-toggle="tooltip" title="Tag mast be unique in terms of a scholarship. Allowed characters [a-zA-Z0-9_-]. Example: essayText1. Later on this tag might be used in email template to reference this requirement."></i></h4>
                                {{ Form::input('text', "requirement_text[$index][permanentTag]", isset($requirementText) ? $requirementText->getPermanentTag() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        <h4>Description</h4>
                        {{ Form::textarea("requirement_text[$index][description]", isset($requirementText) ? $requirementText->getDescription() : '', [
                            'class' => 'form-control',
                        ])}}
                    </td>
                    <td>
                        <h4>Requirement Name</h4>
                        {{ Form::select("requirement_text[$index][requirementName]", $requirementTextNames, isset($requirementText) ? $requirementText->getRequirementName()->getId() : null, [
                            'class' => 'form-control',
                        ]) }}

                        <h4>Words</h4>
                        <div class="row">
                            <div class="col-xs-6">
                                <span>Min:</span>
                                {{ Form::input('text', "requirement_text[$index][minWords]", isset($requirementText) ? $requirementText->getMinWords() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-6">
                                <span>Max:</span>
                                {{ Form::input('text', "requirement_text[$index][maxWords]", isset($requirementText) ? $requirementText->getMaxWords() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        <h4>Characters</h4>
                        <div class="row">
                            <div class="col-xs-6">
                                <span>Min:</span>
                                {{ Form::input('text', "requirement_text[$index][minCharacters]", isset($requirementText) ? $requirementText->getMinCharacters() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-6">
                                <span>Max:</span>
                                {{ Form::input('text', "requirement_text[$index][maxCharacters]", isset($requirementText) ? $requirementText->getMaxCharacters() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-12">
                                <h4>Allow file <i class="fa fa-question-circle" data-toggle="tooltip" title="Allow file for online application only if we have file upload input."></i></h4>
                                {{ Form::select("requirement_text[$index][allowFile]", [0 => 'No', 1 => 'Yes'], isset($requirementText) ? (int) $requirementText->getAllowFile() : 0, [
                                    'class' => 'form-control allow-file-select',
                                ]) }}
                            </div>
                        </div>
                        <div class="row file-configuration">
                            <div class="col-xs-12">
                                <h4>File Extension <i class="fa fa-question-circle" data-toggle="tooltip" title="Restrict file upload by file extension. Multiple extension can be separated with comma. Example: doc, docx"></i></h4>
                                {{ Form::input('text', "requirement_text[$index][fileExtension]", isset($requirementText) ? $requirementText->getFileExtension() : '', [
                                    'class' => 'form-control file-extension',
                                ]) }}
                            </div>
                            <div class="col-xs-12">
                                <h4>Max file size (Mb) <i class="fa fa-question-circle" data-toggle="tooltip" title="Restrict file upload by file size in megabytes."></i></h4>
                                {{ Form::input('text', "requirement_text[$index][maxFileSize]", isset($requirementText) ? $requirementText->getMaxFileSize() : '', [
                                    'class' => 'form-control max-file-size',
                                ]) }}
                            </div>
                        </div>
                        @include('admin.scholarships.requirements.includes.requirement_is_optional', ['requirement_name' => 'requirement_text', 'requirement' => $requirementText])
                    </td>
                    <td>
                        <a href='#' class='btn btn-danger btn-requirement-delete'>Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
