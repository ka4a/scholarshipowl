@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle24') !!}
@endsection

@section("scripts2")
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
	{!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<div class="mobile">

	<div class="mobile-header blue-bg">
		<div class="container">
			<div class="row">
				<div class="text text-center">
					<h2 class="title">ScholarshipOwl</h2>
					<p class="description">
						Lorem ipsum dolor sit amet, consectetur adipisicing elit. Voluptate perferendis non eligendi reprehenderit tenetur.
		    	</p>
			   </div>
			</div>
		</div>
	</div>

	<div class="container mod-container">

		<!-- Junior -->
		<div id="junior" class="row package-container">
			<div class="col-xs-12">
				<div class="row package-container-inner">
					<div class="col-xs-7">
						<div class="package-name">
							<span class="text-uppercase">Junior</span>
						</div>
					</div>
					<div class="col-xs-5 text-right">
						<span class="price">
							$0
						</span>
						<span class="per-month">per month</span>
					</div>

					<div class="col-xs-12">
						<p class="description text-center">
							Unlimited applications for 12 months
						</p>
					</div>

				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-xs-12">
				<ul class="list-group package-list">
					<li class="list-group-item">
						<div class="row">
							<div class="col-xs-3 text-right mod-border">
								<span class="big-number">
									<em>3</em>
								</span>
							</div>
							<div class="col-xs-9 mod-item">
								<em>Scholarship applications</em>
							</div>
						</div>
					</li>
					<li class="list-group-item">Item 2</li>
					<li class="list-group-item">Item 3</li>
				</ul>
			</div>
		</div>

		<!-- Pro -->
		<div id="pro" class="row package-container">
			<div class="col-xs-12">
				<div class="row package-container-inner">

					<div class="col-xs-7">
						<div class="package-name">
							<span class="text-uppercase">Pro</span>
						</div>
					</div>
					<div class="col-xs-5 text-right">
						<span class="price">
							$39.99
						</span>
						<span class="per-month">per month</span>
					</div>
					<div class="col-xs-12">
						<p class="description text-center">
							Unlimited applications for 12 months
						</p>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<ul class="list-group package-list">
							<li class="list-group-item">
								<div class="row">
									<div class="col-xs-3 text-right mod-border">
										<span class="big-number">
											<em>3</em>
										</span>
									</div>
									<div class="col-xs-9 mod-item">
										<em>Scholarship applications</em>
									</div>
								</div>
							</li>
							<li class="list-group-item">Item 2</li>
							<li class="list-group-item">Item 3</li>
							<li class="list-group-item">Item 4</li>
						</ul>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="row buttons-container-inner">

							<div class="col-xs-6 first-child mod-gutter">
					      <button class="btn btn-default btn-lg btn-block btn-payment paypal" role="button">
					        paypal
					      </button>
							</div>
							<div class="col-xs-6 second-child mod-gutter">
					      <button class="btn btn-default btn-lg btn-block btn-payment credit-card" role="button">
				          <span class="text-center">
				            <em>Credit Card</em>
				          </span>
					      </button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<!-- Premium -->
		<div id="premium" class="row package-container">
			<div class="col-xs-12">
				<div class="row package-container-inner">
					<div class="col-xs-7">
						<div class="package-name">
							<span class="text-uppercase">Premium</span>
						</div>
					</div>
					<div class="col-xs-5 text-right">
						<span class="price">
							$39.99
						</span>
						<span class="per-month">per month</span>
					</div>
					<div class="col-xs-12">
						<p class="description text-center">
							Unlimited applications for 12 months
						</p>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<ul class="list-group package-list">
							<li class="list-group-item">
								<div class="row">
									<div class="col-xs-3 text-right mod-border">
										<span class="big-number">
											<em>3</em>
										</span>
									</div>
									<div class="col-xs-9 mod-item">
										<em>Scholarship applications</em>
									</div>
								</div>
							</li>
							<li class="list-group-item">Item 2</li>
							<li class="list-group-item">Item 3</li>
							<li class="list-group-item">Item 4</li>
							<li class="list-group-item">Item 5</li>
						</ul>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="row buttons-container-inner">

							<div class="col-xs-6 first-child mod-gutter">
					      <button class="btn btn-default btn-lg btn-block btn-payment paypal" role="button">
					        paypal
					      </button>
							</div>
							<div class="col-xs-6 second-child mod-gutter">
					      <button class="btn btn-default btn-lg btn-block btn-payment credit-card" role="button">
				          <span class="text-center">
				            <em>Credit Card</em>
				          </span>
					      </button>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>


		<!-- Full Access -->
		<div id="full-access" class="row package-container">
			<div class="col-xs-12">
				<div class="row package-container-inner">

					<div class="col-xs-7 first-child mod-gutter">
						<div class="package-name">
							<span class="text-uppercase">Full</span> Access
						</div>
					</div>
					<div class="col-xs-5 second-child text-right">
						<span class="price">
							$99.99
						</span>
						<span class="per-month">per month</span>
					</div>
					<div class="col-xs-12">
						<p class="description text-center">
							Unlimited applications for 12 months
						</p>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<ul class="list-group package-list">
							<li class="list-group-item">
								<div class="row">
									<div class="col-xs-3 text-right mod-border">
										<span class="big-number">
											<em>All</em>
										</span>
									</div>
									<div class="col-xs-9 mod-item">
										<em>Scholarship applications</em>
									</div>
								</div>
							</li>
							<li class="list-group-item">Item 2</li>
							<li class="list-group-item">Item 3</li>
							<li class="list-group-item">Item 4</li>
							<li class="list-group-item">Item 5</li>
							<li class="list-group-item">Item 6</li>
							<li class="list-group-item">Item 7</li>
						</ul>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12">
						<div class="row buttons-container-inner">

							<div class="col-xs-6 first-child mod-gutter">
					      <button class="btn btn-default btn-lg btn-block btn-payment paypal" role="button">
					        paypal
					      </button>
							</div>
							<div class="col-xs-6 second-child mod-gutter">
					      <button class="btn btn-default btn-lg btn-block btn-payment credit-card text-center" role="button">
				            <em>Credit Card</em>
					      </button>
							</div>

						</div>
					</div>
				</div>

			</div>
		</div>


	</div>
</div>

@stop
