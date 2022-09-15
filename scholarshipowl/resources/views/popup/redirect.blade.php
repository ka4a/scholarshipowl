<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <title>Redirecting...</title>
    <link rel="apple-touch-icon" sizes="57x57" href="/apple-touch-icon-57x57.png">
    <link rel="apple-touch-icon" sizes="60x60" href="/apple-touch-icon-60x60.png">
    <link rel="apple-touch-icon" sizes="72x72" href="/apple-touch-icon-72x72.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/apple-touch-icon-76x76.png">
    <link rel="apple-touch-icon" sizes="114x114" href="/apple-touch-icon-114x114.png">
    <link rel="apple-touch-icon" sizes="120x120" href="/apple-touch-icon-120x120.png">
    <link rel="apple-touch-icon" sizes="144x144" href="/apple-touch-icon-144x144.png">
    <link rel="apple-touch-icon" sizes="152x152" href="/apple-touch-icon-152x152.png">
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon-180x180.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-96x96.png" sizes="96x96">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/android-chrome-manifest.json">
    <meta name="msapplication-TileColor" content="#4e8eec">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#ffffff">

    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle20') !!}
    <style>
        html, body{
            padding: 0;
            margin: 0;
        }
        .container{
            margin: auto;
        }
    </style>
</head>
<body>

<div id="redirect-popup" class="modal fade in payment-popups" tabindex="-1" role="dialog" aria-labelledby="congratulations-on-upgrading" aria-hidden="true" data-keyboard="false" data-backdrop="static">
    <div class="modal-dialog container">
        <div class="modal-content row text-center">

            <div class="modal-body col-xs-12 text-left clearfix">
                <div class="col-sm-offset-4 col-sm-8">
                    <p>
                        {!! $redirectMessage !!}
                    </p>
                </div>
            </div>

            <div class="modal-footer col-xs-12 text-center">
                You will be redirected in <span id="finalCountdown">{{ $redirectTime }}</span>s. If you are not redirected automatically, <a href="{{ $generatedUrl }}" id="redirectUrl">click here</a>.
            </div>
        </div>

    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
<script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script>
$("#redirect-popup").modal("show");
var count = "{{ $redirectTime }}";
var countdown = setInterval(function () {
    $("#finalCountdown").html(count);
    if (count == 0) {
        clearInterval(countdown);
        window.location = "{{ $generatedUrl }}";
    }
    count--;
}, 1000);
</script>
</body>
</html>
