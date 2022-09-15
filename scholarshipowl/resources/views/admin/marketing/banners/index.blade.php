@extends('admin.base')
@section('content')
    <div class="row">
        <div class="col-xs-12">

            <div class="box">
                <div class="box-header">
                    <div class="box-name">Actions</div>
                </div>
                <div class="box-content">
                    <a href="{{ route('admin::marketing.banners.edit') }}" class="btn btn-primary">Create Banner</a>
                    @if ($deleteForce)
                        <div class="alert alert-danger">
                            Banner configured to show up on offer wall pages:
                            @if (is_array($deleteForcePages))
                                @foreach($deleteForcePages as $page)
                                    <a href="{{ route('admin::pages.edit', $page) }}">{{ $page }}</a>
                                @endforeach
                            @endif
                            <a href="{{ route('admin::marketing.banners.delete', ['id' => $deleteForce, 'force' => 'force']) }}" class="btn btn-danger">
                                Delete force
                            </a>
                        </div>
                    @endif
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <div class="box-name">Banners - List</div>
                </div>
                <div class="box-header">
                    <table class="table table-hover table-striped table-bordered table-heading">
                        <thead>
                            <tr>
                                <th>Id</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Url</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($banners as $banner)
                                <tr>
                                    <td>{{ $banner->getId() }}</td>
                                    <td>{{ $banner->getTitle() }}</td>
                                    <td>{{ $banner->getTypeName() }}</td>
                                    <td>{{ $banner->getUrl() }}</td>
                                    <td>
                                        <a href="{{ route('admin::marketing.banners.edit', $banner->getId()) }}" class="btn btn-warning">Edit</a>
                                        <a data-confirm-message="Are you sure want to delete this banner?" href="{{ route('admin::marketing.banners.delete', $banner->getId()) }}" class="btn btn-danger">Delete</a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
