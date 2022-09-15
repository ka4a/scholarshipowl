@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle6') !!}
@endsection

@section("scripts2")
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle4') !!}
@endsection

@section('content')
    tralala
@stop
