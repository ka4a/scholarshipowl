
<div class="modal fade" id="payment-popup" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
			</div>
			<div class="modal-body text-center">
			    <span id="GeneralPackageMessage">{!! \App\Entity\FeaturePaymentSet::popupTitleDisplay() !!}</span>
			</div>
			<div class="modal-footer">
			    <a type="button" class="btn btn-primary center-block" href="{{ url_builder("upgrade-mobile") }}">Upgrade</a>
			    <a data-dismiss="modal">Continue without upgrade</a>
			</div>
		</div>
	</div>
</div>
