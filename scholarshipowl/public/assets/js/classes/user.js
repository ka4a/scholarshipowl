
/*
 * ReferralsTable JS Class
 * By Marko Prelic
 */
var ReferralsTable = Loadable.extend({
	onSuccess: function(response) {
		$(".ReferralsTable tbody tr").remove();

		$.each(response.data, function(index, value) {
			var $html = "<tr>"
			$html += "<td>" + value.first_name + "</td>";
			$html += "<td>" + value.created_date + "</td>";
			$html += "<td>" + value.upgraded + "</td>";
			$html += "</tr>";

			$(".ReferralsTable tbody").append($html);
		});
	}
});


/*
 * SaveProfile JS Class
 * By Marko Prelic
 */
var SaveProfile = AjaxButton.extend({
	_init: function(element) {
		this._super(element);

		var inputZipCode = document.querySelector('[name="zip"]');

		if(!inputZipCode) return;

		function leave5Numbers(str) {
      return str.replace(/\D+/g, '').substr(0, 5);
    }

    function leaveAlphaNum(str) {
      return str.replace(/[^a-z0-9]/gi,'').substr(0, 31);
    }

    var inputFormat = SOWLStorage.settings.uc === 'US'
          ? leave5Numbers : leaveAlphaNum;

		inputZipCode.addEventListener('input', function(ev) {
			ev.target.value = inputFormat(ev.target.value);
		})
	},

	onBeforeSend: function(request) {
		$(".help-block").hide();
	},

	onSuccess: function(response) {
		if(response.status == "ok") {
			var $applyUrl = response.data.apply_url;

			$("#Popup .modal-body").html(response.message);
			$("#Popup .modal-footer").html("<a type='button' class='btn btn-warning btn-block text-uppercase text-center' href='" + $applyUrl + "'>Apply Now</a><a type='button' class='btn btn-primary btn-block text-center' data-dismiss='modal'>Continue completing profile</a>");

			$("#Popup").modal("show");
		}
		else if(response.status == "error") {
			$.each(response.data, function(key, value) {
				$('.help-block[data-error="' + key + '"]').html(value);
				$('.help-block[data-error="' + key + '"]').show();
			});
		}
	}
});


/*
 * MailboxButton JS Class
 * By Marko Prelic
 */
var MailboxButton = Class.extend({
	_init: function(element) {
		var caller = this;

		element.click(function(e) {
			e.preventDefault();

			var $messageCount = $(caller).attr("data-message-count");
			if ($.isEmptyObject($messageCount)) {
				var $noEmailsMessage = "Currently, there are no emails in your application inbox to display. Please check back regularly for updates on your scholarships";

				$("#Popup .modal-body").html($noEmailsMessage);
				$("#Popup .modal-footer").html("<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>");

				$("#Popup").modal("show");
			}
			else {
				window.location = $(caller).attr("href");
			}
		});
	}
});

/*
 * GetMoreScholarshipsButton JS Class
 * By Ivan Krkotic
 */
var GetMoreScholarshipsButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("click", function(e) {
				e.preventDefault();
				element.data("shown", false);
				invokeUpgradeModal();
			});
		}
	}
});

function invokeUpgradeModal() {
	var page = document.location.pathname.substring(1);

	if($(".PopupDisplay").length) {
		$(".PopupDisplay").each(function () {
			if (($(this).data("popup-display") == 1 || $(this).data("popup-display") == 3)) {
				if ($(this).data("popup-delay") == 0) {
					new PopupDisplay($(this), $(this).data("popup-delay"), false);
				} else {
					new PaymentPopup($("#payment-popup"), page);
				}
			}else{
				new PaymentPopup($("#payment-popup"), page);
			}
		});
	} else {
		new PaymentPopup($("#payment-popup"), page);
	}
}

/*
 * MailButton JS Class
 * By Ivan Krkotic
 */
var MailButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;

		if (element) {
			element.bind("click", function(e) {
				e.preventDefault();
				$("#ReferralMailPopup").modal("show");
			});
		}
	}
});

/*
 * InviteButton JS Class
 * By Ivan Krkotic
 */
