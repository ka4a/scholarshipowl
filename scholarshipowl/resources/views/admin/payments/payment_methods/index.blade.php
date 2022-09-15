@extends('admin.base')
@section('content')
    <div class="row">
        <div class="col-xs-12">
        <div class="box">
            <div class="box-content">
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Company Details Title</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($methods_list as $method)
                        <tr>
                            <td>{{ $method->getId() }}</td>
                            <td>{{ $method->getName() }}</td>
                            <td>{{ $method->getFeatureCompanyDetailsSet() }}</td>
                            <td>
                                <a class="btn btn-warning"
                                   href="{{ route('admin::payments.payment_methods.edit', $method->getId()) }}">Edit</a>
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
