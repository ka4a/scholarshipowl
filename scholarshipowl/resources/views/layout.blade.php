<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,600,700|Raleway:900" rel="stylesheet">

    @include('includes.google-tag-manager')
    @include('includes.marketing.mixpanel')

    {{--        @if (is_production())--}}
    @include('includes/marketing/pushnami')
    {{--        @endif--}}

    @if (is_production())
        @include('common.sentry')
        @include('includes/optimizely')
    @endif

    {!! \App\Extensions\AssetsHelper::getCSSBundle('iconFont') !!}

    @section("metatags")
        <meta name="description" content="{{ \CMS::description() }}" />
        <meta name="keyword" content="{{ \CMS::keywords() }}" />
        <meta name="author" content="{{ \CMS::author() }}" />
    @show

    <meta property="fb:app_id" content="your_app_id" />
    <meta property="og:site_name" content="ScholarshipOwl" />
    <meta property="og:url" content="{{ Request::url() }}" />
    <meta property="og:title" content="{{ \CMS::title() }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ \CMS::description() }}" />

    <meta name="twitter:card" content="summary" />
    <meta name="twitter:site" content="@ScholarshipOwl" />
    <meta name="twitter:title" content="{{ \CMS::title() }}" />
    <meta name="twitter:description" content="{{ \CMS::description() }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />

    <title>{{ \CMS::title() }}</title>

    @include('common.configs')
    @include('common.webstorage')
    @include('common.notifications')

</head>
<body style="margin: 0; line-height: 1;">
@yield('before.body')
    <div id="scholarships-root" style="height: 100%"></div>
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundleExternal') !!}
@yield('before.body.end')
</body>
</html>
