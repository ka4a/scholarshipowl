@extends('layouts.front')

@section('meta-title', "Pricing | Apply.me")

@section('meta-description', "We offer a variety of pricing packages to suit every budget. Choose the plan that suits you best.")

@section('page-name', 'page-pricing')

@section('content')

    <section class="Banner">
        <div class="container">
            <div class="row Banner__content">
                <div class="col-xs-12">
                    <div class="text-center Util--icon-wrapper">
                        <img src="imgs/icons/am.svg" alt="">
                    </div>
                    <hr class="Util--spacer-trans-micro">
                    <h1 class="Banner__title text-center">Pricing</h1>
                    <hr class="Util--spacer-trans-micro">
                    <div class="Banner__text text-center">
                        We have variety of packages available to choose from.<br>
                        Select the pricing plan that best fits your needs.
                    </div>
                </div>
            </div>
        </div>
    </section>

    <ol class="breadcrumb Section--light-secondary Util--margin-bottom-none">
        <li><a href="{{ route('front.index') }}" title="">Home</a></li>
        <li class="active">Pricing</li>
    </ol>

    <section class="Section Section--light-secondary">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-sm-offset-4">
                    <div class="Package__item Package__item-highlighted">
                        <h3 class="text-center">Ultimate Package</h3>
                        <p class="text-center price"><span>$299/Y</span></p>
                        <p class="text-center">
                            <a
                                href="https://academy.apply.me/p/ultimate-package"
                                title=""
                                class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">RESERVE A SPOT</a>
                        </p>
                    </div>
                    <ul class="Package__description">
                        <li><img src="/imgs/icons/checked.png" alt=""> College Preparation Program</li>
                        <li><img src="/imgs/icons/checked.png" alt=""> Scholarships 101 Course</li>
                        <li><img src="/imgs/icons/checked.png" alt=""> Government Grants and Free Services</li>
                    </ul>
                    <hr class="Util--spacer-trans-small visible-xs">
                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-primary Standalone-price-mobile visible-xs">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-10 col-md-offset-1">

                    <h2 class="Section__main-title text-center Util--text-primary">Standalone Prices</h2>
                    <hr class="Util--spacer-trans-small">

                    <div class="row Standalone-price__item">
                        <div class="col-xs-12 col-md-7">
                            <h3 class="text-center">College Planning for Parents (Mini-Course)</h3>
                            <hr class="Util--spacer-trans-micro">
                            <p class="text-center">
                                <span class="price">$49</span>
                            </p>
                            <p class="text-center">
                                <a
                                    href="https://applymeacademy.teachable.com/p/college-planning-for-parents-mini-course"
                                    title=""
                                    class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted">GET IT NOW</a>
                            </p>
                        </div>
                    </div>

                    <hr class="Util--spacer-trans-small">

                    <div class="row Standalone-price__item odd">
                        <div class="col-xs-12 col-md-7">
                            <h3 class="text-center">Essay Assistance</h3>
                            <hr class="Util--spacer-trans-micro">
                            <p class="text-center">
                                <span class="price">$89</span>
                            </p>
                            <p class="text-center">
                                <a
                                    href="https://academy.apply.me/p/essay-assistance"
                                    title=""
                                    class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted">GET IT NOW</a>
                            </p>
                        </div>
                    </div>

                    <hr class="Util--spacer-trans-small">

                    <div class="row Standalone-price__item">
                        <div class="col-xs-12 col-md-7">
                            <h3 class="text-center">Interview Preparation</h3>
                            <hr class="Util--spacer-trans-micro">
                            <p class="text-center">
                                <span class="price">$149</span>
                            </p>
                            <p class="text-center">
                                <a
                                    href="https://academy.apply.me/p/interview-preparation"
                                    title=""
                                    class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted">GET IT NOW</a>
                            </p>
                        </div>
                    </div>

                    <hr class="Util--spacer-trans-small">

                    <div class="row Standalone-price__item odd">
                        <div class="col-xs-12 col-md-7">
                            <h3 class="text-center">Admissions Coaching</h3>
                            <hr class="Util--spacer-trans-micro">
                            <p class="text-center">
                                <span class="price">$249</span>
                            </p>
                            <p class="text-center">
                                <a
                                    href="https://academy.apply.me/p/admissions-coaching"
                                    title=""
                                    class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted">GET IT NOW</a>
                            </p>
                        </div>
                    </div>

                    <hr class="Util--spacer-trans-small">

                    <div class="row Standalone-price__item">
                        <div class="col-xs-12 col-md-7">
                            <h3 class="text-center">Scholarships 101 Course <br>(1-year access)</h3>
                            <hr class="Util--spacer-trans-micro">
                            <p class="text-center">
                                <span class="price">$279</span>
                            </p>
                            <p class="text-center">
                                <a
                                    href="https://academy.apply.me/p/scholarships-101"
                                    title=""
                                    class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted">GET IT NOW</a>
                            </p>
                        </div>
                    </div>

                    <hr class="Util--spacer-trans-small">

                    <div class="row Standalone-price__item odd">
                        <div class="col-xs-12 col-md-7">
                            <h3 class="text-center">College Preparation Program <br>(1-year access)</h3>
                            <hr class="Util--spacer-trans-small">
                            <p class="text-center">
                                <span class="price">$279</span>
                            </p>
                            <p class="text-center">
                                <a
                                    href="https://academy.apply.me/p/college-preparation-program"
                                    title=""
                                    class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted">GET IT NOW</a>
                            </p>
                        </div>
                    </div>

                    <hr class="Util--spacer-trans-small">

                    <div class="row Standalone-price__item">
                        <div class="col-xs-12 col-md-7">
                            <h3 class="text-center">Personalized Scholarship List</h3>
                            <hr class="Util--spacer-trans-micro">
                            <p class="text-center">
                                <span class="price">$549</span>
                            </p>
                            <p class="text-center">
                                <a
                                    href="https://academy.apply.me/p/personalized-scholarship-list"
                                    title=""
                                    class="btn btn-lg btn-default btn-am-buy btn-am-buy-highlighted">GET IT NOW</a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="Section Section--light-primary hidden-xs">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-md-8 col-md-offset-2">
                    <h2 class="Section__main-title text-center Util--text-primary">Standalone Prices</h2>

                    <div class="table-responsive">
                        <table class="table table-striped Standalone-price">
                            <tbody>
                                <tr class="Standalone-price__item">
                                    <td class="Util--align-middle"><h3>College Planning for Parents (Mini-Course)</h3></td>
                                    <td class="text-center"><span class="price">$49</span></td>
                                    <td class="Util--align-middle">
                                        <a
                                            href="https://applymeacademy.teachable.com/p/college-planning-for-parents-mini-course"
                                            title=""
                                            class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">GET IT NOW</a></td>
                                </tr>
                                <tr class="Standalone-price__item">
                                    <td class="Util--align-middle"><h3>Essay Assistance</h3></td>
                                    <td class="text-center"><span class="price">$89</span></td>
                                    <td class="Util--align-middle">
                                        <a
                                            href="https://academy.apply.me/p/essay-assistance"
                                            title=""
                                            class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">GET IT NOW</a></td>
                                </tr>
                                <tr class="Standalone-price__item">
                                    <td class="Util--align-middle"><h3>Interview Preparation</h3></td>
                                    <td class="text-center"><span class="price">$149</span></td>
                                    <td class="Util--align-middle">
                                        <a
                                            href="https://academy.apply.me/p/interview-preparation"
                                            title=""
                                            class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">GET IT NOW</a></td>
                                </tr>
                                <tr class="Standalone-price__item">
                                    <td class="Util--align-middle"><h3>Admissions Coaching</h3></td>
                                    <td class="text-center"><span class="price">$249</span></td>
                                    <td class="Util--align-middle">
                                        <a
                                            href="https://academy.apply.me/p/admissions-coaching"
                                            title=""
                                            class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">GET IT NOW</a></td>
                                </tr>
                                <tr class="Standalone-price__item">
                                    <td class="Util--align-middle"><h3>Scholarships 101 Course (1-year access)</h3></td>
                                    <td class="text-center"><span class="price">$279</span></td>
                                    <td class="Util--align-middle">
                                        <a
                                            href="https://academy.apply.me/p/scholarships-101"
                                            title=""
                                            class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">GET IT NOW</a></td>
                                </tr>
                                <tr class="Standalone-price__item">
                                    <td class="Util--align-middle"><h3>College Preparation Program (1-year access)</h3></td>
                                    <td class="text-center"><span class="price">$279</span></td>
                                    <td class="Util--align-middle">
                                        <a
                                            href="https://academy.apply.me/p/college-preparation-program"
                                            title=""
                                            class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">GET IT NOW</a></td>
                                </tr>
                                <tr class="Standalone-price__item">
                                    <td class="Util--align-middle"><h3>Personalized Scholarship List</h3></td>
                                    <td class="text-center"><span class="price">$549</span></td>
                                    <td class="Util--align-middle">
                                        <a
                                            href="https://academy.apply.me/p/personalized-scholarship-list"
                                            title=""
                                            class="btn btn-default btn-am-buy btn-am-buy-highlighted btn-block">GET IT NOW</a></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

@stop
