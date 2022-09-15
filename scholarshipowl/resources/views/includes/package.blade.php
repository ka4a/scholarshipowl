<div class="blue-bg"></div>
    <div class="row text-center ">
        @if ($package->isMarked())
            <div class="ribbon-wrapper-brick">
                <div class="ribbon-brick">Most popular</div>
            </div>
        @endif

        <div class="col-xs-12 col-sm-12">
            <div class="row no-gutter price-select-button-container">
                <div class="col-xs-6 col-sm-12 mod-price">
                    <div class="price">
                        <span class="priceType text-uppercase">{{ $package->getName() }}</span>
                        <div class="priceAmmount">
                            <span class="dolar">$</span>
                            <span class="ammount">{{ (int) $package->getPrice() }}</span>
                            <em>{!! $package->getRecurrentTypeMessage() !!}</em>
                        </div>
                        <div class="description">{!! $package->getDisplayMessage() !!}</div>
                    </div>
                </div>
                <div class="col-xs-6 col-sm-12 mod-select-button">
                    <div class="selectButton">
                        <a
                            id="@if ($package->isMarked()) {{ 'premiumButton' }} @else {{ 'basicButton' }} @endif"
                            class="btn @if ($package->getPrice() == 0) {{ 'btn-success' }} @else {{ 'btn-warning' }} @endif btn-block vertical-center text-uppercase {{ ((int) $package->getPrice() != 0)?"PackageButton":"btn-success" }}"
                            @if((int) $package->getPrice() == 0)
                                data-dismiss="modal"
                            @endif
                            data-package-id="{{ $package->getPackageId() }}"
                            data-package-name="{{ $package->getName() }}"
                            data-package-billing-agreement="{{ $package->getBillingAgreement() }}"
                            data-package-price="{{ $package->getPrice() }}"
                            data-package-free-trial="{{ $package->isFreeTrial() ? 'true' : 'false' }}">
                            {{ ((int) $package->getPrice() == 0)?"Continue":"Upgrade" }}
                        </a>
                    </div>
                </div>

            </div>
        </div>

        <div class="col-xs-12 col-sm-12 no-gutter-this selectWrapper">
            <div class="panel-group visible-xs-block" id="accordion" role="tablist" aria-multiselectable="true">
                <div class="panel panel-default">
                    <div class="panel-heading" role="tab" id="headingOne">
                        <h2 class="panel-title">
                            <a data-toggle="collapse" data-parent="#accordion" href="#Package_{{ $package->getPackageId() }}" aria-expanded="false" aria-controls="Package_{{ $package->getPackageId() }}">
                                <span>find out more ↓</span>
                            </a>
                        </h2>
                    </div>

                    <div id="Package_{{ $package->getPackageId() }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Package_{{ $package->getPackageId() }}">
                        <div class="panel-body">
                            <ul class="list-group">
                                <li class="list-group-item">
                                    <div class="center-block">
                                        <span class="big">@if ($package->isScholarshipsUnlimited()) {{ 'All' }} @else {{ $package->getScholarshipsCount() }} @endif</span>
                                        <span class="normal text-left">
                                            <em>
                                                @if ($package->isExpirationTypeRecurrent())
                                                    Scholarships {{ 'per ' }} {{ $package->getExpirationPeriodType() }}
                                                @else
                                                    Scholarship applications
                                                @endif
                                            </em>
                                        </span>
                                    </div>
                                </li>
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
                <li class="list-group-item">
                    <div class="center-block">
                        <span class="big">@if ($package->isScholarshipsUnlimited()) {{ 'All' }} @else {{ $package->getScholarshipsCount() }} @endif</span>
                        <span class="normal text-left">
                            <em>
                                @if ($package->isExpirationTypeRecurrent())
                                    Scholarships {{ 'per ' }} {{ $package->getExpirationPeriodType() }}
                                @else
                                    Scholarship applications
                                @endif
                            </em>
                        </span>
                    </div>
                </li>
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
