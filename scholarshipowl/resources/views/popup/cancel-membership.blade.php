<div id="membership-cancel-modal" class="modal-confirm modal fade" tabindex="-1" role="dialog" aria-labelledby="membership-cancel-modal" aria-hidden="true">
    <div class="vertical-alignment-helper">
        <div class="modal-dialog vertical-align-center">
            <div class="modal-content">
                <div class="modal-header"><h4 class="header-title">Are you absolutely sure?</h4></div>
                <div class="modal-body text-center">
                    {!!
                        map_tags_provider(
                            setting($subscription->isFreeTrial() ?
                                \App\Entity\Setting::SETTING_FREE_TRIAL_CANCEL_SUBSCRIPTION :
                                \App\Entity\Setting::SETTING_CANCEL_SUBSCRIPTION_TEXT
                            ),[
                                $subscription->getAccount(),
                                ['subscription', $subscription],
                                ['eligibility_count' => $eligibility_count]
                            ]
                        )
                    !!}
                </div>
                <div class="modal-footer">
                    <div class="row">
                        <div class="col-lg-10 col-lg-offset-1">
                            <button class="btn btn-primary btn-later keep-active" data-dismiss="modal">Keep membership active</button>
                        </div>
                    </div>
                    <a class="cancel-subscription" href="{{ route('cancel-subscription', $subscription->getSubscriptionId()) }}">
                        Cancel Now
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
