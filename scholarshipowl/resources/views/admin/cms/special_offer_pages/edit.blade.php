@extends('admin.base')
@section('content')

    <div class="row">
        <div class="col-xs-12 col-sm-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-name">
                        <i class="fa fa-save"></i>
                        <span>Special Offer Page</span>
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
                {{ Form::open(['method' => 'post', 'route' => ['admin::cms.special-offer-pages.edit', $page ? $page->getId() : null ], 'class' => 'form-horizontal']) }}
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Url</label>

                        <div class="col-xs-6">
                            {{ Form::text('url', $page ? $page->getUrl() : null, ['class' => 'form-control', 'readonly' => $page ? true : null]) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Package</label>

                        <div class="col-xs-6">
                            {{ Form::select('packageId', $packages, $page ? $page->getPackage()->getPackageId() : null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <hr/>

                    <fieldset>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Title</label>
                            <div class="col-xs-6">
                                {{ Form::text('title', $page ? $page->getTitle() : null, ['class' => 'form-control tinymce']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-3 control-label">Icon Title 1</label>
                            <div class="col-xs-6">
                                {{ Form::text('icon_title1', $page ? $page->getIconTitle1() : null, ['class' => 'form-control tinymce']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-3 control-label">Icon Title 2</label>
                            <div class="col-xs-6">
                                {{ Form::text('icon_title2', $page ? $page->getIconTitle2() : null, ['class' => 'form-control tinymce']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-3 control-label">Icon Title 3</label>
                            <div class="col-xs-6">
                                {{ Form::text('icon_title3', $page ? $page->getIconTitle3() : null, ['class' => 'form-control tinymce']) }}
                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-xs-3 control-label">Description</label>
                            <div class="col-xs-6">
                                {{ Form::textarea('description', $page ? $page->getDescription() : null, ['class' => 'form-control tinymce']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">"Scroll to" text</label>
                            <div class="col-xs-6">
                                {{ Form::text('scroll_to_text', $page ? $page->getScrollToText() : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </fieldset>

                    <hr/>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Meta Title</label>
                        <div class="col-xs-6">
                            {{ Form::text('meta_title', $page ? $page->getMetaTitle() : null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Meta Description</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('meta_description', $page ? $page->getMetaDescription() : null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Meta Keywords</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('meta_keywords', $page ? $page->getMetaKeywords() : null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-xs-3 control-label">Meta Author</label>
                        <div class="col-xs-6">
                            {{ Form::text('meta_author', $page ? $page->getMetaAuthor() : null, ['class' => 'form-control']) }}
                        </div>
                    </div>

                    <hr/>

                    {{ Form::submit($page ? 'Save' : 'Create', ['class' => 'btn btn-primary']) }}
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@stop
