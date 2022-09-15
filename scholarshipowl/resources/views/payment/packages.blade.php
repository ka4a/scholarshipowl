@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle22') !!}
@endsection

@section("scripts")

@endsection

@section("scripts2")
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
	{!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')


<section id="payment" class="blue-bg clearfix">
	<div class="container">
		<div class="row">
			<div class="text">
				<h2 class="description">We have sent applications to the scholarships you selected</h2>
				<h2 class="title">
					Want more?
				</h2>
				<div class="prevNext">
					<div id="down" href="#" class="prevNextBtn text-center">
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
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="selectPayment" class="paleBlue-bg">
	<div class="container">
		<div class="row">
			<div id="packages" class="col-sm-12 col-md-9">
				<div class="row">
          <div>marker</div>
					<form action="payment-form" method="post">
					{{ Form::token() }}
					{{ Form::hidden("package", "") }}
					{{ Form::hidden("price", "") }}
					{{ Form::hidden("account_id", $user->getAccountId()) }}

					@foreach ($packages as $packageId => $package)
						<div class="col-sm-3 col-md-3 package @if ($package->isMarked()) {{ 'active' }} @endif">
							<div class="blue-bg"></div>
							<div class="row text-center">
								@if ($package->isMarked())
									<div class="ribbon-wrapper-brick">
										<div class="ribbon-brick">Most popular</div>
									</div>
								@endif

								<div class="price">
									<span class="priceType">{{ $package->getName() }}</span>
									<div class="priceAmmount">
										<span class="dolar">$</span>
										<span class="ammount">{{ (int) $package->getPrice() }}</span>
									</div>
								</div>

								<div class="selectButton">
									<button type="button"
										id="@if ($package->isMarked()) [{ 'premiumButton' }} @else {{ 'basicButton' }} @endif"
										class="btn btn-success btn-block vertical-center  text-uppercase"
										data-package-id="{{ $package->getPackageId() }}"
										data-tracking-params="{{ $tracking_params }}"
										data-package-name="{{ $package->getName() }}"
                                        data-package-billing-agreement="{{ $package->getBillingAgreement() }}"
										data-package-price="{{ $package->getPrice() }}"
                                        data-package-free-trial="{{ $package->isFreeTrial() ? 'true' : 'false' }}"
									>
									Select
									</button>
								</div>

								<div class="selectWrapper">
									<div class="panel-group visible-xs-block" id="accordion" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<div class="panel-title">
								        			<a data-toggle="collapse" data-parent="#accordion" href="#Package_{{ $packageId }}" aria-expanded="false" aria-controls="Package_{{ $packageId }}">
								          				<span>find out more ↓</span>
								        			</a>
								      			</div>
											</div>

											<div id="Package_{{ $packageId }}" class="panel-collapse collapse" role="tabpanel" aria-labelledby="Package_{{ $packageId }}">
												<div class="panel-body">
													<ul class="list-group">
														<li class="list-group-item">
															<div class="center-block">
																<span class="big">@if ($package->isScholarshipsUnlimited()) {{ 'All' }} @else {{ $package->getScholarshipsCount() }} @endif</span>
																<span class="normal">Scholarship applications</span>
															</div>
														</li>

														@foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
															<li class="list-group-item" data-package-id="{{ $packageId }}">
																{{ $item }}
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
												<span class="normal">Scholarship applications</span>
											</div>
										</li>

										@foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
											<li class="list-group-item" data-package-id="{{ $packageId }}">
												{{ $item }}
											</li>
										@endforeach
									</ul>
								</div>
							</div>
						</div>
					@endforeach
				</form>
				</div>
			</div>

			<div class="col-md-3 ">
				<div class="row">
					<div class="payment">
						<div class="upsale-wrapper center-block">
							<div class="col-sm-6 col-md-12">
								<div class="upsale">
									<div class="bars">
										<div class="text text-uppercase">
											<span class="text1-b">
												<strong>
												Over 90% of respondents rated the sign up process as easy and intuitive.
												</strong>
											</span>
										</div>
									</div>
								</div>
							</div>

							<div class="col-sm-6 col-md-12">
								<div class="upsale">
									<div class="bars">
										<div class="text text-uppercase">
											<strong>83% of respondents wrote:</strong>
										</div>
										<div class="text1 text-uppercase">
											<span class="text1-a">
												"Being automatically applied to multiple scholarships is a huge advantage "
											</span>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div class="clearfix"></div>


			<div id="paymentForm" class="wrapper">
				<div class="row hidden" id="PackageOptionsContainer">
					<div class="col-xs-12 col-sm-10 col-sm-offset-1">
						@foreach ($packages as $packageId => $package)
							<div class="PackageOptions hidden" data-package-id="{{ $packageId }}">
								<div class="row selected clearfix">
									<div class="col-xs-5 pull-left">
										<big class="bold">You've selected</big>
									</div>

									<div class="col-xs-7 pull-right text-right">
										<big class="bold"><span class="text-uppercase">{{ $package->getName() }}</span> package for ${{ $package->getPrice() }}</big>
									</div>

									<ul class="list-unstyled col-xs-12">

										<li class="col-xs-12 col-sm-6">

											<span class="glyphicon glyphicon-ok vertical-center"></span><span class="horizontal-border"></span>
											@if ($package->isScholarshipsUnlimited()) {{ 'All' }} @else {{ $package->getScholarshipsCount() }} @endif
											Scholarship applications

										</li>

									@foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
											<li class="col-xs-12 col-sm-6">

												<span class="glyphicon glyphicon-ok vertical-center"></span><span class="horizontal-border"></span> {{ trim($item) }}

											</li>
									@endforeach
									</ul>
								</div>
							</div>
						@endforeach

						<div class="row checkout">
							<big class="bold">Checkout with</big>
							<ul class="list-unstyled clearfix">
								<li class="col-xs-12 col-sm-5">
									<span class="btn btn-default btn-lg btn-block gradient" role="button">
										@include ("includes/paypal")
									</span>
								</li>
								<li class="col-xs-12 col-sm-2 text-center">
									<span class="text-center img-circle or vertical-center">or</span>
								</li>
								<li class="col-xs-12 col-sm-5">
									<span class="btn btn-default btn-lg btn-block gradient @if (empty($missing_data)) {{ 'PaymentFormButton' }} @else {{ 'MissingPaymentDataButton' }} @endif" role="button" data-package-id="" data-tracking-params="">
										<span class="creditcards"></span>
										<span class="ccText text-left vertical-center">
											Credit <br class="visible-xs visible-sm" />Card
										</span>
									</span>
								</li>
							</ul>
						</div>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-10 col-sm-offset-1">
						<div id="Gate2ShopTop">&nbsp;</div>

						<div class="row" id="Gate2ShopForm"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>



@if (!empty($error))
	@if ($error == "payment_fail" || $error == "paypal")
		<div id="NotificationBody">
			<p>There was an error processing your payment.</p>
		</div>

		<div id="NotificationFooter">
			<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>
		</div>
	@endif
@endif



@include ('includes/popup')

@stop
