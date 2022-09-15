<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>@yield('meta-title', 'Apply.me - Your One-Stop Shop For College Prep & Scholarships')</title>
    <meta name="description" content="@yield('meta-description', "Apply.me guides you through the process of applying to college. Admissions coaching, essay review, interview prep, scholarship matching & more. Join us today!")">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="application-name" content="Apply.me">
    <meta name="apple-mobile-web-app-title" content="Apply.me">
    <link rel="canonical" href="{{ config('app.url') }}">
    <link rel="stylesheet" href="{{ mix('css/libs.css') }}">
    <link rel="stylesheet" href="{{ mix('css/front.css') }}">
    @yield('inline-css')
    @if(config('app.env') == 'production')
        @include('front.components.analytic._google-tag-manager-head')
    @endif
</head>
