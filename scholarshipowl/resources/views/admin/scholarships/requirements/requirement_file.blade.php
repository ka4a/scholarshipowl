<div class="requirement-file" data-index="{{ $index }}">
    {{ Form::hidden("requirement_file[$index][id]", isset($requirementFile) ? $requirementFile->getId() : '') }}
    <div class="col-xs-12">
        <h3>File requirement ({{ isset($requirementFile) ? $requirementFile->getId() : 'not saved' }})</h3>
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
                        <h4>Title</h4>
                        {{ Form::input('text', "requirement_file[$index][title]", isset($requirementFile) ? $requirementFile->getTitle() : '', [
                            'class' => 'form-control',
                        ]) }}
                        <h4>Description</h4>
                        {{ Form::textarea("requirement_file[$index][description]", isset($requirementFile) ? $requirementFile->getDescription() : '', [
                            'class' => 'form-control',
                        ])}}
                    </td>
                    <td>
                        <h4>Requirement Name</h4>
                        {{ Form::select("requirement_file[$index][requirementName]", $requirementFileNames, isset($requirementFile) ? $requirementFile->getRequirementName()->getId() : null, [
                            'class' => 'form-control',
                        ]) }}
                        <h4>File Extension <i class="fa fa-question-circle" data-toggle="tooltip" title="Restrict file upload by file extension. Multiple extension can be separated with comma. Example: doc, docx"></i></h4>
                        {{ Form::input('text', "requirement_file[$index][fileExtension]", isset($requirementFile) ? $requirementFile->getFileExtension() : '', [
                            'class' => 'form-control',
                        ]) }}
                        <h4>Max file size (Mb) <i class="fa fa-question-circle" data-toggle="tooltip" title="Restrict file upload by file size in megabytes."></i></h4>
                        {{ Form::input('text', "requirement_file[$index][maxFileSize]", isset($requirementFile) ? $requirementFile->getMaxFileSize() : '', [
                            'class' => 'form-control',
                        ]) }}
                        @include('admin.scholarships.requirements.includes.requirement_is_optional', ['requirement_name' => 'requirement_file', 'requirement' => $requirementFile])
                    </td>
                    <td>
                        <a href='#' class='btn btn-danger btn-requirement-delete'>Delete</a>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
