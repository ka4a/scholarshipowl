<div class="modal fade" id="select-popup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content text-center">
			<div class="modal-header">
			</div>
			<div class="modal-body text-center">
			    We found {!! $eligibility_count !!} scholarships for you.<br />
                As a {!! $subscription->getName() !!} member, you get {!! $subscription->isScholarshipsUnlimited()?"unlimited":$subscription->getScholarshipsCount() !!} applications every month.<br /><br />
                Take your pick by marking the checkboxes next to the scholarships of your choice.<br />
                Then click the 'Apply Now' button.<br />
			</div>
			<div class="modal-footer">
			    <label>
			        <input name="do-not-show" type="checkbox" value="do-not-show" id="do-not-show">
			        <span class="lbl padding-0"></span>
			        Don't show this message again<br />
			        </label>
			    <a type="button" class="btn btn-primary center-block buttonSelectProceed" data-dismiss="modal">Ok</a>
			</div>
		</div>
	</div>
</div>