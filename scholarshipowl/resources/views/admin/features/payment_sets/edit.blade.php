@extends('admin.base')
@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <div class="box-name">
                    {{ (isset($set) ? 'Edit ' . $set->getName() : 'Create payment set') }}
                </div>
            </div>
            <div class="box-content">
                {{ Form::open(['route' => ['admin::features.payment_sets.edit', isset($set) ? $set->getId() : null], 'class' => 'form-horizontal']) }}
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Name</label>
                        <div class="col-xs-6">
                            {{ Form::text('name', isset($set) ? $set->getName() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Payment Method</label>
                        <div class="col-xs-6">
                            {{ Form::select('payment_method', \App\Entity\PaymentMethod::options(), isset($set) ? $set->getPaymentMethod()->getId() : '', ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Show names (Recurly)</label>
                        <div class="col-xs-6">
                            {{ Form::select('show_names', [0 => 'No', 1 => 'Yes'], isset($set) ? ($set->getShowNames() ? 1 : 0) : null, ['class' => 'form-control']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Popup title</label>
                        <div class="col-xs-6">
                            {{ Form::textarea('popup_title', isset($set) ? $set->getPopupTitle() : '', ['class' => 'form-control tinymce']) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Show only SpecialOffer package on mobile</label>
                        <div class="col-xs-2">
                            {{ Form::checkbox('mobile_special_offer_only', 1, isset($set) ? (bool)$set->getMobileSpecialOfferOnly() : true) }}
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label">Packages</label>
                        <div class="col-xs-2">
                            {{ Form::select('package[0][id]', \App\Entity\Package::options(), isset($set) ? $set->getPackages()[0]['id'] ?? null : null, ['class' => 'populate placeholder select2']) }}
                            {{ Form::checkbox('package[0][flag]', 1, isset($set) && isset($set->getPackages()[0]['flag']) ? true : false, ['id' => 'package_flag_0']) }}
                            <label for="package_flag_0">SpecialOffer</label>
                        </div>
                        <div class="col-xs-2">
                            {{ Form::select('package[1][id]', \App\Entity\Package::options(), isset($set) ? $set->getPackages()[1]['id'] ?? null : null, ['class' => 'populate placeholder select2']) }}
                            {{ Form::checkbox('package[1][flag]', 1, isset($set) && isset($set->getPackages()[1]['flag']) ? true : false, ['id' => 'package_flag_1']) }}
                            <label for="package_flag_1">SpecialOffer</label>
                        </div>
                        <div class="col-xs-2">
                            {{ Form::select('package[2][id]', \App\Entity\Package::options(), isset($set) ? $set->getPackages()[2]['id'] ?? null : null, ['class' => 'populate placeholder select2']) }}
                            {{ Form::checkbox('package[2][flag]', 1, isset($set) && isset($set->getPackages()[2]['flag']) ? true : false, ['id' => 'package_flag_2']) }}
                            <label for="package_flag_2">SpecialOffer</label>
                        </div>
                        <div class="col-xs-2">
                            {{ Form::select('package[3][id]', \App\Entity\Package::options(), isset($set) ? $set->getPackages()[3]['id'] ?? null : null, ['class' => 'populate placeholder select2']) }}
                            {{ Form::checkbox('package[3][flag]', 1, isset($set) && isset($set->getPackages()[3]['flag']) ? true : false, ['id' => 'package_flag_3']) }}
                            <label for="package_flag_3">SpecialOffer</label>
                        </div>
                    </div>
                    <div >
                        <label class="col-xs-3 control-label">Description</label>
                        <br>
                        <br>
                        <div id="option-list">
                        @php
                            $options = [];
                            if(!is_null($set)) {
                                $options = $set->getCommonOption();
                            }
                        @endphp


                        @foreach($options as $indx => $option)
                            <div class="option-row" style="margin-bottom: 6px;">
                                <div class="row">
                                    <div class="col-xs-3">
                                        {{ Form::textarea("package_common_option[$indx][text]", isset($option) ? $option['text'] : '', ['class' => 'form-control tinymce','style' => 'height:10', 'rows' => 1, 'cols' =>1]) }}
                                    </div>
                                    @php
                                        $i = 0;
                                    @endphp

                                    @foreach($option['status'] as $status)
                                        <div class="col-xs-2">
                                            Enable in package {{$i+1}}
                                            {{ Form::checkbox("package_common_option[$indx][status][$i]", 0, true, ['id' => "package_common_option_$i", 'class' => 'hidden']) }}
                                            {{ Form::checkbox("package_common_option[$indx][status][$i]", 1, isset($status) && $status == "1" ? true : false, ['id' => "package_common_option_$i"]) }}
                                        </div>
                                        @php
                                            $i++;
                                        @endphp
                                    @endforeach
                                </div>
                                <div class="row">
                                <input type="button" class="remove-option btn btn-danger" style="margin-left: 15px; margin-top: 5px;" value="Remove">
                            </div>
                            </div>
                        @endforeach
                        </div>
                        <br>
                        <input type="button" id="add-option" class="btn btn-success" value="Add new line">

                    </div>

                </fieldset>
                <hr/>
                <div class="row">
                    <div class="col-xs-3"></div>
                    <div class="col-xs-9">{{ Form::submit('Save', ['class' => 'btn btn-success']) }}</div>
                </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@endsection
