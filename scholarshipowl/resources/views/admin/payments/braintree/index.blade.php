@extends('admin.base')

@section('content')
    <div class="row">
        <div class="col-xs-12">
            @section('box-content')
            {{ Form::open(['route' => 'admin::payments.braintree.saveDefault']) }}
            {{ Form::token()  }}
                <div class="row">
                    <div class="col-xs-3">
                        Default braintree account
                    </div>
                    <div class="col-xs-6">
                        {{ Form::select('default', $accountsOptions, $default, ['class' => 'form-control']) }}
                    </div>
                    <div class="col-xs-3">
                        <button type="submit" class="btn btn-success">Save Setting</button>
                    </div>
                </div>
            {{ Form::close() }}
            @overwrite
            @include('admin.common.box', ['boxName' => 'Braintree Default Account Setting'])
            @section('box-content')
                <table class="table table-hover table-striped table-bordered table-heading">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Merchant Id</th>
                            <th>Webhook URL</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            <tr>
                                <td>{{ $account->getId() }}</td>
                                <td>{{ $account->getName() }}</td>
                                <td>{{ $account->getMerchantId() }}</td>
                                <td>{{ route('webhook', $account->getId()) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @overwrite
            @include('admin.common.box', ['boxName' => 'Braintree Accounts'])
            @section('box-content')
                {{ Form::open(['route' => 'admin::payments.braintree.saveAccount', 'class' => 'form-horizontal']) }}
                {{ Form::token() }}

                    <fieldset>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Name</label>
                            <div class="col-xs-6">
                                {{ Form::text('name', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Merchant Id</label>
                            <div class="col-sm-6">
                                {{ Form::text('merchantId', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-3 control-label">Public Key</label>
                            <div class="col-xs-6">
                                {{ Form::text('publicKey', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-xs-3 control-label">Private Key</label>
                            <div class="col-xs-6">
                                {{ Form::text('privateKey', null, ['class' => 'form-control']) }}
                            </div>
                        </div>
                    </fieldset>

                    <fieldset>
                        <div class="form-group">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                                {{ Form::submit('Create', ['class' => 'btn btn-success']) }}
                            </div>
                        </div>
                    </fieldset>

                {{ Form::close() }}
            @overwrite
            @include('admin.common.box', ['boxName' => 'Add new account'])
        </div>
    </div>
@stop
