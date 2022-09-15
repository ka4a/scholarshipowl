<div class="modal fade in" id="ReferralMailPopup" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
	    <!-- header -->
		<div class="modal-content text-center">
			<div class="modal-header">
			    <button id="close-widget-refer" type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">×</span>
                    <span class="sr-only">Close</span>
                </button>
			</div>
			<form id="inviteMail" name="inviteMail" action="{{ url_builder('api/v1.0/referrals/mail') }}" method="post" class="center-block clearfix ajax_form">
                <div class="modal-body text-center">
                    Enter a list of email addresses of friends you want to invite to ScholarshipOwl.<br>
                    <br />
                    <a href="#" class="GoogleButton">My gMail Address Book</a>
                    <div class="emailWrapper ">
                        <input type="text" name="friends_emails" placeholder="John@smith.com Jane@parker.com" class="form-element" id="FriendsEmails">
                        <div class="friendEmailsWrapper" id="friendEmailsWrapper">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <a type="button" class="btn btn-primary center-block text-uppercase InviteButton" id="btnInvite" value="" type="submit">Send</a>
                </div>
			</form>
		</div>
	</div>
</div>
