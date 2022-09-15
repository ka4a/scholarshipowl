<div id="sowl-checkout{{ isset($id) ? "-$id" : '' }}"
     class="sowl-checkout clearfix {{ !empty($mobile) ? 'mobile-checkout' : '' }}">
    {{ Form::open(['class' =>'payment-form', 'id' => 'bt-form'.( isset($id) ? "-$id" : '')]) }}
        {{ Form::input('hidden', 'package_id', isset($package) ? $package->getPackageId() : '') }}
        {{ Form::input('hidden', 'free_trial', (isset($package) && $package->isFreeTrial()) ? 'true' : 'false') }}
        {{ Form::input('hidden', 'billing-agreement', isset($package) ? $package->getBillingAgreement() : '') }}

        {{ Form::input('hidden', 'tracking_params', $tracking_params ?? '') }}
        {{ Form::input('hidden', 'braintree_token', $braintreeToken ?? null) }}
        {{ Form::input('hidden', 'recurly_token') }}
        {{ Form::input('hidden', 'payment_method_nonce') }}

        <ul class="nav nav-tabs payment-tabs">
            <li class="tab-paypal col-xs-12 col-sm-6"></li>
            <li class="tab-creditcard col-xs-12 col-sm-6 active">
                <h3>Credit Card</h3>
                <div class="creditcards-sprite center-block">
                    <span class="hor-1"></span>
                    <span class="hor-2"></span>
                </div>
            </li>
        </ul>
        <div class="payments-content col-xs-12">
            <div class="tab-credit-card tab-pane fade in active col-xs-12">
                <div class="row">
                    <div class="input-wrapper col-xs-12">
                        <div class="bt-input-container">
                            <label for="card-element">Credit or debit card</label>
                            <div id="card-element"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <span id="card-errors" class="bt-error"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="hidden-xs col-sm-2"></div>
                    <div class="col-xs-12 col-sm-8">
                        <button type="submit" class="btn btn-warning btn-continue">
                            <i class="glyphicon glyphicon-lock hidden-xs"></i>
                            <span class="message">Continue</span>
                            <span class="loading"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>Loading...</span>
                        </button>
                    </div>
                    <div class="hidden-xs col-sm-2">
                        <img class="secure-badge pull-right" src="{{ asset('assets/img/100-secure.png') }}" />
                    </div>
                </div>
                <div class="row text-center">
                  <small>By signing up, you agree to the <a class="break-xs" href="/terms" target="_blank">ScholarshipOwl terms</a></small>
                </div>
            </div>
        </div>
    {{ Form::close() }}
</div>