var InviteButton = AjaxButton.extend({
	onSuccess: function(response) {
		if(response.status == "error") {
			$("#ReferralMailPopup .modal-body .error").remove();
			var $errorMessage = '<div class="error text-danger">';
			$errorMessage += response.message;
			$errorMessage += '</div>';
			$("#ReferralMailPopup .modal-body").append($errorMessage);
		}
		else if(response.status == "ok") {
			$("#ReferralMailPopup .modal-body .error").remove();
			var $oldBodyData = $("#ReferralMailPopup .modal-body").html();
			var $oldFooterData = $("#ReferralMailPopup .modal-footer").html();
			$("#ReferralMailPopup .modal-body").html(response.data);
			$("#ReferralMailPopup .modal-footer").html("<a type='button' class='btn btn-primary center-block' data-dismiss='modal'>Ok</a>");

            var $action = "api/v1.0/referrals/share";
            var $type = "post";
            var $data = { channel: "Email" };

            $.ajax({
                method: $type,
                url: $action,
                data: $data
            }).done(function( msg ) {
                if(msg.data.upgraded == "Yes"){
                    var successPopup = $.parseHTML("<div id=\"congratulations-on-upgrading-popup\" class=\"modal fade in payment-popups\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"congratulations-on-upgrading\" aria-hidden=\"true\"> <div class=\"modal-dialog container\"> <div class=\"modal-content row text-center\"> <div class=\"modal-header col-xs-12 clearfix\"> <button type=\"button\" class=\"close img-circle text-center\" data-dismiss=\"modal\"> <span aria-hidden=\"true\">x</span> <span class=\"sr-only\">Close</span> </button> </div> <div class=\"modal-body col-xs-12 text-left clearfix\"><div class=\"col-sm-offset-4 col-sm-8\"> <h3 class=\"text-uppercase\"> " + msg.data.successMessage + " </h3><div class=\"divider\"></div><p> <small>Check out status of your applications, news from scholarship providers and more in your Account page</small> </p> </div></div> <div class=\"modal-footer col-xs-12\"> <div class=\"row\"> <div class=\"col-xs-12\"> <p class=\"text-left\"> </p> </div> </div> </div> </div> </div> </div>");
                    $(successPopup).modal("show");
                }
            });

			$("#ReferralMailPopup").on('hidden.bs.modal', function (e) {
				$("#ReferralMailPopup .modal-body").html($oldBodyData);
				$("#ReferralMailPopup .modal-footer").html($oldFooterData);
				$(".InviteButton").each(function() {
					new InviteButton($(this));
				});
			});
		}
	}
});

/*
 * CopyToClipboardButton JS Class
 * By Ivan Krkotic
 */
var CopyToClipboardButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;
		if (element) {
			var client = new ZeroClipboard( $(".copy-link") );

			client.on( "ready", function( readyEvent ) {
				client.on( "aftercopy", function( event ) {
					$('.ReferralLinkInput').notify("Text copied to clipboard.", "warn");
				} );
			} );
		}
	}
});

/*
 * ReferralLinkInput JS Class
 * By Ivan Krkotic
 */
var ReferralLinkInput = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;
		if (element) {
			element.bind("click", function(e) {
				caller.select();
			});
			element.bind("mouseup", function(e) {
				e.preventDefault();
			});
		}
	}
});

/*
 *  AccountMissionsTable JS Class
 *  By Ivan Krkotic
 */
var AccountMissionsTable = Loadable.extend({
	onSuccess: function(response) {
		$(".AccountMissionsTable tbody tr").remove();
		if(response.data.length) {
			$.each(response.data, function (index, value) {
				if (value.active == 1) {
					var $statusCell = "";
					if (value.status == "Completed") {
						$statusCell = "<div class=\"text-center text-success\"><span class=\"glyphicon glyphicon-ok\"></span></div>Accomplished";
					} else if (value.status == "In Progress" || value.status == "Pending") {
						$statusCell = "<a href=\"#\" class=\"MissionDirectLink btn btn-success text-uppercase\" data-mission-id=\"" + value.mission_id + "\">Continue</a>";
					} else if (value.status == "Not Started") {
						$statusCell = "<a href=\"#\" class=\"MissionDirectLink btn btn-warning text-uppercase\" data-mission-id=\"" + value.mission_id + "\">Start</a>";
					}
					var $html = "<tr>"
					$html += "<td class=\"no-break\">" + value.name + "</td>";
					$html += "<td>" + ((typeof value.package_description == "string" && value.package_description != "") ? value.package_description : value.description) + "</td>";
					$html += "<td class=\"no-break\">" + value.start_date + "<br />" + value.end_date + "</td>";
					$html += "<td>" + value.reward + "</td>";
					$html += "<td>" + $statusCell + "</td>";
					$html += "</tr>";

					$(".AccountMissionsTable tbody").append($html);
				}
			});
			$(".MissionDirectLink").each(function () {
				new MissionDirectLink($(this));
			});
		}
	}
});

/*
 *  MissionDirectLink JS Class
 *  By Ivan Krkotic
 */
var MissionDirectLink = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;
		if (element) {
			element.bind("click", function(e) {
				e.preventDefault();
				var $missionId = element.attr("data-mission-id");

				var $missionOptions = $(".MissionOptions[data-mission-id=" + $missionId + "]");
				var $missionOptionsDiv = $missionOptions.find(".MissionOptionsDiv");

				$("[href=#missionTab]").tab('show');

				new MissionGoalsDiv($missionOptionsDiv);

				$("#payment-popup-direct-mission").modal("show");


				$("#payment-popup-direct-mission").on("hidden.bs.modal", function () {
					$("#payment-wizard a:first").tab("show");
					$(".MissionOptions").addClass("hidden");
					clearInterval($globalRefreshInterval);
				});
			});
		}
	}
});

