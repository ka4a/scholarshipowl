<h4 class="sr-only sr-only-focusable">Friends</h4>

<div id="refer" class="referAF">
    <div class="row getAreward">
        <div class="col-xs-12">
        	{!! setting("refer_a_friend.tab_above_message") !!}

            <!--
            <h5>Tell a Friend, get a reward </h5>

          	<p>Tell <strong>five friends</strong> about ScholarshipOwl and <strong>get FULL access</strong>.</p>
          	<p>And if that's not enough, we will give your friends <strong>five applications for free</strong> as well.</p>
            <p>More scholarship applications are just a click away.</p>
             -->
        </div>
    </div>

    <div class="row social text-center">
        @if((in_array("show_all", setting("referral.channels")) || in_array("fb", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
            <div id="fb" class="col-xs-{{ $isMobile?"2":"2" }} col-lg-3 social-widget">
                <a data-url="{{ url('')."/?referral=".$user->getReferralCode()."%26ch%3Dfb" }}" data-icon="{{ url('') }}/assets/img/mascot.png" href="#" class="FacebookShareButton">
                    <img src="{{ url('') }}/assets/img/refer-icon-facebook.png" alt="Facebook" class="mCS_img_loaded">
                </a>
            </div>
        @endif
        @if((in_array("show_all", setting("referral.channels")) || in_array("tw", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
            <div id="twitter" class="col-xs-{{ $isMobile?"2":"2" }} col-lg-3 social-widget">
                <a href="https://twitter.com/intent/tweet?text=I%20just%20found%20this%20amazing%20tool%20applying%20me%20to%20loads%20of%20scholarships%20automatically!&amp;url={{ url('')."/?referral=".$user->getReferralCode()."%26ch%3Dtw" }}">
                    <img src="{{ url('') }}/assets/img/refer-icon-twitter.png" alt="Twitter" class="mCS_img_loaded">
                </a>
            </div>
        @endif
        @if((in_array("show_all", setting("referral.channels")) || in_array("pi", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
            <div class="col-xs-{{ $isMobile?"2":"2" }} col-lg-3 social-widget">
                <a href="https://pinterest.com/pin/create/button/?url={{ url('')."/?referral=".$user->getReferralCode()."%26ch%3Dpi" }}&description=I%20just%20found%20this%20amazing%20tool%20applying%20me%20to%20loads%20of%20scholarships%20automatically!&media={{ url('') }}/assets/img/mascot.png" class="pin-it-button" count-layout="horizontal" target="_blank">
                    <img border="0" src="{{ url('') }}/assets/img/refer-icon-pinterest.png" title="Pin It" />
                </a>
            </div>
        @endif
        @if($isMobile)
            @if((in_array("show_all", setting("referral.channels")) || in_array("wa", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                <div class="col-xs-2 col-lg-3 social-widget whatsapp">
                    <a href="whatsapp://send" data-text="I just found this amazing tool applying me to loads of scholarships automatically!" data-href="{{ url('')."/?referral=".$user->getReferralCode()."&ch=wa" }}" class="wa_btn wa_btn_l">
                        <img border="0" src="{{ url('') }}/assets/img/refer-icon-whatsapp.png" />
                    </a>
                </div>
            @endif
            @if((in_array("show_all", setting("referral.channels")) || in_array("sm", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                <div class="col-xs-2 col-lg-3 social-widget">
                    <a href="sms:?body=I%20just%20found%20this%20amazing%20tool%20applying%20me%20to%20loads%20of%20scholarships%20automatically!%20{{ url('')."/?referral=".$user->getReferralCode()."&ch=sms" }}">
                        <img border="0" src="{{ url('') }}/assets/img/refer-icon-sms.png" id="smsicon" />
                    </a>
                </div>
            @endif
        @endif
        @if((in_array("show_all", setting("referral.channels")) || in_array("ma", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
            <div id="email" class="col-xs-{{ $isMobile?"2":"2" }} col-lg-3 social-widget last">
                <a href="#" class="MailButton">
                    <img src="{{ url('') }}/assets/img/refer-icon-mail.png" alt="Mail" class="mCS_img_loaded">
                </a>
            </div>
        @endif
    </div>
    <div class="row copy">
        <div class="col-xs-12">
            <div class="input-group">
                <span class="input-group-addon CopyToClipboardButton copy-link" data-clipboard-text="{{ url('')."/?referral=".$user->getReferralCode()."&ch=ln" }}">
                    Copy link
                </span>
                <input type="text" class="form-control ReferralLinkInput" value="{{ url('')."/?referral=".$user->getReferralCode()."&ch=ln" }}">
            </div>
        </div>
    </div>
    <div class="row referals">
        <div class="col-xs-12">
            <h5 class="mod-referals">Referrals</h5>
            <div class="table-responsive text-center">
                <div class="table-wraper">
                    <table class="ReferralsTable" data-url="/api/v1.0/referrals" data-method="get" data-type="json">
                        <thead>
                            <tr>
                                <th scope="col"><span>First name:</span></th>
                                <th scope="col"><span>Signup date:</span></th>
                                <th scope="col"><span>Upgraded:</span></th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            {!! setting("refer_a_friend.tab_below_message") !!}
        </div>
    </div>
</div>
