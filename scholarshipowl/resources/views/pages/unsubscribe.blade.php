@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('mainStyle') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('login') !!}
    {!! \App\Extensions\AssetsHelper::getJSBundle('main') !!}
@endsection

@section('content')

            <!-- Refer a Friend header -->
    <section role="region" aria-labelledby="page-title">
        <div class="section--additional-services-header blue-bg clearfix">
            <div class="container">
                <div class="row">
                    <div class="text-container text-center text-white">
                        <h2 class="text-large text-light" id="page-title">
                            Unsubscribe
                        </h2>
                        <p class="text-medium">

                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Refer a Friend -->
    <section role="region" aria-labelledby="">
        <div class="section--additional-services lightBlue-bg clearfix">
            <div class="container center-block">
                <div class="row">
                    <div class="text-container text-center">
                        @if($unsubscribed)
                            <p>Email {{ $email }} has been successfully unsubscribed.</p>
                        @elseif($email == '')
                            <form action="/unsubscribe" method="GET">
                                <div class="form-group">
                                    <input type="text" name="email" class="form-control" placeholder="Email" required>
                                    <div id="upgrade-btn" class="row">
                                        <div class="col-xs-12 text-center">
                                            <button id="unsubscribe" href="#" class="btn btn-warning btn-block center-block text-uppercase">
                                                Unsubscribe
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        @else
                            <p>Sorry, email could not be unsubscribed.</p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop
