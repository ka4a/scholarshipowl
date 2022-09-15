@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    <i class="fa fa-cogs"></i>
                    Sets
                </div>
            </div>
            <div class="box-content">
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                    <tr>
                        <th>Id</th>
                        <th>Name</th>
                        <th>Desktop Payment Set</th>
                        <th>Mobile Payment Set</th>
                        <th>Content Set</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($sets as $set)
                        <tr>
                            <td>{{ $set->getId() }}</td>
                            <td>{{ $set->getName() }}</td>
                            <td>{{ $set->getDesktopPaymentSet() }}</td>
                            <td>{{ $set->getMobilePaymentSet() }}</td>
                            <td>{{ $set->getContentSet() }}</td>
                            <td>
                                <a class="btn btn-warning" href="{{ route('admin::features.edit', $set->getId()) }}">Edit</a>
                                <a class="btn btn-info clone-feature" data-href="{{ route('admin::features.clone', $set->getId()) }}">Clone</a>
                                @if($set->getId() !== \App\Entity\FeatureSet::DEFAULT_SET && !$set->isDeleted())
                                    <a class="btn btn-danger" data-confirm-message="{{ sprintf('Are you sure want ot delete \'%s\' set?', $set->getName()) }}" href="{{ route('admin::features.delete', $set->getId()) }}">Remove</a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                    <tfoot>
                    <tr>
                        <td colspan="6" align="right">
                            <a class="btn btn-success" href="{{ route('admin::features.edit') }}">New Set</a>
                            @if(!$showDeleted)
                                <a class="btn btn-danger" href="{{ route('admin::features.index', ['showDeleted' => 1]) }}">Show Deleted</a>
                            @else
                                <a class="btn btn-warning" href="{{ route('admin::features.index') }}">Hide Deleted</a>
                            @endif
                        </td>
                    </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
