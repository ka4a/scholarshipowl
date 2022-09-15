@extends("base-landing")

@section("scripts")
  {!! HTML::script('assets/js/bootstrap-select.min.js') !!}
  {!! HTML::script('assets/js/jquery.mCustomScrollbar.min.js') !!}
  {!! HTML::script('assets/js/jquery.mousewheel.min.js') !!}
  {!! HTML::script('assets/js/jquery.bootstrap-autohidingnavbar.min.js') !!}
  {!! \App\Extensions\AssetsHelper::getJSBundle('bundle22') !!}
@endsection

@section("content")
<article>
<div id="landing-page" class="apply-to-hundreds">
  <div class="container-fluid">
      <div class="row">
          <header id="green" class="page-header">
              <div class="container">
                  <div class="row">
                      <div class="topWrapper">
                          <div class="topWrapperInner lpAnimation">
                              <div class="top">
                                  <a href="{{ url_builder('/') }}" class="logo pull-left"></a>
                              </div>
                          </div>
                      </div>
                      <div class="youtube-sch">
                          <div class="embed-responsive embed-responsive-16by9">
                          	<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/Y0eE7Tr8PcI?autoplay=1" frameborder="0" allowfullscreen></iframe>
                          </div>
                      </div>
                  </div>
              </div>


              <div class="container-fluid landing-reg-form-background">
                  <div class="row">
                      <div class="container form-wrapper">
                          @include('register/register-form-landing')
                      </div>
                  </div>
              </div>

          </header>
      </div>
  </div>
                              <a name="content"></a>
  <div class="container">
      <div class="row">
          <div id="applyUnlimited" class="text1">
Apply for
              <strong class="bold">Hundreds of Scholarships</strong>
              <span class="linebreak">with just one application!</span>
          </div>
          <div id="aboutUs" class="container">
              <div class="row">
                  <div class="col-md-6 pull-left">
                      <h3>Who We Are</h3>
                      <p>ScholarshipOwl is a site designed to offer students
                          an easy way to finance their education. We know how
                          hard it is to pay for college, especially with the cost
                          of attendance on the rise. Whether you need $100 or
                          $100,000 to cover your expenses, we're here to help you

                          <span class="bold">gain access to that money</span> and
                              put it toward your schooling. All you need to do is

                          <span class="bold">fill out one form, once.</span>
                      </p>
                  </div>
                  <div class="col-md-6 pull-right">
                      <h3>Our System at a Glance</h3>
                      <p>We have created a program that allows college and high
                          school students to
                          <span class="bold">apply for hundreds of scholarships
                            with just one set of information.</span>
                            You don't have to spend hours on the computer typing
                            out your personal information and scholarship essays.
                            We do all of the work for you. Our goal is to help you
                            get the funding you're looking for.

                      </p>
                  </div>
              </div>
              <div id="checkYourChances" class="button-wrapper clearfix">
                  <div class="row">
                      <div class="big-orange-button">
                          <button class="" id="sign_up_now_btn" type="submit" name="signUpBtn" onclick="scrollTo($('#landingRegForm1'))">
                          Apply now
                          <div class="arrow-btn">
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
                          </button>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  </div>
</div>
</article>

@include('includes/testimonials-lp')
@include('includes/social-landing')

<div id="dialog" title="Confirmation Required">
  Are you sure about this?
</div>
@include('includes/marketing/mixpanel_pageview')
@endsection
