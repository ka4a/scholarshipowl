<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimum-scale=1.0, maximum-scale=1.0">
    <title>School listings</title>
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

    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700|Raleway:300,700,800,900" rel="stylesheet">
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle21') !!}
    <style>
        body{
            padding: 0;
            margin: 0;
        }
        .container{
            margin: auto;
        }
    </style>
</head>
<body>

<div id="school-listings" class="clicks-listings">
    <div class="container-fluid">
        <div class="row">
            <header class="page-header">
                <nav class="navbar navbar-default navbar-fixed-top navbar-2" role="navigation">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="container">
                                <div class="row">
                                    <div id="navbar2" class="max-width center-block">
                                        <div class="navbar-header">
                                            <a class="navbar-brand brand sprite-logo" href="{{ url_builder('/') }}" title="ScholarshipOwl"></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </nav>
            </header>
            <article>
                <script type="text/javascript" src="http://cdn.fcmrktplace.com/scripts/clicksnet.js"></script>
                <script type="text/javascript">
					( function() {
                        var affcid = "1085978";
                        var key = "NnUnVWpixDs1";
                        var zip = {!! isset($user)?json_encode($user->getProfile()->getZip()):"clicksNetGetQueryStringParam('zip')" !!};

                        var clicksnet_campus_location = {!! isset($user)?json_encode(\ScholarshipOwl\Data\Entity\Account\Facade\ProfileClicksFacade::getStudyOnlineValue($user->getProfile())):"clicksNetGetQueryStringParam('clicksnet_campus_location')" !!};
                        var clicksnet_degree = {!! isset($user)?json_encode(\ScholarshipOwl\Data\Entity\Account\Facade\ProfileClicksFacade::getDegreeTypeName($user->getProfile())):"clicksNetGetQueryStringParam('clicksnet_degree')" !!};
                        var clicksnet_study = {!! isset($user)?json_encode(\ScholarshipOwl\Data\Entity\Account\Facade\ProfileClicksFacade::getCareerGoalName($user->getProfile())):"clicksNetGetQueryStringParam('clicksnet_study')" !!};
                        var clicksnet_current_education = clicksNetGetQueryStringParam('clicksnet_current_education');
                        var clicksnet_military = clicksNetGetQueryStringParam('clicksnet_military');
                        var clicksnet_nurse_type = clicksNetGetQueryStringParam('clicksnet_nurse_type');
                        var subid1 = '';
                        var subid2 = '';

                        var creative_id = clicksNetGetQueryStringParam('preview');

                        var showHeader = false;
                        var showFooter = false;

                        document.write("<script type='text/javascript' src='" + clicksNetGetProtocol() + "cdn.fcmrktplace.com/listing/?affcamid=" + affcid + "&zip=" + zip + "&key=" + key + "&creative_id=" + creative_id + "&clicksnet_campus_location=" + clicksnet_campus_location + "&clicksnet_degree=" + clicksnet_degree + "&clicksnet_study=" + clicksnet_study + "&clicksnet_current_education=" + clicksnet_current_education + "&clicksnet_military=" + clicksnet_military + "&clicksnet_nurse_type=" + clicksnet_nurse_type + "&subid1=" + subid1 + "&subid2=" + subid2 + "'> <\/script>");

					})();
                </script>
            </article>
        </div>
    </div>
</div>



<footer class="footer">
    @include("includes/footer")
</footer>
</body>
</html>
