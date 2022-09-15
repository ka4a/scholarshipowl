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

<!-- Refer a Friend header -->
<section role="region" aria-labelledby="page-title">
				<div class="section--additional-services-header blue-bg clearfix">
					<div class="container">
						<div class="row">
							<div class="text-container text-center text-white">
								<h2 class="text-large text-light" id="page-title">
									Refer a Friend
								</h2>
								<p class="text-medium">

								</p>
							</div>
						</div>
					</div>
				</div>
			</section>

<!-- Refer a Friend -->
<section role="region" aria-labelledby="">
	<div class="section--additional-services lightBlue-bg clearfix">
	    <div class="container center-block">
	        <div class="row">
	            <div class="text-container">

	                <p>Refer a Friend</p>

	            </div>
	        </div>
	    </div>
	</div>
</section>

@include('includes/refer')
@stop
