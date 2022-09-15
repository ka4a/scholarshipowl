@extends("admin/base")
@section("content")

<div class="row">
    <div class="col-xs-12 col-sm-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">Actions</div>
            </div>
            <div class="box-content">
                <a href="{{ route('admin::cms.create') }}" class="btn btn-primary">Create CSM Config</a>
                <a href="{{ route('admin::pages.edit') }}" class="btn btn-primary">Create Page</a>
            </div>
        </div>
        <div class="box pages">
            <div class="box-header">
                <div class="box-name">New Pages - List</div>
            </div>
            <div class="box-content">
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                        <tr>
                            <th>Url</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                            <tr>
                                <td><a href="{{ $page->getPublicUrl() }}">{{ $page->getPublicUrl() }}</a></td>
                                <td>{!! $page->getTitle() !!}</td>
                                <td>{{ $page->getTypeName() }}</td>
                                <td>
                                    <a href="{{ route('admin::pages.edit', $page->getId()) }}" class="btn btn-warning">Edit</a>
                                    <a href="{{ route('admin::pages.delete', $page->getId()) }}" class="btn btn-danger"
                                       data-confirm-message="Are you sure want to delete the page?"
                                    >Delete</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-save"></i>
                    <span>Cms List of Pages</span>
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
                <table class="table table-hover table-striped table-bordered table-heading" id="CmsList">
                    <thead>
                    <tr>
                        <th>Page</th>
                        <th>Url</th>
                        <th>Author</th>
                        <th>Title</th>
                        <th>Description</th>
                        <th>Keywords</th>
                        <th>Edit</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach ($cms as $page)
                    <tr>
                        <td>{!! $page->getPage() !!}</td>
                        <td>{!! $page->getUrl() !!}</td>
                        <td>{!! $page->getAuthor() !!}</td>
                        <td>{!! $page->getTitle() !!}</td>
                        <td>{!! $page->getDescription() !!}</td>
                        <td>{!! $page->getKeywords() !!}</td>
                        <td><a href="{{ route('admin::cms.edit', $page->getCmsId()) }}" class="btn btn-primary">Edit</a></td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop
