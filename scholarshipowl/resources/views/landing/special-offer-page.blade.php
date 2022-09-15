@extends("base-landing")

@section("scripts")
  {!! \App\Extensions\AssetsHelper::getJSBundle('bundle25') !!}
@endsection

@section("addition-style-sheet")
  {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle32') !!}
@endsection

    @section('metatags')
    <meta name="description" content="{{ $page->getMetaDescription() ?: \App\Services\CmsService::DEFAULT_DESCRIPTION }}" />
    <meta name="keyword" content="{{ $page->getMetaKeywords() ?: \App\Services\CmsService::DEFAULT_KEYWORDS }}" />
    <meta name="author" content="{{ $page->getMetaAuthor() ?: \App\Services\CmsService::DEFAULT_AUTHOR }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ $page->getMetaDescription() ?: \App\Services\CmsService::DEFAULT_DESCRIPTION }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
    <meta name="twitter:description" content="{{ $page->getMetaDescription() ?: \App\Services\CmsService::DEFAULT_DESCRIPTION }}" />
    @endsection

    @section('metatitle')
    <title>{{ $page->getMetaTitle() ?: \App\Services\CmsService::DEFAULT_TITLE }}</title>
    <meta property="og:title" content="{{ $page->getMetaTitle() ?: \App\Services\CmsService::DEFAULT_TITLE }}" />
    <meta name="twitter:title" content="{{ $page->getMetaTitle() ?: \App\Services\CmsService::DEFAULT_TITLE }}" />
    @endsection

    @section("content")
    <article class="special-time-limited-article special-time-limited-header">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <span class="logo"></span>
            <p>{!! $page->getTitle() !!}</p>
          </div>
        </div>
      </div>
    </article>
    <main class="special-time-limited-main">
      <div class="container">
        <div class="row">
          <div class="center-block icons-mobile">
            <div>
              <div class="item clearfix">
                <div class="thing1 col-lg-4 col-sm-12">
                  <img src="../assets/img/landing/special-time-icon1.svg" alt="One">
                  <p>{!! $page->getIconTitle1() !!}</p>
                </div>
              </div>
              <div class="item clearfix">
                <div class="thing2 col-lg-4 col-sm-12">
                  <img src="../assets/img/landing/special-time-icon2.svg" alt="Two">
                  <p>{!! $page->getIconTitle2() !!}</p>
                </div>
              </div>
              <div class="item">
                <div class="thing2 col-lg-4 col-sm-12">
                  <img src="../assets/img/landing/special-time-icon3.svg" alt="Two">
                  <p>{!! $page->getIconTitle3() !!}</p>
                </div>
              </div>
            </div>
          </div>
          <div class="icons-desktop">
            <div class="thing1 col-lg-4 col-sm-4">
              <img src="../assets/img/landing/special-time-icon1.svg" alt="One">
              <p>{!! $page->getIconTitle1() !!}</p>
            </div>
            <div class="thing2 col-lg-4 col-sm-4">
              <img src="../assets/img/landing/special-time-icon2.svg" alt="Two">
              <p>{!! $page->getIconTitle2() !!}</p>
            </div>
            <div class="thing2 col-lg-4 col-sm-4">
              <img src="../assets/img/landing/special-time-icon3.svg" alt="Two">
              <p>{!! $page->getIconTitle3() !!}</p>
            </div>
          </div>
        </div>
        @if ($page->getScrollToText())
        <div class="row">
          <div class="col-lg-8 col-lg-offset-2 col-sm-8 col-sm-offset-2">
            @if(isset($user))
            <a href="#bt-form" id="scroll_to" class="special-time-limited-scroll-to btn btn-warning">
              {!! $page->getScrollToText() !!}
            </a>
            @else
            <button class="btn btn-block btn-lg btn-warning login-button">
              {!! $page->getScrollToText() !!}
            </button>
            @endif
          </div>
        </div>
        @endif
        <div class="row">
          <div class="col-lg-10 col-lg-offset-1 col-sm-8 col-sm-offset-2">
            <p class="upgrade-desc">{!! $page->getDescription() !!}</p>
          </div>
        </div>
        <div class="row">
          @if(isset($user))
          @include('payment.braintree.checkout', ['package' => $page->getPackage()])
          @endif
        </div>
      </div>
    </main>

    @include('includes/testimonials-lp')

    <div id="dialog" title="Confirmation Required">
      Are you sure about this?
    </div>
    @include('includes/marketing/mixpanel_pageview')
    @endsection
