@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle23') !!}
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
				<h2 class="title">We have sent applications to the scholarships you selected</h2>
				<p class="subhead">
					Want more?
				</p>
			</div>
		</div>
	</div>
</section>

<section id="selectPayment" class="paleBlue-bg">
	<div class="container">
		<div class="row">
			<div class="col-sm-12 col-md-9 pull-left">
				<div class="row">
					<form action="payment-form" method="post">
					{{ Form::token() }}
					{{ Form::hidden("package", "") }}
					{{ Form::hidden("price", "") }}

					@foreach ($packages as $packageId => $package)
						<div class="col-sm-3 col-md-3 package">
							<div class="row text-center @if ($package->isMarked()) {{ 'active' }} @endif">
								@if ($package->isMarked())
									<div class="ribbon-wrapper-brick">
										<div class="ribbon-brick">Most popular</div>
									</div>
								@endif

								<div class="price">
									<span class="priceType">{{ $package->getName() }}</span>
									<div class="priceAmmount">
										<span class="dolar">$</span><span class="ammount">{{ (int) $package->getPrice() }}</span>
									</div>
								</div>

								<div class="selectWrapper">
									<div class="panel-group visible-xs-block" id="accordion" role="tablist" aria-multiselectable="true">
										<div class="panel panel-default">
											<div class="panel-heading" role="tab" id="headingOne">
												<h4 class="panel-title">
								        			<a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
								          				find out more +
								        			</a>
								      			</h4>
											</div>

											<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
												<div class="panel-body">
													<ul class="list-group">
														<li class="list-group-item">
															<div class="center-block">
																<span class="big">@if ($package->isScholarshipsUnlimited()) {{ 'All' }} @else {{ $package->getScholarshipsCount() }} @endif</span>
																<span class="normal">Scholarship applications</span>
															</div>
														</li>

														@foreach (explode(PHP_EOL, $package->getDisplayDescription()) as $item)
															<li class="list-group-item">
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
											<li class="list-group-item">
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

			<div class="col-md-3 push-right hidden-xs hidden-sm">
				<div class="">
					<div class="upsale-wrapper center-block">
						<div class="upsale">
							<div class="bars">
								<div class="title">Click here to ask
									<div class="text1">
										Your parents to
									</div>
								</div>
								<div class="text text-uppercase">
									<span class="text1-a">
										<strong>
											Pay or schedule
										</strong>
									</span>
								</div>
								<div class="text1 text-uppercase">
									<span class="text1-a">
										a callback
									</span>
								</div>
							</div>
						</div>
						<div class="upsale">
							<div class="bars">
								<div class="title">Upsale 1</div>
								<div class="text text-uppercase">
									<strong>Did you know</strong>
								</div>
								<div class="text1 text-uppercase">
									<span class="text1-a">
										Essay writting webinars
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>

			<div class="col-md-3 push-right visible-xs-block visible-sm-block">
				<div class="row">
					<div class="upsale-wrapper center-block">
						<div class="upsale">
							<div class="bars">
								<div class="title">Click here to ask
									<div class="text1">
										Your parents to
									</div>
								</div>
								<div class="text text-uppercase">
									<span class="text1-a">
										<strong>
											Pay or schedule
										</strong>
									</span>
								</div>
								<div class="text1 text-uppercase">
									<span class="text1-a">
										a callback
									</span>
								</div>
							</div>
						</div>
						<div class="upsale">
							<div class="bars">
								<div class="title">Upsale 1</div>
								<div class="text text-uppercase">
									<strong>Did you know</strong>
								</div>
								<div class="text1 text-uppercase">
									<span class="text1-a">
										Essay writting webinars
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section id="note">
	<div class="container-fluid">
		<div class="row">
			<div class="container">
				<div class="row">
					<div class="col-xs-10 col-xs-offset-1 text-center note">We will credit your payment towards any additional service booked with us - e.g. college advisory, webinars, essays prep</div>
				</div>
			</div>
		</div>
	</div>
</section>

<section>
	<div class="container-fluid">
		<div class="row">
			<div class="container">
				<div class="row" id="Gate2ShopForm">

				</div>
			</div>
		</div>
	</div>
</section>

@stop
