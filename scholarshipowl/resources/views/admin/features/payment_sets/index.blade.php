@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-credit-card"></i>
                    <span>Payment Sets</span>
                </div>
            </div>
            <div class="box-content">
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Payment Method</th>
                            <th>Name</th>
                            <th>Popup title</th>
                            <th>Packages</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($paymentSets as $set)
                        <tr>
                            <td>{!! $set->getId() !!}</td>
                            <td>{!! $set->getPaymentMethod() !!}</td>
                            <td>{!! $set->getName() !!}</td>
                            <td>{!! $set->getPopupTitle() !!}</td>
                            <td>{!! json_encode($set->getPackages()) !!}</td>
                            <td>
                                <a class="btn btn-warning" href="{{ route('admin::features.payment_sets.edit', $set->getId()) }}">Edit</a>
                                <a class="btn btn-info clone-feature" data-href="{{ route('admin::features.payment_sets.clone', $set->getId()) }}">Clone</a>
                                <a class="btn btn-danger"
                                   href="{{ route('admin::features.payment_sets.delete', $set->getId()) }}"
                                   data-confirm-message="{{ sprintf("Are you sure want to remove '%s' payment set?", $set->getName()) }}"
                                >Remove</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" align="right">
                            <a class="btn btn-success" href="{{ route('admin::features.payment_sets.edit') }}">New payment set</a>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