/*
 *   PopupDisplay JS Class
 *   By Ivan Krkotic
 */
var PopupDisplay = Element.extend({
	_init: function(element, $delay, $isAfter) {
		this._super(element);
		var caller = this;
		if (element) {
			if(element.data("popup-display-times") != 0){
				if(!readCookie("popup-display-" + element.data("popup-id"))){
					createCookie("popup-display-" + element.data("popup-id"), 1, 20*365);
				}else{
					var $numDisplayed = readCookie("popup-display-" + element.data("popup-id"));
					if($numDisplayed >= element.data("popup-display-times")){
						if($delay == 0){
							var page = "";
							if (element.data("source-page") !== "") {
								page = element.data("source-page");
							}
                            if(!$isAfter) {
                                new PaymentPopup($("#payment-popup"), page, element);
                            }
						}
						return;
					}else{
						createCookie("popup-display-" + element.data("popup-id"), ++$numDisplayed, 20*365);
					}
				}
			}
			if($delay > 0){
				setTimeout(function(){ caller.showPopup(element, $isAfter) }, $delay * 1000)
			}else{
				caller.showPopup(element, $isAfter);
			}
		}
	},

	showPopup:function(element, $isAfter){
		var $action = "/api/v1.0/popup/" + element.data("popup-id");
		var $type = "get";

		var $popupType = element.data("popup-type");

		var $ajax = new Ajax($action, $type);

		if ($popupType == "raf") {
			var $popup = $("#raf-exit-popup");
		} else {
			var $popup = $("#package-exit-popup");
		}

		$ajax.onSuccess = function (response) {
			var $data = response.data;
			$popup.find(".modal-title").html("<h3>" + $data.popup_title + "</h3>" + $data.popup_text);

			if ($popupType == "mission") {
				$popup.find(".modal-body .MissionOptionsDiv").attr("data-url", "/api/v1.0/missions?mission=" + $data.popup_target_id);
				$popup.find(".modal-body .MissionOptionsDiv").attr("data-mission-id", $data.popup_target_id);
				$popup.find(".modal-dialog").css("top", "-100%");
				new MissionGoalsDiv($popup.find(".modal-body .MissionOptionsDiv"));
			} else if ($popupType == "package") {
				var $action = "/api/v1.0/package/view/" + $data.popup_target_id;
				var $type = "get";

				var $ajax = new Ajax($action, $type);

				$ajax.onSuccess = function (response) {
					$popup.find(".modal-body .PackageDiv").html(response.data);

					$("[href=#missionExitStep2]").tab('show');

					new PackageButton($popup.find(".modal-body .PackageButton"));
					$popup.find(".modal-body .PackageButton").on("click", function(){
						element.data("shown", true);
						$("#step2").addClass("comesFromExit");
					});

					// Override to make back button work on #step2 tab
					$(document).on("click", "#step2.comesFromExit .backToBeginning", function(){
						$("#package-exit-popup").modal("show");
						$("#package-exit-wizard").find("[href=#missionExitStep2]").tab("show");
						$("#step2").removeClass("comesFromExit");
					});
				}

				$ajax.sendRequest();
			}

			if(!window.modalVue)
				throw Error("modal instance is not defined");

			window.modalVue.showModal({
				modalName: "promotion",
				content: {
					title: $data.popup_title,
					text: [$data.popup_text]
				},
				hooks: {
					after: function () {
						if($isAfter) {
							$("#package-exit-wizard a:first").tab("show");
						}else{
							if ($("#popupActionCompleted").val() == 0) {
								var page = "";
								if (element.data("source-page") !== "") {
									page = element.data("source-page");
								}
								if (element.data("shown") != true && element.data("trigger-upgrade") == 1) {
									new PaymentPopup($("#payment-popup"), page, element);
									element.data("shown", true);
								}
							}
							$("#package-exit-wizard a:first").tab("show");
						}
					}
				}
			})
		}

		$ajax.sendRequest();
	}
});

/*
 *   FacebookShareButton JS Class
 *   By Ivan Krkotic
 */
