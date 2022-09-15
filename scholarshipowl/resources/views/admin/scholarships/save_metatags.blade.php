{{ Form::open(['method' => 'post', 'route' => ['admin::scholarships.saveMetatags', $scholarshipEntity->getScholarshipId()], 'class' => 'form-horizontal']) }}
	<fieldset>
        <div class="form-group">
            <label class="col-sm-3 control-label">Author</label>
            <div class="col-sm-6">
                {{ Form::text('meta_author', $scholarshipEntity->getMetaAuthor() ?? \App\Services\CmsService::DEFAULT_AUTHOR, array("class" => "form-control")) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Title</label>
            <div class="col-sm-6">
                {{ Form::text('meta_title', $scholarshipEntity->getMetaTitle() ?? $scholarshipEntity->getTitle(), array("class" => "form-control")) }}
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-3 control-label">Description</label>
            <div class="col-sm-6">
                {{ Form::textarea('meta_description', $scholarshipEntity->getMetaDescription() ?? $scholarshipEntity->getDescription(), array("class" => "form-control")) }}
            </div>
        </div>
		<div class="form-group">
			<label class="col-sm-3 control-label">Keywords</label>
			<div class="col-sm-6">
				{{ Form::textarea('meta_keywords', $scholarshipEntity->getMetaKeywords() ?? $scholarshipEntity->getDescription(), array("class" => "form-control")) }}
			</div>
		</div>
    </fieldset>

    <fieldset>
        <div class="form-group">
            <div class="col-sm-6">
                {{ Form::submit('Save CMS', ['class' => 'btn btn-primary']) }}
            </div>
        </div>
    </fieldset>

{{ Form::close() }}
