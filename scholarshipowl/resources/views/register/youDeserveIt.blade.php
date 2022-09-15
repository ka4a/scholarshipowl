@extends('base')

@php $metaData = 'You Deserve It Scholarship'; @endphp
@php $social = false @endphp
@section('metatitle')
    <title>{{ $metaData }}</title>
    <meta property="og:title" content="{{ $metaData }}" />
    <meta name="twitter:title" content="{{ $metaData }}" />
@endsection

@section("metatags")
    <meta name="description" content="{{ $metaData }}" />
    <meta name="keyword" content="{{ \CMS::keywords() }}" />
    <meta name="author" content="{{ \CMS::author() }}" />
    <meta property="og:image" content="{{ url('assets/img/mascot.png') }}"  />
    <meta property="og:description" content="{{ $metaData }}" />
    <meta name="twitter:image" content="{{ url('assets/img/mascot.png') }}" />
    <meta name="twitter:description" content="{{ $metaData }}" />
@endsection

@section("styles")
    {!! \App\Extensions\AssetsHelper::getCSSBundle('bundle12') !!}
@endsection

@section("scripts2")
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle10') !!}
@endsection

@section('content')
    <!-- Header -->
    <section role="region" aria-labelledby="page-title">
        <div id="congratulations-head" class="congratulations-head register-step1-header blue-bg">
            <div class="container">
                <div class="row">
                    <div class="row-height">
                        <div class="col-xs-12 col-md-height col-bottom">
                            <h1 class="h2 deserveIt text-light text-white">
                                'You Deserve it' Scholarship
                            </h1>
                        </div>
                    </div>
                    <div class="row-height">
                        <div class="col-xs-12 col-md-8 col-md-height">
                            <div class="congratulations text-container mod-text-container text-white">
                                <p class="lead text-semibold">Sign up and get applied to the</p>
                                <p class="lead text-semibold">scholarship automatically!</p>
                                <p class="text-large">$1,000</p>
                                <p class="text-semibold towards">Application Deadline: {{ $expiryDate->format(\ScholarshipOwl\Data\DateHelper::FULL_DATE_FORMAT) }}</p>
                                <p class="lead additional-inf">*NO PURCHASE OR PAYMENT OF ANY KIND IS NECESSARY TO ENTER OR WIN THE $1,000 'YOU DESERVE IT!' SCHOLARSHIP</p>
                            </div>
                        </div>
                        <div class="col-xs-12 col-md-5 col-md-height col-bottom girl-thumb-up text-right">
                            <img src="../assets/img/girl-with-books.png">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section role="region" aria-labelledby="registration-form">
        <div class="you-deserve-it register-step1-form clearfix">
            <div class="container">
                @include('register/register-form')
            </div>
        </div>
    </section>
    <section id="sweepsTakesPage">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 table-frame">
                    <h4 class="text-uppercase">Scholarship Sweepstakes Rules</h4>
                    <div>
                        <p><span>{{ company_details()->getCompanyName() }}</span> (“<strong>ScholarshipOwl</strong>”) is pleased to offer this scholarship sweepstakes (“<strong>Scholarship Sweepstakes</strong>”) in which you can apply for a chance to win a scholarship to be paid by ScholarshipOwl directly to your qualified high school, college or university</p>
                        <p><strong>PLEASE READ THE OFFICIAL RULES ATTACHED TO THE <a href="/terms">TERMS OF USE</a> PRIOR TO APPLYING TO ENTER THE SCHOLARSHIP SWEEPSTAKES. BY APPLYING TO ENTER THE SCHOLARSHIP SWEEPSTAKES, YOU AGREE TO BE BOUND BY THE OFFICIAL RULES, AND YOU UNDERSTAND THAT ANY VIOLATION OF THE OFFICIAL RULES SHALL RESULT IN YOUR DISQUALIFICATION FROM THE SCHOLARSHIP SWEEPSTAKES.
                        </strong></p>
                        <p><strong>NO PURCHASE OR PAYMENT NECESSARY.</strong></p>
                        <p><strong>ANY PURCHASE OR PAYMENT WILL NOT INCREASE YOUR CHANCES OF WINNING. THE SCHOLARSHIP SWEEPSTAKES IS VOID WHERE PROHIBITED OR RESTRICTED BY LAW. SCHOLARSHIPOWL RESERVES THE RIGHT TO DISQUALIFY ANY APPLICANT FROM THE SCHOLARSHIP SWEEPSTAKES AT ANY TIME, AND IN ITS SOLE DISCRETION</strong></p>
                    </div>
                    <p class="text-uppercase" style="font-size: 23px"><strong>Consumer Disclosure</strong></p>
                    <table>
                        <tr>
                            <th>Scholarship Name:</th>
                            <th>“You Deserve It” Scholarship.</th>
                        </tr>
                        <tr>
                            <td>Prize:</td>
                            <td><p>A single $1,000 scholarship along with one (1) month of free access to scholarshipowl.com.</p></td>
                        </tr>
                        <tr>
                            <td>Eligibility Requirements:</td>
                            <td>
                                <div><span style="text-decoration:underline;">Territory:</span> You must be a resident of any of the 50 United States, District of Columbia or US Territories except for Rhode Island and Michigan.</div>
                                <div><span style="text-decoration:underline;">Age:</span> You must be 16 years of age or older</div>
                                <div><span style="text-decoration:underline;">Enrollment:</span> You must either be enrolled now, or will be enrolled within three months of registration in the Scholarship Sweepstakes, in a qualified high school, college or university within the United States.</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Sweepstakes Period:</td>
                            <td>
                                <p>The Scholarship Sweepstakes will run from {{ $startDate->format(\ScholarshipOwl\Data\DateHelper::FULL_DATE_FORMAT) }} until {{ $expiryDate->format(\ScholarshipOwl\Data\DateHelper::FULL_DATE_FORMAT) }}, inclusive, Eastern Standard Time.</p>
                            </td>
                        </tr>
                        <tr>
                            <td>How to Apply and Application Frequency Period:</td>
                            <td>
                                <p>If you created an Account on or after {{ $startDate->format('F jS, Y') }} you will automatically have a single application submitted on your behalf to apply to enter the Sweepstakes. You may subsequently apply to enter the Scholarship Sweepstakes up to once every seven days during the Sweepstakes Period.</p>
                                <p>If you created an Account prior to {{ $startDate->format('F jS, Y') }}, you may apply to enter the Scholarship Sweepstakes up to once every seven days during the Sweepstakes Period.</p>
                                <p>Alternately, you may apply to the Scholarship Sweepstakes by mailing a postcard, on which you have written your name, age, state of residence and email address, to: <span>{{ company_details()->getCompanyName() }}</span>, <span>{{ company_details()->getAddress1() }}</span>.</p>
                            </td>
                        </tr>
                        <tr>
                            <td>Estimated Odds of Winning:</td>
                            <td><p>Approximately 1 chance in 140,000. Actual odds of winning depend upon the total number of eligible applications received. Each application, regardless of the method of entry, has the same chance of winning.</p></td>
                        </tr>
                        <tr>
                            <td>Draw Date:</td>
                            <td>{{ $drawDate->format('F jS, Y') }}.</td>
                        </tr>
                        <tr>
                            <td>Responsibility for charges:</td>
                            <td>Winner will be responsible for wire transfer, paypal, or other related charges to the extent incurred.</td>
                        </tr>
                        <tr>
                            <td>Special Requirements from Winner:</td>
                            <td><p>Winner will allow ScholarshipOwl to publish Winner’s name, state, and photo (not required for Tennessee residents). In addition, Winner may be asked to leave a short video testimonial which may be published on our website, emails, and marketing materials. Winner may be asked to participate in media interviews during the six months period after winning. There are additional requirements of Winner – please refer to the <a href="/terms">Terms of Use</a> for the full requirements.</p></td>
                        </tr>
                    </table>

                    <div class="you-deserve-it cta">
                        <div class="button-wrapper">
                            <div class="btn btn-lg compact btn-block btn-warning text-center">
                                <a class="register-btn-txt text-uppercase RegisterButtonCTA" id="btnRegister2">{{ isset($buttonText) ? $buttonText : 'Register for free' }}</a>
                            </div>
                            @if (Request::is('awards/you-deserve-it-scholarship'))
                                <span>Existing user?
                                <a href="#" id="btnLogin2">click here</a>
                                </span>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>

@include('includes/marketing/mixpanel_pageview')
@stop
