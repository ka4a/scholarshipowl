<div class="modal fade" id="raf-exit-popup" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
                <div class="applications-remove-header">
                    <button type="button" class="close img-circle" data-dismiss="modal">
                        <span aria-hidden="true">×</span>
                        <span class="sr-only">Close</span>
                    </button>
                </div>

            </div>

            <div class="modal-body text-center clearfix">
                <div class="row">
                    <div class="col-xs-12 col-sm-5">

                    </div>

                    <div class="col-xs-12 col-sm-7">
                        <div class="modal-title text-left"></div>
                        <div class="social-container">

                            <div class="social clearfix">
                                @if((in_array("show_all", setting("referral.channels")) || in_array("fb", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                                    <div class="col-xs-{!! $isMobile?"2":"3" !!} social-widget">
                                        <a data-url="{!! url('')."/?referral=".$user->getReferralCode()."%26ch%3Dfb" !!}" data-icon="{!! url('') !!}/assets/img/mascot.png" href="#" class="FacebookShareButton">
                                            <img src="{!! url('') !!}/assets/img/refer-icon-facebook.png" alt="Facebook">
                                        </a>
                                    </div>
                                @endif
                                @if((in_array("show_all", setting("referral.channels")) || in_array("tw", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                                    <div class="col-xs-{!! $isMobile?"2":"3" !!} social-widget">
                                        <a href="https://twitter.com/intent/tweet?text=I%20just%20found%20this%20amazing%20tool%20applying%20me%20to%20loads%20of%20scholarships%20automatically!&amp;url={!! url('')."/?referral=".$user->getReferralCode()."%26ch%3Dtw" !!}">
                                            <img src="{!! url('') !!}/assets/img/refer-icon-twitter.png" alt="Twitter">
                                        </a>
                                    </div>
                                @endif
                                @if((in_array("show_all", setting("referral.channels")) || in_array("pi", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                                    <div class="col-xs-{!! $isMobile?"2":"3" !!} social-widget">
                                        <a href="https://pinterest.com/pin/create/button/?url={!! url('')."/?referral=".$user->getReferralCode()."%26ch%3Dpi" !!}&description=I%20just%20found%20this%20amazing%20tool%20applying%20me%20to%20loads%20of%20scholarships%20automatically!&media={!! url('') !!}/assets/img/mascot.png" class="pin-it-button" count-layout="horizontal" target="_blank">
                                            <img border="0" src="{!! url('') !!}/assets/img/refer-icon-pinterest.png" title="Pin It" />
                                        </a>
                                    </div>
                                @endif
                                @if($isMobile)
                                    @if((in_array("show_all", setting("referral.channels")) || in_array("wa", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                                        <div class="col-xs-2 social-widget">
                                            <a href="whatsapp://send" data-text="I just found this amazing tool applying me to loads of scholarships automatically!" data-href="{!! url('')."/?referral=".$user->getReferralCode()."&ch=wa" !!}" class="wa_btn wa_btn_l">
                                                <img border="0" src="{!! url('') !!}/assets/img/refer-icon-whatsapp.png" />
                                            </a>
                                        </div>
                                    @endif
                                    @if((in_array("show_all", setting("referral.channels")) || in_array("sm", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                                        <div class="col-xs-2 social-widget">
                                            <a href="sms:?body=I%20just%20found%20this%20amazing%20tool%20applying%20me%20to%20loads%20of%20scholarships%20automatically!%20{!! url('')."/?referral=".$user->getReferralCode()."&ch=sms" !!}" class="wa_btn_l">
                                                <img border="0" src="{!! url('') !!}/assets/img/refer-icon-sms.png" id="smsicon" />
                                            </a>
                                        </div>
                                    @endif
                                @endif
                                @if((in_array("show_all", setting("referral.channels")) || in_array("ma", setting("referral.channels"))) && !in_array("show_none", setting("referral.channels")))
                                    <div class="col-xs-{!! $isMobile?"2":"3" !!} social-widget last">
                                        <a href="#" class="MailButton">
                                            <img src="{!! url('') !!}/assets/img/refer-icon-mail.png" alt="Mail">
                                        </a>
                                    </div>
                                @endif
                            </div>

                            <div class="col-xs-12">
                                <p class="mod-margin" id="offerText">

                                </p>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <div class="modal-footer col-xs-12 ">
			</div>
		</div>
	</div>
</div>
