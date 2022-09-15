@extends("admin/base")
@section("content")

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-name">
                        <span>Actions<span>
                    </div>
                </div>
                <div class="box-content">
                    <a href="{{ route('admin::cms.special-offer-pages.edit') }}" class="btn btn-primary">Create</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <div class="box-name">
                        <span>Special Offer Pages - List</span>
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
                    <table class="table table-hover table-striped table-bordered table-heading">
                        <thead>
                            <tr>
                                <th>URL</th>
                                <th>Package</th>
                                <th>Title</th>
                                <th>Actions</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($pages as $page)
                                <tr>
                                    <td><a href="{{ $page->getFullUrl() }}" target="_blank">{{ $page->getFullUrl() }}</a></td>
                                    <td>{{ sprintf('(%s) %s', $page->getPackage()->getPackageId(), $page->getPackage()->getName()) }}</td>
                                    <td>{!! $page->getTitle() !!}</td>
                                    <td>
                                        <a href="{{ route('admin::cms.special-offer-pages.edit', $page->getId()) }}" class="btn btn-warning">Edit</a>
                                        <a data-confirm-message="{{ sprintf('Are you sure want to delete special offer page: %s', $page->getUrl()) }}"
                                           href="{{ route('admin::cms.special-offer-pages.delete', $page->getId()) }}"
                                           class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
