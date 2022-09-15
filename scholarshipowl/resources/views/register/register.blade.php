@extends('base')

@php $metaData = 'ScholarshipOwl Registration'; @endphp
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

@section('content')
  <div id="vue-register1-form"></div>
  @include('includes/marketing/mixpanel_pageview')
@stop
