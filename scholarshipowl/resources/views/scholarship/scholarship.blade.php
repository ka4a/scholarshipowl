@extends('base')

@section("metatags")
    <meta name="description" content="{{ $scholarship->getMetaDescription() ?? $scholarship->getDescription() }}" />
    <meta name="keyword" content="{{ $scholarship->getMetaKeywords() ?? $scholarship->getDescription() }}" />
    <meta name="author" content="{{ $scholarship->getMetaAuthor() ?? \CMS::author() }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ $scholarship->getMetaDescription() ?? $scholarship->getDescription() }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
    <meta name="twitter:description" content="{{ $scholarship->getMetaDescription() ?? $scholarship->getDescription() }}" />
@endsection

@section('metatitle')
    <title>{{ !$isPublished ? 'Expired:' : '' }} {{ $scholarship->getMetaTitle() ?? \CMS::title() }}</title>
    <meta property="og:title" content="{{ $scholarship->getMetaTitle() ?? \CMS::title() }}" />
    <meta name="twitter:title" content="{{ $scholarship->getMetaTitle() ?? \CMS::title() }}" />
@endsection

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle11') !!}
@endsection

@section("scripts2")
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle10') !!}
@endsection

@section('content')
    <section class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-7" id="scholarship-description">
                <img width="100%" src="{{ $scholarship->getLogoUrl() }}" alt="{{ $scholarship->getTitle() }}" />
                <h4>
                    @if ( $scholarship->isRecurrent() )
                        <span class="recurrent-icon glyphicon glyphicon-refresh"></span>
                    @endif
                    {{ $scholarship->getTitle() }}

                    @if ( $scholarship->isRecurrent() )
                        <span class="icon icon-help tooltip-controller" data-trigger="manual" data-toggle="tooltip" data-placement="auto top" title="Recurring scholarships are scholarships which are reinstated periodically (e.g. weekly, monthly, yearly…)"></span>
                    @endif
                </h4>
                <p>{!! $scholarship->getDescription() !!} </p>
                <p @if (!$isPublished) style="color: #f34857;" @endif >
                    <b>Deadline: </b>{{ $scholarship->getExpirationDate()->format('m.d.Y') }}
                </p>

                @if(!$isPublished)
                    <p style="color: #f34857;">
                        @if($scholarship->isRecurrent())
                            <b>Scholarship has deadlined, see the <a href="{{ $scholarship->getCurrentScholarship() ? $scholarship->getCurrentScholarship()->getPublicUrl() : URL::to('/') }}" >latest one </a></b>
                        @else
                            <b>Scholarship has deadlined, please register to see all available scholarships</b>
                        @endif
                    </p>
                @endif

                <p><b>Amount Awarded: </b>${{ number_format($scholarship->getAmount()) }}</p>
                <p><b>Awards: </b>{{ $scholarship->getAwards() }}</p>
                <p id="scholarship-disclaimer"><small>Any logos displayed on this website belong to their respective owners and the uses of such logos may not be endorsed by such owners.
                Please contact us if you are the owner of a logo and would like to have it removed.</small></p>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-5 winnerRightBlock registerAside">
                @if (isset($user))
                    <p class="registerIntro">Get applied to the "{{ $scholarship->getTitle() }}" and your other
                        <strong>{{ $eligibility_count }} scholarship</strong> matches here</p>
                    <a href="{{ url_builder('select') }}" class="btn btn-lg btn-block btn-warning text-uppercase viewMatches">View scholarship matches</a>
                @else
                    <p class="registerIntro">
                        Register with ScholarshipOwl now, and you could get applied to hundreds of scholarships, including “{{ $scholarship->getTitle() }}“
                    </p>
                    @include('register/register-form', ['buttonText' => 'Register Now'])
                @endif
            </div>
        </div>
    </section>
    @stop
