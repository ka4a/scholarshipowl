@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('mainStyle') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('social') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<!-- Premium Services header -->
<section role="region" aria-labelledby="page-title">
			<div class="section--additional-services-header blue-bg clearfix">
				<div class="container">
					<div class="row">
						<div class="text-container text-center text-white">
							<h2 class="h2 text-light" id="page-title">Premium Services</h2>
							<p class="lead mod-top-header">

							</p>
						</div>
					</div>
				</div>
			</div>
		</section>

<!-- Premium Services -->
<section role="region" aria-labelledby="">
	<div class="section--additional-services lightBlue-bg clearfix">
	    <div class="container center-block">
	        <div class="row">
	            <div class="text-container">

	                <p>Premium Services</p>

	            </div>
	        </div>
	    </div>
	</div>
</section>

@include('includes/refer')
@stop
