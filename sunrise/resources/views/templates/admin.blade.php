<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token()  }}">
    <meta name="google-signin-client_id" content="{{ config('service.google.client_id')  }}">
    <title>Sunrise - Scholarships Management</title>
    <link rel="icon" type="image/png" href="/favicon_16-16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/favicon_32-32.png" sizes="32x32">
    <link rel="stylesheet" href="//fonts.googleapis.com/icon?family=Material+Icons">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.5.94/css/materialdesignicons.min.css">
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Assistant" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">

    @include('tracking.sentry-io')
    @include('tracking.head-google-tag-manager')

    <script src="//apis.google.com/js/platform.js" async defer></script>
    <style>
        #preloader {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            z-index: 9999;
            background: #fff;
        }

        #preloader .spinner {
            margin: auto;
            width: 280px;
            height: 280px;
            position: relative;
            top: 30%;
            text-align: center;
            animation: sk-rotate 2.0s infinite linear;
        }

        #preloader .dot1,
        #preloader .dot2,
        #preloader .dot3 {
            width: 30%;
            height: 30%;
            display: inline-block;
            position: absolute;
            top: 0;
            background: linear-gradient(259.63deg, #D73148 7.32%, #EB6668 92.68%);
            border-radius: 100%;
            animation: sk-bounce 3.0s infinite ease-in;
        }

        #preloader .dot2 {
            top: 35%;
            margin-left: 30px;
            animation-delay: -1.0s;
        }

        #preloader .dot3 {
            top: auto;
            bottom: 0;
            animation-delay: -2.0s;
        }

        @keyframes sk-rotate {
            100% {
                transform: rotate(360deg);
                -webkit-transform: rotate(360deg)
            }
        }

        @keyframes sk-bounce {
            0%,
            100% {
                transform: scale(0.0);
                -webkit-transform: scale(0.0);
            }
            50% {
                transform: scale(1.0);
                -webkit-transform: scale(1.0);
            }
        }
    </style>
</head>
<body>
@include('tracking.body-google-tag-manager')
<div id="app">
    <div id="preloader">
        <div class="spinner">
            <div class="dot1"></div>
            <div class="dot2"></div>
            <div class="dot3"></div>
        </div>
    </div>
</div>
</body>
</html>
