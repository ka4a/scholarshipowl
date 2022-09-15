@extends('base')

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')
<div style="height: 94vh" id="winners-vue-bind-point"></div>
@stop
