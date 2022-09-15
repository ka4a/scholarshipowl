@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle29') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('testimonials') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('offerWall') !!}
@endsection

@section('header')
  <style>
      #vue-header {
          min-height: 58px;
          background-color: white;
          box-shadow: 0 2px 3px rgba(0,0,0,.25);
          width: 100%;
          z-index: 100;
      }
  </style>
  <div id="vue-header"></div>
@endsection

@section('content')
  <div class="thank-you-page">
    <div class="container">
      <div class="row">
        <div class="col-lg-12 congrats-text">
          <h1>{{ $offerWall->getTitle() }}</h1>
          <h5>{{ $offerWall->getDescription() }}</h5>
        </div>
        <div class="col-lg-6 col-lg-offset-3 dotted"></div>
      </div>
      <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
          @foreach($offerWall->getBanners() as $index => $banner)
            <li data-target="#carousel-example-generic" data-slide-to="{{ $index }}" class="{{ $index === 0 ? 'active' : '' }}"></li>
          @endforeach
        </ol>

                <!-- Wrapper for slides -->
            <div class="carousel-inner" role="listbox">
                @foreach($offerWall->getBanners() as $index => $banner)
                    <div class="item {{ $index === 0 ? 'active' : '' }}">
                        <div class="col-lg-4 col-sm-12">
                            @include('common.banners.banner', ['banner' => $banner])
                        </div>
                    </div>
                @endforeach
            </div>
      </div>
      <div class="row offers-tablet-desktop">
          @foreach($offerWall->getBanners() as $banner)
              <div class="col-lg-4 col-sm-6">
                  @include('common.banners.banner', ['banner' => $banner])
              </div>
          @endforeach
      </div>
    </div>
  </div>

<!-- Testimonials -->
@include('includes.testimonials')
@include('includes.refer')

@stop
