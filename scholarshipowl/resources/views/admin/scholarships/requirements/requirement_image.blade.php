<div class="requirement-image" data-index="{{ $index }}">
    {{ Form::hidden("requirement_image[$index][id]", isset($requirementImage) ? $requirementImage->getId() : '') }}
    <div class="col-xs-12">
        <h3>Image requirement ({{ isset($requirementImage) ? $requirementImage->getId() : 'not saved' }})</h3>
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
                        <h4>Title</h4>
                        {{ Form::input('text', "requirement_image[$index][title]", isset($requirementImage) ? $requirementImage->getTitle() : '', [
                            'class' => 'form-control',
                        ]) }}
                        <h4>Description</h4>
                        {{ Form::textarea("requirement_image[$index][description]", isset($requirementImage) ? $requirementImage->getDescription() : '', [
                            'class' => 'form-control',
                        ])}}
                    </td>
                    <td>
                        <h4>Requirement Name</h4>
                        {{ Form::select("requirement_image[$index][requirementName]", $requirementImageNames, isset($requirementImage) ? $requirementImage->getRequirementName()->getId() : null, [
                            'class' => 'form-control',
                        ]) }}
                        <h4>File Extension <i class="fa fa-question-circle" data-toggle="tooltip" title="Restrict file upload by file extension. Multiple extension can be separated with comma. Example: doc, docx"></i></h4>
                        {{ Form::input('text', "requirement_image[$index][fileExtension]", isset($requirementImage) ? $requirementImage->getFileExtension() : '', [
                            'class' => 'form-control',
                        ]) }}
                        <h4>Max file size (Mb) <i class="fa fa-question-circle" data-toggle="tooltip" title="Restrict file upload by file size in megabytes."></i></h4>
                        {{ Form::input('text', "requirement_image[$index][maxFileSize]", isset($requirementImage) ? $requirementImage->getMaxFileSize() : '', [
                            'class' => 'form-control',
                        ]) }}
                        <h4>Image width <i class="fa fa-question-circle" data-toggle="tooltip" title="To validate specific file width make from and to same. Zero value would not be validated."></i></h4>
                        <div class="row">
                            <div class="col-xs-6">
                                <span>From:</span>
                                {{ Form::input('text', "requirement_image[$index][minWidth]", isset($requirementImage) ? $requirementImage->getMinWidth() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-6">
                                <span>To:</span>
                                {{ Form::input('text', "requirement_image[$index][maxWidth]", isset($requirementImage) ? $requirementImage->getMaxWidth() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        <h4>Image height</h4>
                        <div class="row">
                            <div class="col-xs-6">
                                <span>From:</span>
                                {{ Form::input('text', "requirement_image[$index][minHeight]", isset($requirementImage) ? $requirementImage->getMinHeight() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                            <div class="col-xs-6">
                                <span>To:</span>
                                {{ Form::input('text', "requirement_image[$index][maxHeight]", isset($requirementImage) ? $requirementImage->getMaxHeight() : '', [
                                    'class' => 'form-control',
                                ]) }}
                            </div>
                        </div>
                        @include('admin.scholarships.requirements.includes.requirement_is_optional', ['requirement_name' => 'requirement_image', 'requirement' => $requirementImage])
                    </td>
                    <td>
                        <a href='#' class='btn btn-danger btn-requirement-delete'>Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
