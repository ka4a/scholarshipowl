@extends('base')

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('reset') !!}
@endsection

<div id="mailbox-root"></div>