var FacebookShareButton = Element.extend({
	_init: function(element) {
		this._super(element);
		var caller = this;
		if (element) {
			element.bind("click", function(e) {
				e.preventDefault();
				var $url = element.data("url");
				var $icon = element.data("icon");

				FB.ui(
					{
						method: "feed",
						name: "ScholarshipOwl",
						link: $url,
						picture: $icon,
						caption: "ScholarshipOwl",
						description: "I just found this amazing tool applying me to loads of scholarships automatically!"
					},
					function(response) {
						if (response && response.post_id) {
							var $action = "api/v1.0/referrals/share";
							var $type = "post";
							var $data = { channel: "Facebook" };

							$.ajax({
								method: $type,
								url: $action,
								data: $data
							}).done(function( msg ) {
                                if(msg.data.upgraded == "Yes"){
                                    var successPopup = $.parseHTML("<div id=\"congratulations-on-upgrading-popup\" class=\"modal fade in payment-popups\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"congratulations-on-upgrading\" aria-hidden=\"true\"> <div class=\"modal-dialog container\"> <div class=\"modal-content row text-center\"> <div class=\"modal-header col-xs-12 clearfix\"> <button type=\"button\" class=\"close img-circle text-center\" data-dismiss=\"modal\"> <span aria-hidden=\"true\">x</span> <span class=\"sr-only\">Close</span> </button> </div> <div class=\"modal-body col-xs-12 text-left clearfix\"><div class=\"col-sm-offset-4 col-sm-8\"> <h3 class=\"text-uppercase\"> " + msg.data.successMessage + " </h3><div class=\"divider\"></div><p> <small>Check out status of your applications, news from scholarship providers and more in your Account page</small> </p> </div></div> </div> </div> </div>");
                                    $(successPopup).modal("show");
                                }
							});
						}
					}
				);
			});
		}
	}
});

$(function() {

    $(document).ready(function() {
        var $checkout = $('#sowl-checkout');

        if ($checkout.length) {
            window.SOWLCheckoutHtml = $checkout.clone().wrap('<div>').parent().html();

            var packageButton = $('a.payment-opener');
            if(!packageButton.length){
                window.SOWLCheckout = new SOWLElementCheckout($checkout);
            }
    	}
    });

  $("#step3").find(".backToBeginning").on("click", function(){
    $("a[href='#step2']").tab("show");
    $("a[href='#step1']").tab("hide");
  });

  $("#payment-popup").on("hidden.bs.modal", function() {
    $("#step2").removeClass("comesFromExit");
  });

  $('.subscription-cancel').click(function(event) {
    event.preventDefault();

    var $membershipTab = $('[href=#membership-tab]').click();

    $('html, body').animate({scrollTop: $membershipTab.offset().top}, 500, function() {
      $('.subscription-cancel-container #cancel-membership').click();
    });
  });

  $(".GetMoreScholarshipsButton").each(function() {
    if (typeof GetMoreScholarshipsButton !== 'undefined') new GetMoreScholarshipsButton($(this));
  });

  $('.subscription-cancel-container').each(function() {
    var $this = $(this);

    $this.find('#cancel-membership').click(function(e) {
      e.preventDefault();

      $('#membership-cancel-modal').modal('show');
    });
  });

  /**
   * Recurrence setting in profile
   */
  $('.recurring-application-settings input[type=radio][name=recurring_application]')
    .change((function() {
      var request;

      return function () {
        if (!request) {
          request = $.post('/post-recurrence', {recurring_application: this.value})
            .always(function() {
              request = null;
            });
        }
      }
    })());
});

if (typeof twttr !== 'undefined') {
//  Register twitter tweet event
  twttr.ready(
    function (twttr) {
      twttr.events.bind(
        "tweet",
        function (event) {
          var $action = "api/v1.0/referrals/share";
          var $type = "post";
          var $data = {channel: "Twitter"}

          $.ajax({
            method: $type,
            url: $action,
            data: $data
          }).done(function (msg) {
            if (msg.data.upgraded == "Yes") {
              var successPopup = $.parseHTML("<div id=\"congratulations-on-upgrading-popup\" class=\"modal fade in payment-popups\" tabindex=\"-1\" role=\"dialog\" aria-labelledby=\"congratulations-on-upgrading\" aria-hidden=\"true\"> <div class=\"modal-dialog container\"> <div class=\"modal-content row text-center\"> <div class=\"modal-header col-xs-12 clearfix\"> <button type=\"button\" class=\"close img-circle text-center\" data-dismiss=\"modal\"> <span aria-hidden=\"true\">x</span> <span class=\"sr-only\">Close</span> </button> </div> <div class=\"modal-body col-xs-12 text-left clearfix\"><div class=\"col-sm-offset-4 col-sm-8\"> <h3 class=\"text-uppercase\"> " + msg.data.successMessage + " </h3><div class=\"divider\"></div><p> <small>Check out status of your applications, news from scholarship providers and more in your Account page</small> </p> </div></div> <div class=\"modal-footer col-xs-12\"> <div class=\"row\"> <div class=\"col-xs-12\"> <p class=\"text-left\"> </p> </div> </div> </div> </div> </div> </div>");
              $(successPopup).modal("show");
            }
          });
        }
      );
    }
  );
}
