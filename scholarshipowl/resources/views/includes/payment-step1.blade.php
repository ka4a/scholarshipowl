<!-- STEP 1 -->
<div class="tab-pane fade" id="step1">

    <div class="modal-header clearfix">
        <button type="button" class="close img-circle text-center" data-dismiss="modal">
            <span aria-hidden="true">×</span>
            <span class="sr-only">Close</span>
        </button>
        <div id="GeneralPackageMessage">{!! \App\Entity\FeaturePaymentSet::popupTitleDisplay() !!}</div>
    </div>

    <div class="modal-body text-left clearfix">
        <section id="selectPayment" class="clearfix selectPayment">
            <div id="packages" class="clearfix">
                @include('includes.upgrade-form')
            </div>
        </section>
    </div>
    <div class="modal-footer">
    </div>
    @include('includes/texts/deserve-it-disclaimer')
</div>
<!-- /step 1 -->
