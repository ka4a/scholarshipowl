<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">

    @include('common.webstorage-optimized')

    @include('includes.marketing.mixpanel')

    @if (is_production())
        @include('includes/optimizely')
    @endif

    @section("metatags")
        <meta name="description" content="{{ \CMS::description() }}" />
        <meta name="keyword" content="{{ \CMS::keywords() }}" />
        <meta name="author" content="{{ \CMS::author() }}" />
    @show

    @section('metatitle')
        <title>{{ \CMS::title() }}</title>
        <meta property="og:title" content="{{ \CMS::title() }}" />
        <meta name="twitter:title" content="{{ \CMS::title() }}" />
    @show

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta property="fb:app_id" content="your_app_id" />
    <meta property="og:site_name" content="ScholarshipOwl" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ \CMS::description() }}" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@ScholarshipOwl" />
    <meta name="twitter:description" content="{{ \CMS::description() }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700,800|Raleway:300,700,900" rel="stylesheet">

    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle35') !!}
</head>
<body>
    @yield('before.body')
    <div id="app" style="height: 100%"></div>

    {!! \App\Extensions\AssetsHelper::getJSBundle('bundleAccountMobile') !!}

    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle28') !!}
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif


    @include('common.configs')
    @yield('before.body.end')
</body>
</html>
