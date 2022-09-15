<!-- STEP 2 -->
<div id="step2" class="tab-pane fade in">

<div class="modal-header clearfix">
<button type="button" class="close img-circle text-center" data-dismiss="modal">
  <span aria-hidden="true">×</span>
  <span class="sr-only">Close</span>
</button>
</div>

<!-- modal body -->
<div class="modal-body col-xs-12 text-left clearfix">


<!-- Payment form -->
<div id="paymentForm" class="wrapper">
    <div class="row" id="PackageOptionsContainer">
        <div class="col-xs-12 col-sm-10 col-sm-offset-1">
            @foreach ($allVisiblePackages as $packageId => $package)
            <div class="PackageOptions" data-package-id="{!! $package->getPackageId() !!}">
                <div class="row selected clearfix">
                    <div class="col-xs-5 col-sm-4 pull-left">
                        <div class="text-md bold">You've selected</div>
                    </div>

                    <div class="col-xs-7 col-sm-8 pull-right text-right">
                        <div class="text-md bold">
                            @if((int) $package->getDiscountPrice() !== 0 )
                                ${!! intval($package->getDiscountPrice()) !!} for your first month, then only ${!! $package->getPricePerMonth() !!} per month. {!! $package->getName() !!}
                            @else
                                @if($package->isFreeTrial())
                                    {!! $package->getFreeTrialPeriodText() !!} free, then only ${!! $package->getPricePerMonth() !!} per month. {!! $package->getName() !!}
                                @elseif ($package->isExpirationTypeRecurrent() && $package->getPricePerMonth())
                                    <span class="text-uppercase">{!! $package->getName() !!}</span> membership for ${!! $package->getPricePerMonth() !!} per month
                                @else
                                    <span class="text-uppercase">{!! $package->getName() !!}</span> package for ${!! (int) $package->getPrice() !!}
                                @endif
                            @endif
                        </div>
                        @if ((float) $package->getPricePerMonth() !== (float) $package->getPrice())
                            <div class="billed-period text-lowercase text-light text-sm">
                                (${!! $package->getPrice() !!} {!! $package->getRecurrentTypeMessageFull() !!})
                            </div>
                        @endif
                    </div>

                    <div class="list-unstyled whatYouGetCont col-xs-12">
                         @foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
                                <div class="col-xs-12 col-sm-6 whatYouGet">
                                    <span class="horizontal-border">
                                    </span>
                                        <span class="selected-package">{!! trim($item) !!}</span>
                                    <span class="glyphicon glyphicon-ok vertical-center"></span>
                                </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endforeach

                @if (isset($conversionPagePackage))
                    <div class="PackageOptions" data-package-id="{!! $conversionPagePackage->getPackageId() !!}">
                        <div class="row selected clearfix">
                            <div class="col-xs-5 col-sm-4 pull-left">
                                <div class="text-md bold">You've selected</div>
                            </div>

                            <div class="col-xs-7 col-sm-8 pull-right text-right">
                                @if ($conversionPagePackage->isRecurrent() && $conversionPagePackage->getPricePerMonth())
                                    <div class="text-md bold">
                                        <span class="text-uppercase">{!! $conversionPagePackage->getName() !!}</span> membership for ${!! $conversionPagePackage->getPricePerMonth() !!} per month
                                    </div>
                                    @if ((float) $conversionPagePackage->getPricePerMonth() !== (float) $conversionPagePackage->getPrice())
                                        <div class="billed-period text-lowercase text-light text-sm">
                                            (${!! $conversionPagePackage->getPrice() !!} {!! $conversionPagePackage->getRecurrentTypeMessageFull() !!})
                                        </div>
                                    @endif
                                @else
                                    <div class="text-md bold">
                                        <span class="text-uppercase">{!! $conversionPagePackage->getName() !!}</span> package for ${!! (int) $conversionPagePackage->getPrice() !!}
                                    </div>
                                @endif
                            </div>

                            <div class="list-unstyled whatYouGetCont col-xs-12">
                                @foreach (explode(PHP_EOL, $conversionPagePackage->getDisplayDescription()) as $item)
                                    <div class="col-xs-12 col-sm-6 whatYouGet">
                                    <span class="horizontal-border">
                                    </span>
                                        <span class="selected-package">{!! trim($item) !!}</span>
                                        <span class="glyphicon glyphicon-ok vertical-center"></span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                @include('payment.braintree.checkout')
                @if (setting_on('disclaimer.enabled'))
                    <p class="text-center disclaimer">{!! setting('disclaimer.text')  !!}</p>
                @endif
          </div>
      </div>
</div>



<!-- /Payment form -->

</div>
<!-- /Modal body -->

<!-- modal footer -->
<div class="modal-footer col-xs-12 ">

  <div class="row">
      <div class="col-xs-4 col-sm-2">

              <div class="prevNext mod-payment">
                      <a id="previous" href="#" class="prevNextBtn text-right pull-left backToBeginning">
                              <span>
                                  Back
                              </span>
                              <div class="arrow-btn">
                                      <div class="arrow">
                                              <span class="a1"></span>
                                              <span class="a2"></span>
                                              <span class="a3"></span>
                                              <span class="a4"></span>
                                              <span class="a5"></span>
                                              <span class="a6"></span>
                                              <span class="a7"></span>
                                              <span class="a8"></span>
                                              <span class="a9"></span>
                                              <span class="a10"></span>
                                              <span class="a11"></span>
                                      </div>
                              </div>
                      </a>
              </div>

      </div>
      <div class="col-xs-8 col-sm-10">
          <p class="text-left">
          </p>
      </div>
  </div>
</div>
<!-- /modal footer -->

</div>
<!-- step 2 -->
