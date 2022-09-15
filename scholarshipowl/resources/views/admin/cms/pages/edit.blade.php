@extends('admin.base')
@section('content')

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-name">Page - Edit</div>
                </div>
                <div class="box-content">
                    {{ Form::open(['method' => 'post', 'route' => ['admin::pages.edit', $page ? $page->getId() : null], 'class' => 'form-horizontal']) }}
                    <fieldset>
                        @if ($page)
                            <div class="form-group">
                                <label class="control-label col-xs-3">Url</label>
                                <div class="col-xs-6">
                                    <a href="{{ $page->getPublicUrl() }}">
                                        {{ $page->getPublicUrl() }}
                                    </a>
                                </div>
                            </div>
                        @endif
                        <div class="form-group">
                            <label class="control-label col-xs-3">Path</label>
                            <div class="col-xs-6">
                                {{ Form::text('path', $page ? $page->getPath() : null, ['class' => 'form-control', 'readonly' => $page ? true : null]) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="control-label col-xs-3">Type</label>
                            <div class="col-xs-6">
                                @if($page)
                                    {{ Form::hidden('type', $page->getType()) }}
                                    {{ Form::text('type-name', $page->getTypeName(), ['class' => 'form-control', 'readonly' => true]) }}
                                @else
                                    {{ Form::select('type', \App\Entity\Page::types(), null, ['class' => 'form-control']) }}
                                @endif
                            </div>
                        </div>
                    </fieldset>
                    <hr/>
                    @if ($page && $page->getType() === \App\Entity\Page::TYPE_OFFER_WALL)
                        @include('admin.cms.pages.edit-offer-wall', ['offerWall' => $offerWall])
                        <hr/>
                    @endif
                    <fieldset>
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
                    </fieldset>
                    <hr/>
                    <fieldset>
                        <div class="form-group">
                            <div class="col-xs-3"></div>
                            <div class="col-xs-6">
                                {{ Form::submit('Save', ['class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    </fieldset>
                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>

@endsection
