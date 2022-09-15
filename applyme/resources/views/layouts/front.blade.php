<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
@include('layouts._front-head')
<body class="@yield('page-name', 'page')">
    @if(env('APP_ENV') == 'production')
        @include('front.components.analytic._google-tag-manager-body')
    @endif
    <div id="page-wrapper">
        @yield('content')
        @include('front.components.footer._default')
        <div class="hidden-xs hidden-sm">
            @include('front.components.nav._default')
        </div>
        <div class="hidden-md hidden-lg">
            @include('front.components.nav._mobile')
            @include('front.components.nav._aside-menu')
        </div>
        @include('layouts._front-tail')
    </div>
</body>
</html>
