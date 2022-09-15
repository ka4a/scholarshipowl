<!-- Refer a friend modal -->
<div class="modal fade in modal-wide raf-seurveys-popup" id="raf-surveys-popup" tabindex="-1" role="dialog" aria-labelledby="raf-survey-label" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content clearfix text-center">

			<!-- Modal Header -->
			<div class="modal-header text-left">
	            <button type="button" class="close img-circle text-center" data-dismiss="modal">
        	        <span aria-hidden="true">×</span>
            	    <span class="sr-only">Close</span>
            	</button>

            	<p class="h5 text-semibold">
					Month
            	</p>
				<p class="h4 text-semibold">
					Complete 2 surveys to get <span class="text-uppercase">full</span> access for a month.
				</p>

			</div>

			<!-- Modal Body -->
			<div class="modal-body text-center">

				<div class="row">
					<div class="col-xs-12 col-sm-4">
						<div class="modal-item">
							<img class="img-responsive center-block" src="https://placehold.it/200x60/00ced1/fff">
							<p class="text-light">
								Join a global community to share opinions in return for points and rewards
							</p>
							<div>
								<a type="button" class="btn btn-warning btn-block text-uppercase">Start</a>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4">
						<div class="modal-item">
							<img class="img-responsive center-block" src="https://placehold.it/200x60/00ced1/fff">
							<p class="text-light">
								Earn rewards by influencing government and non-profit decision makers
							</p>
							<div>
								<a type="button" class="btn btn-warning btn-block text-uppercase">Start</a>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-4">
						<div class="modal-item">
						    <div class="row text-center">
						      <div class="col-xs-4 social-widget">
						        <a href="#" class="MailButton">
						          <img class="img-responsive" src="assets/img/refer-icon-mail.png" alt="Mail">
						        </a>
						      </div>
						      <div class="col-xs-4 social-widget">
						        <a data-url="{{ url('') }}" data-icon="{{ url('') }}/assets/img/mascot.png" href="#" class="FacebookShareButton">
						          <img class="img-responsive" src="assets/img/refer-icon-facebook.png" alt="Facebook">
						        </a>
						      </div>
						      <div class="col-xs-4 social-twitter social-widget">
						        <a onclick="javascript:window.open(this.href, '', 'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=600,width=600');return false;" href="https://twitter.com/share?text=Check%20out%20ScholarshipOwl.com.%0aI%20already%20found%20{{ $eligibility_count }}%20scholarships!&amp;url={{ url('/?referral='.$user->getReferralCode()) }}">
						          <img class="img-responsive" src="assets/img/refer-icon-twitter.png" alt="Twitter">
						        </a>
						      </div>
						    </div>
							<p class="text-light">
								Invite your friends to get <span class="text-uppercase">free</span> pass and gift them a <span class="text-uppercase">free</span> membership.
							</p>
							<div>
								<a type="button" class="btn btn-warning btn-block text-uppercase">Start</a>
							</div>
						</div>
					</div>
				</div>
			</div>

            <!-- modal footer -->
            <div class="modal-footer col-xs-12">
                <div class="row">
                    <div class="col-xs-4 col-sm-2">

                        <div class="prevNext">
                            <a id="previous" href="#" class="prevNextBtn text-right pull-left backToBeggining">
                                <span>
                                  Back
                                </span>
                                <div class="arrow-btn">
	                                <div class="arrow">
	                                    <span class="a1"></span>
	                                    <span class="a2"></span>
	                                    <span class="a3"></span>
	                                    <span class="a4"></span>
	                                    <span class="a5"></span>
	                                    <span class="a6"></span>
	                                    <span class="a7"></span>
	                                    <span class="a8"></span>
	                                    <span class="a9"></span>
	                                    <span class="a10"></span>
	                                    <span class="a11"></span>
	                                </div>
	                            </div>
	                        </a>
	                    </div>
	                </div>

	                <div class="col-xs-8 col-sm-10">
	                    <p class="text-left">

	                    </p>
	                </div>
	            </div>
	        </div>


		</div>
	</div>
</div>
