<div class="col-xs-12 package @if($package->isContactUs()) package-contact @endif @if ($package->getIsMarked()) {!! 'active' !!} @endif">
    <div class="blue-bg"></div>
    <div class="row text-center ">
        @if ($package->getIsMarked())
        <div class="ribbon-wrapper-brick">
            <div class="ribbon-brick">Most popular</div>
        </div>
        @endif

        <div class="col-xs-12 col-sm-12">
            <div class="row no-gutter price-select-button-container">
                <div class="col-xs-6 col-sm-12 mod-price {!! $package->getExpirationType() !!}Package">
                    <div class="price">
                        <span class="priceType text-uppercase">{!! $package->getName() !!}</span>
                        <div class="priceAmmount">
                            @if ((int) $package->getPrice() !== 0)
                                <span class="dolar">$</span>
                                <span class="ammount">
                                    @if((int) $package->getDiscountPrice() !== 0 )
                                        {!! $package->getDiscountPrice() !!}
                                    @else
                                        @if ($package->isExpirationTypeRecurrent() && $package->getPricePerMonth())
                                            {!! $package->getPricePerMonth() !!}
                                            <sup class="billed-period">/mo</sup>
                                        @else
                                            {!! (int) $package->getPrice() !!}
                                        @endif
                                    @endif
                                </span>
                            @else
                                <span class="ammount">FREE</span>
                            @endif
                        </div>
                        @if((int) $package->getDiscountPrice() === 0 )
                            <div class="description">
                                {!! $package->getRecurrentTypeMessageFull() !!}
                            </div>
                        @endif
                    </div>
                </div>
                <div class="col-xs-6 col-sm-12 mod-select-button">
                    <div class="selectButton">
                        <a
                        id="@if ($package->getIsMarked()) {!! 'premiumButton' !!} @else {!! 'basicButton' !!} @endif"
                        class="btn btn-warning btn-block text-uppercase {!! ((int) $package->getPrice() != 0)?"PackageButton":"" !!} {!! $package->isFreemium() ? 'GetFreemiumButton' : '' !!}"
                        data-package-id="{!! $package->getPackageId() !!}"
                        data-tracking-params="{!! $tracking_params !!}"
                        data-package-name="{!! $package->getName() !!}"
                        data-package-billing-agreement="{!! $package->getBillingAgreement() !!}"
                        @if ($package->isContactUs()) data-package-type="contact us" @endif
                        data-contact-us-link="{!! $package->getContactUsLink() !!}"
                        data-package-expiration="{!! $package->getExpirationType() !!}"
                        data-package-period-type="{!! $package->getPaypalExpirationPeriodType() !!}"
                        data-package-period-duration="{!! $package->getExpirationPeriodValue() !!}"

                        data-package-cc-recurrent="{!! ($package->getG2SProductId() && $package->getG2STemplateId()) ? 'on' : 'off' !!}"

                        data-package-price="{!! $package->getPrice() !!}"
                        data-package-free-trial="{!! $package->isFreeTrial() ? 'true' : 'false' !!}"
                        style="{!! $package->getButtonCssForPackage() ?? '' !!}">
                            {!! $package->getButtonTextForPackage() != '' ? $package->getButtonTextForPackage() : (((int) $package->getPrice() == 0)?"Continue":"Upgrade") !!}
                        </a>
                        <div class="general-msg-container">
                            <span class="general-message">{!! $package->getDisplayMessage() !!}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xs-12 col-sm-12 no-gutter-this selectWrapper">
            <div class="panel-group visible-xs-block" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#Package_{!! $packageId !!}" aria-expanded="false" aria-controls="Package_{!! $packageId !!}">
                                <span>find out more ↓</span>
                            </a>
                        </h2>
                    </div>

                    <div id="Package_{!! $packageId !!}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Package_{!! $packageId !!}">
                        <div class="panel-body">
                            <ul class="list-group">
                                @foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
                                <li class="list-group-item">
                                    {!! $item !!}
                                </li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <ul class="list-group hidden-xs">

                @foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
                <li class="list-group-item">
                    {!! $item !!}
                </li>
                @endforeach
            </ul>

        </div>
        <!-- /selectWrapper -->

    </div>
</div>
