@extends('base')

@section("metatags")
@endsection

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle11') !!}
@endsection

@section("scripts2")
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle10') !!}
@endsection

@section('content')
    <section class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-7 scholarship-expired">
                <img src="{{ url('') }}/assets/img/inf_icon.jpg" alt="Information Icon">
                <p class="main-inf">This scholarship is no longer active!</p>
                <p class="alternative-inf">but We have Hunders of other scholarships just for you</p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-5 winnerRightBlock registerAside">
                @if (isset($user))
                    <p class="registerIntro">Get applied to your other
                        <strong>{{ $eligibility_count }} scholarship</strong> matches here</p>
                    <a href="{{ url_builder('select') }}" class="btn btn-lg btn-block btn-warning text-uppercase viewMatches">View scholarship matches</a>
                @else
                    <p class="registerIntro">
                        Register with ScholarshipOwl now, and you could get applied to hundreds of scholarships
                    </p>
                    @include('register/register-form', ['buttonText' => 'Register Now'])
                    @endif
            </div>
        </div>
    </section>
@stop
