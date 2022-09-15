@extends("admin/base")
@section("content")

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-save"></i>
                    <span>Edit Page</span>
                </div>
                <div class="box-icons">
                    <a class="collapse-link">
                        <i class="fa fa-chevron-up"></i>
                    </a>
                    <a class="expand-link">
                        <i class="fa fa-expand"></i>
                    </a>
                </div>
                <div class="no-move"></div>
            </div>
            <div class="box-content">
                {{ Form::open(['method' => 'post', 'route' => isset($page) ? ['admin::cms.edit', $page->getCmsId()] : 'admin::cms.create', 'class' => 'form-horizontal']) }}
                    <div class="form-group">
                        <label class="col-sm-3 control-label">Page</label>

                        <div class="col-sm-6">
                            {{ Form::text('page', isset($page) ? $page->getPage() : null, array("class" => "form-control")) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Url</label>

                        <div class="col-sm-6">
                            @if(isset($page))
                                {{ Form::text('url', isset($page) ? $page->getUrl() : null, array("class" => "form-control","readonly")) }}
                            @else
                                {{ Form::select('url', $urls, null, ['class' => 'form-control']) }}
                            @endif
                        </div>

                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Author</label>

                        <div class="col-sm-6">
                            {{ Form::text('author', isset($page) ? $page->getAuthor() : \App\Services\CmsService::DEFAULT_AUTHOR, array("class" => "form-control")) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">Title</label>

                        <div class="col-sm-6">
                            {{ Form::text('title', isset($page) ? $page->getTitle() : \App\Services\CmsService::DEFAULT_TITLE, array("class" => "form-control")) }}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">Description</label>

                        <div class="col-sm-6">
                            {{ Form::textarea('description', isset($page) ? $page->getDescription() : \App\Services\CmsService::DEFAULT_DESCRIPTION, array("class" => "form-control"))}}
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-sm-3 control-label">Keywords</label>

                        <div class="col-sm-6">
                            {{ Form::textarea('keywords', isset($page) ? $page->getKeywords() : \App\Services\CmsService::DEFAULT_KEYWORDS, array("class" => "form-control")) }}
                        </div>
                    </div>


                    <div class="form-group">
                        <div class="col-sm-6">
                            <button class="btn btn-primary">Save</button>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>
</div>
@stop
