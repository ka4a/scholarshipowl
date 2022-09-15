<div id="sowl-checkout{{ isset($id) ? "-$id" : '' }}"
     class="sowl-checkout clearfix {{ !empty($mobile) ? 'mobile-checkout' : '' }}">
    {{ Form::open(['id' => 'bt-form'.( isset($id) ? "-$id" : '')]) }}
        {{ Form::input('hidden', 'braintree_token', $braintreeToken ?? null) }}
        {{ Form::input('hidden', 'package_id', isset($package) ? $package->getPackageId() : '') }}
        {{ Form::input('hidden', 'free_trial', (isset($package) && $package->isFreeTrial()) ? 'true' : 'false') }}
        {{ Form::input('hidden', 'billing-agreement', isset($package) ? $package->getBillingAgreement() : '') }}
        {{ Form::input('hidden', 'tracking_params', $tracking_params ?? '') }}

        {{ Form::input('hidden', 'stripe_token') }}
        {{ Form::input('hidden', 'payment_method_nonce') }}
        {{ Form::input('hidden', 'recurly_token', null, ['data-recurly' => 'token']) }}
        {{ Form::input('hidden', 'device_data', null) }}

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
                @if (\App\Entity\PaymentMethod::isRecurly())
                <div class="row" @if (!\App\Entity\FeaturePaymentSet::config()->getShowNames()) style="display: none;" @endif>
                    <div class="input-wrapper col-sm-6 col-xs-12">
                        <div class="bt-input-container">
                            <label>First Name</label>
                            <div class="bt-input cc-first-name">
                                <input type="text" data-recurly="first_name" name="first_name" placeholder="Card holder first name" title="Card holder first name" autocomplete="on" value="{{ \Auth::user()->getProfile()->getFirstName()  }}" />
                            </div>
                        </div>
                    </div>
                    <div class="input-wrapper col-sm-6 col-xs-12">
                        <div class="bt-input-container">
                            <label>Last Name</label>
                            <div class="bt-input cc-last-name">
                                <input type="text" data-recurly="last_name" name="last_name" placeholder="Card holder last name" title="Card holder last name" autocomplete="on" value="{{ \Auth::user()->getProfile()->getLastName()  }}" />
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                @if (\App\Entity\PaymentMethod::isBraintree() || \App\Entity\PaymentMethod::isRecurly())
                <div class="row">
                    <div class="input-wrapper col-xs-12">
                        <div class="bt-input-container">
                            <label>Credit Card Number</label>
                            <div class="bt-input cc-number cc-number">
                                <input type="tel" data-braintree-name="number" placeholder="Card Number" title="Credit Card Number" autocomplete="on" />
                            </div>
                        </div>
                    </div>
                </div>
                <script type="text/javascript">
                    var payment = payment || {};
                    payment.isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

                    if(!payment.isMobile) {
                        payment.paymentInput = document.querySelector("[title='Credit Card Number']");
                        payment.paymentInput.focus();
                    }
                </script>
                <div class="row">
                    <div class="input-wrapper bt-input-container col-xs-12 col-sm-4 col-md-4">
                        <label>Expiration Date</label>
                        <div class="clearfix">
                            <div class="expiration-month pull-left">
                                <select data-braintree-name="expiration_month" title="MM">
                                    <option value="1">01</option>
                                    <option value="2">02</option>
                                    <option value="3">03</option>
                                    <option value="4">04</option>
                                    <option value="5">05</option>
                                    <option value="6">06</option>
                                    <option value="7">07</option>
                                    <option value="8">08</option>
                                    <option value="9">09</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                </select>
                            </div>
                            <span class="slash pull-left">/</span>
                            <div class="expiration-year pull-left">
                                <select data-braintree-name="expiration_year" title="YY">
                                    @for ($i = (int) date('Y'); $i < ((int) date('Y') + 20); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="input-wrapper cvv-wrapper col-xs-12 col-sm-4 col-md-4">
                        <div class="bt-input-container">
                            <label for="cc-cvv">Security Code</label>
                            <div class="bt-input cc-cvv">
                                <input type="tel" data-braintree-name="cvv" placeholder="CVV" title="CVV" />
                            </div>
                        </div>
                    </div>
                    <div class="input-wrapper col-sm-4 col-md-4">
                        <div class="pull-left cvv-explain">
                            <span class="title-explain">Security code <small>(or "CVC" or "CVV")</small></span>
                            <img src="{{ asset('assets/img/cvv.png') }}" width="50" height="35"/>
                            <p>The last 3 digits on the back of your card</p>
                        </div>
                    </div>
                </div>
                @endif
                @if (\App\Entity\PaymentMethod::isStripe())
                <div class="row">
                    <div class="input-wrapper col-xs-12">
                        <div class="bt-input-container">
                            <label>Credit Card Number</label>
                            <div class="bt-input cc-number cc-number"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="input-wrapper bt-input-container col-xs-12 col-sm-4 col-md-4">
                        <label>Expiration Date</label>
                        <div class="cc-expiry">
                            <div class="expiration-month pull-left"></div>
                            <span class="slash pull-left">/</span>
                            <div class="expiration-year pull-left"></div>
                        </div>
                    </div>
                    <div class="input-wrapper cvv-wrapper col-xs-12 col-sm-4 col-md-4">
                        <div class="bt-input-container">
                            <label for="cc-cvv">Security Code</label>
                            <div class="bt-input cc-cvv"></div>
                        </div>
                    </div>
                    <div class="input-wrapper col-sm-4 col-md-4">
                        <div class="pull-left cvv-explain">
                            <span class="title-explain">Security code <small>(or "CVC" or "CVV")</small></span>
                            <img src="{{ asset('assets/img/cvv.png') }}" width="50" height="35"/>
                            <p>The last 3 digits on the back of your card</p>
                        </div>
                    </div>
                </div>
                @endif
                <div class="row">
                    <div class="col-xs-12 text-center">
                        <span class="bt-error"></span>
                    </div>
                </div>
                <div class="row">
                    <div class="hidden-xs col-sm-2"></div>
                    <div class="col-xs-12 col-sm-8">
                        <button type="submit" class="btn btn-warning btn-continue">
                            <i class="glyphicon glyphicon-lock hidden-xs"></i>
                            <span class="message">{{ $package && $package->isFreeTrial() ? 'Activate Trial' : 'Continue' }}</span>
                            <span class="loading"><i class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></i>Processing...</span>
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
