@extends('admin.base')

@section('content')
    @section('box-content')
        <table id="logs-admin-activity" data-url="{{ route('admin::logs.restAdminActivity') }}">
            <thead>
            <tr>
                <th>Id</th>
                <th>Date</th>
                <th>Admin Id</th>
                <th>Admin Name</th>
                <th>Route</th>
                <th>Data</th>
            </tr>
            </thead>
        </table>
    @overwrite
    @include('admin.common.box', ['boxName' => 'Activity Table'])
@endsection
