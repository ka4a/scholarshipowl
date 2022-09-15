@extends('base')

@section("styles")
	<link media="all" type="text/css" rel="stylesheet" href="{{ asset("assets/css/style.css") }}">
@endsection


@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

<section id="payment" class="blue-bg clearfix">
	<div class="container">
		<div class="row">
			<div class="text">
				<h2 class="title">My Packages</h2>
			</div>
		</div>
	</div>
</section>

<div class="container center-block">
	<div class="row">
		<p>Package: {{ $subscription->getName() }}</p>
		<p>Price: {{ $subscription->getPrice() }}</p>
		<p>
			Scholarships Count:
			@if ($subscription->isScholarshipsUnlimited())
				{{ 'UNLIMITED' }}
			@else
				{{ $subscription->getScholarshipsCount() }}
			@endif
		</p>
	</div>
</div>

@stop
