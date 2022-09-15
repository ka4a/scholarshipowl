@extends('base')

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('mainStyle') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle9') !!}
@endsection

@section('content')
    <section>
        <div class="blue-bg">
            <div class="container">
                <div class="row">
                    <div class="text text-white">
                        <h2 class="title text10">Reset password</h2>

                        <p class="description text30">
                            Fill in the required data to reset your password.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </section>


    @if($responseType == "success")
        <form method="post" action="post-reset-password" id="ResetPasswordForm" class="ajax_form">
            <section id="reset-password" class="blueBg">
                <div class="container">
                    <div class="row center-block">
                        <div class="col-md-12 reset-pass-block">
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <div class="form-group">
                                <input type="email" name="email" class="form-control" placeholder="Your email address">
                                <small id="ResetPasswordErrorToken" class="help-block" style="display: none;"></small>
                                <small id="ResetPasswordErrorEmail" class="help-block" style="display: none;"></small>
                            </div>
                            <div class="form-group">
                                <input type="password" name="password" class="form-control" placeholder="Type your new password">
                            </div>
                            <div class="form-group">
                                <input type="password" name="retype_password" class="form-control" placeholder="Retype your new password">
                                <small id="ResetPasswordErrorPassword" class="help-block" style="display: none;"></small>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
            <div class="clearfix"></div>
            <section id="continue" class="section--continue">
                <div class="container-fluid continue">
                    <div class="row">
                        <div class="container center-block">
                            <div class="row">
                                <div class="button-wrapper">
                                    <div class="btn btn-lg btn-block btn-warning text-center">
                                        <input value="Continue" type="submit" class="ResetPasswordButton">
                                        <div class="arrow-btn hidden-xs">
                                            <div class="arrow">
                                                <span class="a1"></span>
                                                <span class="a2"></span>
                                                <span class="a3"></span>
                                                <span class="a4"></span>
                                                <span class="a5"></span>
                                                <span class="a6"></span>
                                                <span class="a7"></span>
                                                <span class="a8"></span>
                                                <span class="a9"></span>
                                                <span class="a10"></span>
                                                <span class="a11"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </form>
        @include("includes/popup")
    @else
        <section id="reset-password" class="blueBg">
            <div class="container">
                <div class="row center-block">
                    <div class="bg-{{ $responseType }} text-center">
                        <span class="text-{{ $responseType }}">
                            {!! $message !!}
                        </span>
                    </div>
                </div>
            </div>
        </section>
    @endif

@stop
