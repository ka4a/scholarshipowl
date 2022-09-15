<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <title>Unsubscribe List Download</title>
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

    <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Raleway:300,700,800,900"
          rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
          integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    {!! \App\Extensions\AssetsHelper::getCSSBundle('mainStyle') !!}
    {!! \App\Extensions\AssetsHelper::getCSSBundle('clicksOverride') !!}
    <style>
        body {
            padding: 0;
            margin: 0;
        }

        .container {
            margin: auto;
        }

        .footer {
            position: static !important;
        }
    </style>
</head>
<body>

<header class="page-header">
    <nav class="navbar navbar-default navbar-fixed-top navbar-2" role="navigation">
        <div class="container-fluid">
            <div class="row">
                <div class="container">
                    <div class="row">
                        <div id="navbar2" class="max-width center-block">
                            <div class="navbar-header">
                                <a class="navbar-brand brand sprite-logo" href="{{ url_builder('/') }}"
                                   title="ScholarshipOwl"></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>
</header>
<article>
    <div class="container">
        <form action="" method="post">
            @if (\Session::has("error"))
                <div class="alert alert-danger">
                    <ul>
                        <li>{{ \Session::get("error") }}</li>
                    </ul>
                </div>
            @endif
            <div class="form-group">
                <input type="text" name="key" class="form-control" placeholder="Access key">
                <div id="upgrade-btn" class="row">
                    <div class="col-xs-12 text-center">
                        <button id="download" href="#" class="btn btn-warning btn-block center-block text-uppercase">
                            Download
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</article>


@include("includes/footer")
</body>
</html>
