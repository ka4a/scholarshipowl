@extends("base-landing")

@section("scripts")
  {!! \App\Extensions\AssetsHelper::getJSBundle('bundle27') !!}
@endsection

@section("addition-style-sheet")
  {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle31') !!}
@endsection

    @section("content")
    <header class="special-time-limited-header">
      <div class="container">
        <div class="row">
          <div class="col-lg-4 col-lg-offset-4">
            <span class="logo"><b>Scholarship</b>Owl</span>
          </div>
        </div>
      </div>
    </header>
    <article class="special-time-limited-article">
      <div class="container">
        <div class="row">
          <div class="col-lg-12">
            <p>JOIN THE FASTEST GROWING SCHOLARSHIP APPLICATION ENGINE TODAY!</p>
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
                  <p>AUTOMATIC APPLICATION TO SCHOLARSHIPS</p>
                </div>
              </div>
              <div class="item clearfix">
                <div class="thing2 col-lg-4 col-sm-12">
                  <img src="../assets/img/landing/special-time-icon2.svg" alt="Two">
                  <p>NEW SCHOLARSHIP OPPORTUNITIES EVERY MONTH!</p>
                </div>
              </div>
              <div class="item">
                <div class="thing2 col-lg-4 col-sm-12">
                  <img src="../assets/img/landing/special-time-icon3.svg" alt="Two">
                  <p>TRAINED PERSONAL ACCOUNT MANAGER TO HELP YOU FIND AND APPLY TO THE RIGHT MATCHES</p>
                </div>
              </div>
            </div>
          </div>
          <div class="icons-desktop">
            <div class="thing1 col-lg-4 col-sm-4">
              <img src="../assets/img/landing/special-time-icon1.svg" alt="One">
              <p>AUTOMATIC APPLICATION TO SCHOLARSHIPS</p>
            </div>
            <div class="thing2 col-lg-4 col-sm-4">
              <img src="../assets/img/landing/special-time-icon2.svg" alt="Two">
              <p>NEW SCHOLARSHIP OPPORTUNITIES EVERY MONTH!</p>
            </div>
            <div class="thing2 col-lg-4 col-sm-4">
              <img src="../assets/img/landing/special-time-icon3.svg" alt="Two">
              <p>TRAINED PERSONAL ACCOUNT MANAGER TO HELP YOU FIND AND APPLY TO THE RIGHT MATCHES</p>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2">
            <p class="upgrade-desc">{{ $conversionPageText }}</p>
          </div>
        </div>
        <div class="row">
          @if(isset($user))
          @include("payment.braintree.checkout")
          @else
          <div class="col-lg-6 col-lg-offset-3 col-sm-8 col-sm-offset-2 upgrade-button">
            <button id="basicButton" class="btn btn-block btn-lg btn-warning login-button"  data-toggle="modal" data-target="#LoginFormModal">UPGRADE NOW!</button>
          </div>
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
