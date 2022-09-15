@extends('base')

@section("styles")
	{!! \App\Extensions\AssetsHelper::getCSSBundle('bundle8') !!}
@endsection

@section("scripts")
    {!! HTML::script('https://mottie.github.io/tablesorter/js/jquery.tablesorter.js') !!}
    {!! HTML::script('assets/plugins/checkboxes/jquery.checkboxes.min.js') !!}
@endsection

@section("scripts2")
    {!! HTML::script('https://cdnjs.cloudflare.com/ajax/libs/iScroll/5.1.3/iscroll-probe.js') !!}
    @if(Auth::user())
    {!! \App\Extensions\AssetsHelper::getJSBundle('user') !!}
    @endif
    {!! \App\Extensions\AssetsHelper::getJSBundle('bundle7') !!}
@endsection

@section('content')
    <form method="post" action="api/v1.0/apply{{ isset($reapply) ? '?reapply=1' : '' }}" class="ajax_form">
    {{ Form::token() }}
    {{ Form::hidden("_return", url_builder("my-account")) }}
    {{ Form::hidden("api_url", url_builder("api/v1.0/apply"), array("id" => "apiUrl")) }}

    <script>
        (function() {
            var form = document.querySelector('.ajax_form');

            if(!form) throw Error('Form not defined.')

            form.addEventListener('submit', function(ev) {
                ev.preventDefault();

                return false;
            })
        })()
    </script>

    <!-- Select scholarships header -->
    <section role="region" aria-labelledby="select-scholarships-title">
        <div id="registered" class="blue-bg clearfix">
            <div class="container">
                <div class="row">
                    <div class="text-container text-center text-white">
                        {!! \App\Entity\FeatureSet::config()->getContentSet()->mapRegisterHeader() !!}
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- list of available scholarships -->
    <section role="region" aria-labelledby="list-of-scholarships">
        <div id="reviewScholarshipsTable" class="main myApplications">
            <div class="tableSection">
                <div class="container-fluid tableHead-bg skyBlue-bg">
                    &nbsp;
                </div>
                <div class="container">
                    <div class="row">
                        <div class="bootstrap-table table-responsive {{ $hideCheckboxes ? 'hide-checkboxes' : '' }}">
                            <h3 class="sr-only" id="list-of-scholarships">List of available scholarships</h3>

                            <table class="tablesorter ApplyTable" id="table" data-toggle="table" data-classes="table table-hover table-condensed applyTable" data-sort-name="title" data-sort-order="asc">
                                <thead>
                                <tr>
                                    <th class="col-xs-1 mod-th-checkbox" scope="col" data-field="apply" data-sorter="false" data-halign="center" data-valign="middle" data-align="center" data-checkbox="false">
                                        <div class="title-center">
                                            <div class="field-title text-center mod-field-title">

                                                <div id="selectAll" class="checkAllWrapper">
                                                    <a class="checkAll" href="#applyTable" data-toggle="checkboxes" data-action="check"></a>
                                                    <span class="lbl padding-0"></span>
                                                </div>
                                                <div id="selectNone" class="checkAllWrapper hidden">
                                                    <a class="checkAll" href="#applyTable" data-action="uncheck" data-toggle="checkboxes"></a>
                                                    <span class="lbl padding-0"></span>
                                                </div>
                                            </div>
                                        </div>
                                    </th>

                                    <th style="width: 1%">
                                        <span class="recurrent-container">
                                            <span class="recurrent-icon glyphicon glyphicon-refresh"></span>
                                        </span>
                                    </th>

                                    <th style="width: 57%" class="col-xs-6 mod-th-title" scope="col" data-field="title" data-sortable="true" data-halign="left" data-valign="middle" data-align="left">
                                        <div class="field-title-wrp">
                                            <div class="field-title">Title</div>
                                            <span class="caret">&nbsp;</span>
                                        </div>
                                    </th>

                                    <th style="width: 10%" class="col-xs-1 mod-th-toc" scope="col" data-field="terms_&_conditions" data-sorter="false" data-halign="center" data-valign="middle" data-align="center">
                                        <div class="field-title-wrp">
                                            <div class="field-title">Terms & <br />Conditions</div>
                                            <span class="caret">&nbsp;</span>
                                        </div>
                                    </th>

                                    <th style="width: 10%" class="col-xs-1 hidden-xs hidden-sm" scope="col" data-field="deadline" data-sortable="true" data-halign="center" data-valign="middle" data-align="center">
                                        <div class="field-title-wrp">
                                            <div class="field-title">Deadline</div>
                                            <span class="caret">&nbsp;</span>
                                        </div>
                                    </th>

                                    <th style="width: 10%" class="col-xs-1 mod-th-ammount" scope="col" data-field="amount" data-sortable="true" data-halign="center" data-valign="middle" data-align="right">
                                        <div class="field-title-wrp">
                                            <div class="field-title">Amount</div>
                                            <span class="caret">&nbsp;</span>
                                        </div>
                                    </th>
                                </tr>
                                </thead>
                                <tbody id="applyTable"></tbody>
                            </table>

                            <div class="clearfix">
                                <table class="InfoTable table" id="info-table">
                                    <tbody>
                                    <div class="empty-apply-table text-center">
                                        @if(\Auth::user()->isUSA())
                                        <h3 class="text-uppercase">
                                            You selected all your current scholarship matches already.
                                        </h3>
                                        <p>
                                            View your scholarship applications <a class="text-semibold" href="{{ url_builder('my-applications') }}">HERE</a> and make sure all your applications are complete and submitted in time.
                                        </p>
                                        <p>
                                            We recommend you check for new scholarship matches here at least once a month .
                                        </p>
                                        @else
                                            <br/>
                                            <p>
                                                No international scholarships yet!<br/>
                                                Coming soon!
                                            </p>
                                        @endif
                                    </div>
                                    <tr>
                                        <td class="col-xs-1">
                                        </td>
                                        <td class="left-padd-eq-td col-xs-11">
                                            <p class="no-subscription">
                                                <strong>
                                                    Get applied to all your {{ $eligibility_count }} scholarship matches.
                                                    Click <a href="/upgrade-mobile" class="ApplyPageButton" data-page="select">here</a>
                                                </strong>
                                            </p>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>

                            <div class="clearfix">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <aside role="complementary" aria-labelledby="come-back-regularly">
        <div class="blue-bg">
            <div class="container">
                <div class="row">
                    <div class="text-container text-medium text-white text-center">
                        <h3 class="sr-only" id="come-back-regularly">Come back regularly</h3>
                        <p>
                            We are always adding new scholarships for you.<br />
                            Make sure to come back regularly to discover new scholarships
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    <section role="region" aria-labelledby="continue-label">
        <div id="continue" class="section--continue">
            <h3 class="sr-only" id="continue-label">Continue</h3>
            <div class="container-fluid continue">
                <div class="scroll-magic--wrapper">
                    <div class="scroll-magic animated section--continue--fixed">
                      <button type="submit" class="ApplyButton btn-m-arrow btn-arrow__old margin-center {{ $isMobile?"ApplyButtonMobile":"" }}"
                              data-page="select" {!! $isMobile ? "data-url=\"/upgrade-mobile\"" : "" !!}>
                        <span class="btn__arrow"><i></i></span>
                        <span class="btn__loader"><i></i></span>
                        <span class="btn__text fz_28">{{ features()->getContentSet()->getSelectApplyNow() }}</span>
                      </button>
                    </div>
                </div>
                <div class="clearfix apply-testimonial">
                    <div class="static-testimonial clearfix">
                        <blockquote>
                            <p>Over 70% of respondents rated the overall experience with ScholarshipOwl as amazing</p>
                        </blockquote>
                    </div>
                </div>

            </div>
        </div>
    </section>
    @if(isset($scholarships))
        @foreach ($scholarships as $scholarship)
            <input type="hidden" name="scholarships[]" value="{{$scholarship->getScholarshipId()}}" />
        @endforeach

        <input type="hidden" name="noJs" value="1" />
    @endif
</form>

@if (isset($pretick))
	<input type="hidden" name="pretick" id="pretick" value="{{$pretick}}" />
@endif

@if(isset($reapply))
    <input type="hidden" name="reapply" id="reapply" value="1" />
@endif

<input type="hidden" name="paid_account" id="paid_account" value="{{ $displayAdditionalInfo }}" />


@include('includes/popup')
@include('includes/marketing/mixpanel_pageview')
@stop
