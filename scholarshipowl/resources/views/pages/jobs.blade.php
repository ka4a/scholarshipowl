@extends('base')

@php $metaData = 'ScholarshipOwl Job Board'; @endphp
@section('metatitle')
    <title>{{ $metaData }}</title>
    <meta property="og:title" content="{{ $metaData }}" />
    <meta name="twitter:title" content="{{ $metaData }}" />
@endsection

@section("metatags")
    <meta name="description" content="{{ $metaData }}" />
    <meta name="keyword" content="{{ \CMS::keywords() }}" />
    <meta name="author" content="{{ \CMS::author() }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ $metaData }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
    <meta name="twitter:description" content="{{ $metaData }}" />
@endsection

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('jobs') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')
  <section id="jobs-vue-bind-point"></section>
@stop
