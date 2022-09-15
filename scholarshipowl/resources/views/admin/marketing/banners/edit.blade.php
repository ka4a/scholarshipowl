@extends('admin.base')
@section('content')
    <div class="row">
        <div class="col-xs-12">

            <div class="box banners-edit">
                <div class="box-header">
                    <div class="box-name">Banner - {{ $banner ? $banner->getTitle() : 'Create' }}</div>
                </div>
                <div class="box-content">
                    {{ Form::open(['method' => 'post', 'files' => true, 'route' => ['admin::marketing.banners.edit', $banner ? $banner->getId() : null], 'class' => 'form-horizontal']) }}
                    <fieldset>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Title</label>
                            <div class="col-xs-6">
                                {{ Form::text('title', $banner ? $banner->getTitle() : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Url</label>
                            <div class="col-xs-6">
                                {{ Form::text('url', $banner ? $banner->getUrl() : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Url Display</label>
                            <div class="col-xs-6">
                                {{ Form::text('url_display', $banner ? $banner->getUrlDisplay() : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Type</label>
                            <div class="col-xs-6">
                                {{ Form::select('type', \App\Entity\Banner::types(), $banner ? $banner->getType() : null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </fieldset>
                    <hr/>
                    <fieldset>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Header Content</label>
                            <div class="col-xs-6">
                                {{ Form::textarea('header_content', $banner ? $banner->getHeaderContent() : null, ['class' => 'form-control tinymce']) }}
                            </div>
                        </div>

                        <div class="banner-image">
                            @if($banner && $banner->getImage())
                                <div class="form-group">
                                    <div class="col-xs-3"></div>
                                    <div class="col-xs-6">
                                        <img width="300px" height="250px" src="{{ $banner->getImage()->getPublicUrl() }}"/>
                                    </div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Image (300x250)</label>
                                <div class="col-xs-6">
                                    {{ Form::file('image') }}
                                </div>
                            </div>
                        </div>

                        <div class="banner-text">
                            <div class="form-group">
                                <label class="col-xs-3 control-label">Text</label>
                                <div class="col-xs-6">
                                    {{ Form::textarea('text', $banner ? $banner->getText() : null, ['class' => 'form-control tinymce']) }}
                                </div>
                            </div>
                        </div>
                    </fieldset>
                    <hr/>
                    <fieldset>
                        {{ Form::submit('Save', ['class' => 'btn btn-primary']) }}
                    </fieldset>
                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
@stop
