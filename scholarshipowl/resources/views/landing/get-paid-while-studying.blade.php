@extends("base-landing")

@section("addition-style-sheet")
  {!! \App\Extensions\AssetsHelper::getCSSBundle('landingGetScholarships') !!}
@endsection

@section("scripts")
  {!! HTML::script('assets/js/bootstrap-select.min.js') !!}
  {!! HTML::script('assets/js/jquery.mCustomScrollbar.min.js') !!}
  {!! HTML::script('assets/js/jquery.mousewheel.min.js') !!}
  {!! HTML::script('assets/js/jquery.bootstrap-autohidingnavbar.min.js') !!}
  {!! \App\Extensions\AssetsHelper::getJSBundle('bundle22') !!}
@endsection

    @section("getContent")

    <article class="get-lower-tooltip">
      <div class="container mod-page-container">
        <div class="row">

          <header>
            <div class="header-container">
              <h2><em>scholarshipowl.com</em></h2>
            </div>
          </header>

          <div class="inner-container">
            <div class="row">

              <div class="col-xs-12 col-md-8">
                <h1>Get paid while studying</h1>
                <p class="intro">
                  Pretty much everyone needs to make money while studying. Only a selected few are lucky enough to have parents who can provide them with everything they need while they’re in college. The most obvious option is to get a part-time job, which a lot of students do. However, with tuition fees soaring sky-high and the cost of living going up all the time this is often just not enough.
                </p>
                <img class="student img-responsive" src="../assets/img/landing/female-student-with-glasses.jpg" alt="female student with glasses">
                <p>
                  The average student in higher education is dependent on loans, grants and scholarships. Loans can get very large and it is not uncommon that graduates spend the first 15 to 18 years of their working life paying back loans. Some loans take up to 25 years to pay off. These are all outrageous amounts of time and therefore most students dream of graduating debt-free.
                </p>
                <p>
                  The great thing about grants and scholarships is that they are monetary gifts and do not need to be paid back. Of course, because of the competition they are not as easy to get as a loan, but the great advantage is that the moment you are finished with your studies, all the money you make is yours. Applying for scholarships can take up a lot of free time. You need to research which scholarships you are eligible for and then you need to apply for them one by one, hoping you will be one of the lucky ones who wins.
                </p>

                <div class="row">
                  <div class="col-xs-12 col-sm-6">
                    <p>
                      ScholarshipOwl is a fantastic service that helps you with this potentially long and tedious process. When you sign up for our services we will do the research for you and all you need to do is choose the scholarships you want us to apply you for. Then we send off your application that you wrote when you joined us (you can even get help with that, too!) and all you have to do is keep an eye on your ScholarshipOwl inbox to see which scholarship you won.
                    </p>
                  </div>
                  <div class="col-xs-12 col-sm-6">
                    <img class="classroom flipped" src="../assets/img/landing/classroom.jpg" alt="classroom">
                  </div>
                </div>

              </div>
              <div class="col-xs-12 col-md-4 registerAside hide">
                <div class="form-wrapper how-get">
                  <h3>Sign up</h3>
                  @include('register/register-form-landing')
                </div>
                <div class="youDeserveItP">
                  <div>
                    <span class="registerTo">Register</span><br />
                    <span class="registerTo">to enter our</span><br />
                    <span class="registerToPrice">$1,000</span><br />
                    <span class="givwaway text-uppercase">scholarship</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="up-footer">
            <div>
              <p><img src="../assets/img/landing/ken.png" alt="Ken S."> </p>
              <p class="intro">
                Ken S., BSc, MA
              </p>
              <p class="position">
                Scholarship Entrepreneur
              </p>
            </div>
          </div>
        </div>
      </div>
    </article>
    @include('includes/marketing/mixpanel_pageview')
    @endsection
