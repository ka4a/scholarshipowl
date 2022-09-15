@extends('admin.base')
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <table class="table table-hover table-striped table-bordered table-heading">
                <thead>
                <tr>
                    <th>Id</th>
                    <th>Slug</th>
                    <th>isActive</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                    @foreach($notifications as $notification)
                        <tr>
                            <td>
                                {{ $notification->getId()  }}
                            </td>
                            <td>
                               {{ $notification->getSlug() }}
                            </td>
                            <td>
                               {{ $notification->getIsActive() ? 'true' : 'false' }}
                            </td>
                            <td>
                                <a class="btn btn-primary" href="{{route("admin::applyme.push-notifications.edit", $notification->getId())}}">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection