@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-info"></i>
                    <span>Company details</span>
                </div>
            </div>
            <div class="box-content">
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Fset title</th>
                            <th>Company Name</th>
                            <th>Company Name 2</th>
                            <th>Address 1</th>
                            <th>Address 2</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($companyDetailsSet as $set)
                        <tr>
                            <td>{{ $set->getId() }}</td>
                            <td>{{ $set->getName() }}</td>
                            <td>{{ $set->getCompanyName() }}</td>
                            <td>{{ $set->getCompanyName2() }}</td>
                            <td>{{ $set->getAddress1() }}</td>
                            <td>{{ $set->getAddress2() }}</td>
                            <td>
                                <a class="btn btn-warning" href="{{ route('admin::features.company_details_set.edit', $set->getId()) }}">Edit</a>
                                <a class="btn btn-info clone-feature" data-href="{{ route('admin::features.company_details_set.clone', $set->getId()) }}">Clone</a>
                                <a class="btn btn-danger"
                                   href="{{ route('admin::features.company_details_set.delete', $set->getId()) }}"
                                   data-confirm-message="{{ sprintf("Are you sure want to remove '%s' payment set?", $set->getCompanyName()) }}"
                                >Remove</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="7" align="right">
                            <a class="btn btn-success" href="{{ route('admin::features.company_details_set.edit') }}">New company details set</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
