@extends('admin.base')

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-university"></i>
                    <span>Results ({{ count($admins) }})</span>
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
                <a href="{{ route('admin::acl.admin') }}" class="btn btn-success">Create admin</a><br/><br/>
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($admins as $admin)
                            <tr>
                                <td>
                                    {{ $admin->getAdminId() }}
                                </td>

                                <td>
                                    {{ $admin->getName() }}
                                </td>

                                <td>
                                    {{ $admin->getEmail() }}
                                </td>

                                <td>
                                    {{ $admin->getAdminRole()->getName() }}
                                </td>

                                <td>
                                    <a class="btn btn-warning" href="{{ route('admin::acl.admin-post', $admin->getAdminId()) }}">Edit</a>
                                    <a class="btn btn-danger" href="{{ route('admin::acl.admin-delete', $admin->getAdminId()) }}" data-confirm-message="{{ sprintf("Delete admin '%s' (%s)", $admin->getName(), $admin->getAdminId()) }}">Remove</a>
                                    @if ($admin->getAdminRole()->getAdminRoleId() !== \App\Entity\Admin\AdminRole::ROOT)
                                        <a class="btn btn-primary" href="{{ route('admin::acl.permissions', $admin->getAdminRole()->getAdminRoleId())  }} ">Edit Role Permissions</a>
                                    @endif
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
