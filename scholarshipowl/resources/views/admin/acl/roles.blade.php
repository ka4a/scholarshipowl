@extends('admin.base')

@section('content')
<div class="row">
    <div class="col-xs-12">
        @section('box-content')
            <a href="{{ route('admin::acl.role') }}" class="btn btn-success">Create role</a><br/><br/>
            <table class="table table-hover table-striped table-bordered table-heading">
                <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>
                                {{ $role->getAdminRoleId() }}
                            </td>

                            <td>
                                {{ $role->getName() }}
                            </td>

                            <td>
                                {{ $role->getDescription() }}
                            </td>

                            <td>
                                @if ($role->getAdminRoleId() !== \App\Entity\Admin\AdminRole::ROOT)
                                <a class="btn btn-primary" href="{{ route('admin::acl.permissions', ['roleId' => $role->getAdminRoleId()])  }} ">Edit Permissions</a>
                                <a class="btn btn-warning" href="{{ route('admin::acl.role', ['roleId' => $role->getAdminRoleId()]) }}">Edit</a>
                                <a class="btn btn-danger"  href="{{ route('admin::acl.role-delete', $role->getAdminRoleId()) }}" data-confirm-message="Delete Role '{{ $role->getName() }}' ({{ $role->getAdminRoleId() }}) ?">Remove</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @overwrite
        @include('admin.common.box', ['boxIcon' => 'university', 'boxName' => sprintf('Results (%d)', count($roles))])
    </div>
</div>
@stop
