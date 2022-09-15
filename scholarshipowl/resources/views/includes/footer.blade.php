    <footer class="footer" role="contentinfo" aria-label="footer">
        <div class="container">
            <div class="row">
                <div class="col-xs-12 col-lg-12">
                    <h2 class="sr-only">Footer</h2>
                    <div class="links">
                        <a href="{!! url_builder('/') !!}">Home</a>
                        <a href="{!! url_builder('faq') !!}">FAQ</a>
                        <a target="_blank" href="https://scholarshipowl.zendesk.com/hc/en-us">Helpdesk (beta)</a>
                        <a href="{!! url_builder('press') !!}">Press</a>
                        <a href="{!! url_builder('help') !!}">Help</a>
                        <!-- <a href="{!! url_builder('disclaimer') !!}">Disclaimer</a> -->
                        <a href="{!! url_builder('privacy') !!}">Privacy Policy</a>
                        <a href="{!! url_builder('terms') !!}">Terms of Use</a>
                        <a href="{!! url_builder('contact') !!}">Contact</a>
                        <a target="_blank" href="https://scholarshipowl.recruitee.com/">Jobs</a>
                    </div>
                    @if(in_array(Route::currentRouteName(), ["about-us", "register", "register2", "my-account"]))
                        <div class="text-center nspa">
                            <div class="col-lg-offset-3 col-lg-2">
                                <div class="sprite-ssl-logo"></div>
                            </div>
                            <div class="col-lg-2 dmca">
                                <a class="sprite-dmca-protected-sml-120n" href="https://www.dmca.com/Protection/Status.aspx?ID=dc472339-93d3-4fee-b5fd-cc22f5f05ebd" title="DMCA.com Protection Status" target="_blank"></a>
                            </div>
                            <div class="col-lg-2">
                                <div class="sprite-nspa-logo"></div>
                            </div>
                            <div class="clearfix"></div>
                        </div>
                    @else
                        <div class="text-center">
                            <p>
                                <div class="sprite-ssl-logo"></div>
                            </p>
                            <p>
                                <a class="sprite-dmca-protected-sml-120n" href="https://www.dmca.com/Protection/Status.aspx?ID=dc472339-93d3-4fee-b5fd-cc22f5f05ebd" title="DMCA.com Protection Status" target="_blank" class="dmca-badge"></a>
                            </p>
                        </div>
                    @endif
                    <div class="copyrights">
                        <p>Copyrights &copy; ScholarshipOwl.com, All rights reserved</p>
                        <p>Scholarshipowl.com is owned and operated by <span>{!! company_details()->getCompanyName() !!}</span></p>
                        <p>{!! company_details()->getAddress1() !!}</p>
                        {!! (setting("content.phone.show") == "yes")?"<p>".setting("content.phone")."</p>":"" !!}
                    </div>
                    <div class="disclaimer-note">
                        <p>@include('includes/texts/explanatory')</p>
                    </div>
                </div>
            </div>
        </div>
<!-- Ass seen on -->
<div class="section--as-seen-on blue-bg">
    <div class="container-fluid">
        <div class="row">
            <div class="col-xs-12 text-center">
                <div class="bubble center-block">
                    <h2 class="h4 mod-heading text-white">Check what others say about us</h2>
                </div>
            </div>
        </div>

        <div class="row bgcol-deep-blue">
            <div class="container">
            	<ul class="list-inline logosUl">
                	<li><div class="footer-as-seen-on tnw"></div></li>
                    <li><div class="footer-as-seen-on tech-zulu"></div></li>
                    <li><div class="footer-as-seen-on vator-news"></div></li>
                    <li><div class="footer-as-seen-on forbes"></div></li>
                    <li><div class="footer-as-seen-on gigaom"></div></li>
					<li><div class="footer-as-seen-on redef"></div></li>
					<li><div class="footer-as-seen-on product-hunt"></div></li>
                    <li><div class="footer-as-seen-on hello-giggles"></div></li>
                    <li><div class="footer-as-seen-on la-biz"></div></li>
                    <li><div class="footer-as-seen-on uloop"></div></li>
                </ul>
            </div>
        </div>
    </div>
</div>
</footer>
