$(document).ready(function() {
	// common.js
	$(".RedirectButton").each(function() {
		new RedirectButton($(this));
	});
	new NotificationMessage();

  // register.js
	$(".LoginButton").each(function() {
		new LoginButton($(this));
	});

	// $(".EligibilityButton").each(function() {
	// 	new EligibilityButton($(this));
	// });

	$(".RegisterButton").each(function() {
		new RegisterButton($(this));
	});

	$(".RegisterButtonCTA").each(function() {
		new RegisterButtonCTA($(this));
	});

	$(".Register2Button").each(function() {
		new Register2Button($(this));
	});

	$(".Register3Button").each(function() {
		new Register3Button($(this));
	});

    $(".ForgotPasswordButton").each(function() {
        new ForgotPasswordButton($(this));
    });

    $(".ResetPasswordButton").each(function() {
        new ResetPasswordButton($(this));
    });

    $(".PackageButton").each(function() {
        var packageButton = new PackageButton($(this));
    });

	$(".PaymentFormButton").each(function() {
		new PaymentFormButton($(this));
	});

	$(".MissingPaymentDataButton").each(function() {
		new MissingPaymentDataButton($(this));
	});

    $(".StartMissionButton").each(function() {
		new StartMissionButton($(this));
	});

    $(".MissionCompleted").each(function() {
        displayMissionCompletePopup($(this));
    });

    if (typeof InviteButton !== "undefined") {
        $(".InviteButton").each(function() {
            new InviteButton($(this));
        });
    }
    if(typeof MailButton !== "undefined") {
        $(".MailButton").each(function () {
            new MailButton($(this));
        });
    }
    $(".MissionButton").each(function() {
        new MissionButton($(this));
    });
    $(".ReferralMissionButton").each(function() {
        new ReferralMissionButton($(this));
    });

    $(".CopyToClipboardButton").each(function() {
        new CopyToClipboardButton($(this));
    });

    $(".ReferralLinkInput").each(function() {
        new ReferralLinkInput($(this));
    });


	/*
	 * ContactButton JS Class
	 * By Marko Prelic
	 */
	var ContactButton = AjaxButton.extend({
		onBeforeSend: function(request) {
			$("#content").removeClass("error");
			$("input[name=name]").removeClass("error");
			$("input[name=email]").removeClass("error");
		},

		onSuccess: function(response) {
			if(response.status === 200) {
				var message = "<p class='success'>Your mail was successfully sent</p>";
				$("#contact-form").html(message).attr('style', 'font-size: 18px; margin: 10px; text-align: center;');
			} else if(response.error) {
				$.each(response.error, function(key, value) {
					$("#" + key).addClass("error");
					$("input[name=" + key + "]").addClass("error");
				});
			}
		}
	});

	// user.js
	$(".SaveProfile").each(function() {
		new SaveProfile($(this));
	});

	$("#ContactButton").each(function() {
		new ContactButton($(this));
	});

	$(".ApplyButton").each(function() {
		new ApplyButton($(this));
	});

	$(".ApplyPageButton").each(function() {
		new ApplyPageButton($(this));
	});

    $(".ApplyTable").each(function() {
        new ApplyTable($(this));
    });

    $(".MyAppsTable").each(function() {
        new MyAppsTable($(this));
    });

    $(".MyAppsSubmitButton").each(function() {
        new MyAppsSubmitButton($(this));
    });

    $(".MyAppsRemoveButton").each(function() {
        new MyAppsRemoveButton($(this));
    });

    $(".ReferralsTable").each(function() {
        new ReferralsTable($(this));
    });

    $(".AccountMissionsTable").each(function() {
        new AccountMissionsTable($(this));
    });

    $(".MissionDirectLink").each(function() {
        new MissionDirectLink($(this));
    });

    $("#mailbox").each(function() {
        new Mailbox($(this));
    });
    $(".MailboxButton").each(function() {
        new MailboxButton($(this));
    });
    $(".ExploreMoreButton").each(function() {
        new ExploreMoreButton($(this));
    });

    if(typeof FacebookShareButton !== "undefined") {
        $(".FacebookShareButton").each(function () {
            new FacebookShareButton($(this));
        });
    }


	$(".PopupDisplay").each(function () {
		if (($(this).data("popup-display") == 1 || $(this).data("popup-display") == 3) && $(this).data("popup-delay") != 0) {
			new PopupDisplay($(this), $(this).data("popup-delay"), false);
		}else if($(this).data("popup-display") == 4){
			if($(this).data("popup-display-times") != 0){
				var $numDisplayed = readCookie("popup-display-" + $(this).data("popup-id"));
				if($numDisplayed <= $(this).data("popup-display-times")){
					initExitPopup($(this));
				}
			}else{
				initExitPopup($(this));
			}
		}
	});

    if($("#congratulations-on-upgrading-popup").length){
        $("#congratulations-on-upgrading-popup").modal("show");
    }

    // Show Upgrade Dialog
    var $showUpgrade = $("input[type=hidden][name=payment_show_popup]").val();
    if ($showUpgrade) {
        var page = $(".GetMoreScholarshipsButton[data-source-page]").data("source-page");
    	new PaymentPopup($("#payment-popup"), page);
    }


    /*
     * Global Variables
     */
    $('.carousel').carousel();

    function getCookieValue(name) {
      var match = document.cookie.match(new RegExp(name + '=([^;]+)'));
      if (match) return match[1];
    }

    if (!getCookieValue("dedicatedEmailNotification")) {
        $('.note-dedicated-email').show();
    }

    $('.close-notification').click(function() {
        var date = new Date;
        date.setDate(date.getDate() + 365);
        document.cookie = "dedicatedEmailNotification=1;expires=" + date.toUTCString();
        $('.note-dedicated-email').hide('slow');
    });

    // cookie disclaimer
    (function() {
        var modal = $('#cookie-disclaimer');

        if(modal.length === 0) {
          return;
        }

        modal.find('.ctrl_cookie').click(function(){
          modal.hide();
        });

        if(!getCookieValue('cookiePrivatePolicy')) {
            modal.show();
        }

        var date = new Date;
        date.setDate(date.getDate() + 365);
        document.cookie = "cookiePrivatePolicy=notified;expires=" + date.toUTCString() + "; path=/";
    })();

    // speech bubble
    (function() {
      var bubble = document.querySelector('.bubble');

        if(!bubble) {
            return;
        }

				setTimeout(function () {
            bubble.className = 'bubble bubble_zoom-in';
            bubble.style.display = 'block';
        }, 1000);
    })();

    // remove header if it's mailbox page
    // TODO remove it when mailbox will be in client routing
    (function() {
        if(document.location.pathname === '/mailbox') {
            var header = document.getElementById('vue-header');

            if(!header) return;

            header.parentNode.removeChild(header);
        }
    })();

     // ApplicationCounter
		(function() {
		  var $applicationCounter = $("#app-counter");

          if ($applicationCounter.length === 0) {
            return;
          }

          var endValue = parseInt($applicationCounter.attr('data-count')),
              startValue = 1000000;

          var counter = new CountUp("app-counter", startValue, endValue, 0, 3, {
            useEasing: true,
            easingFn: function (t, b, c, d) {
              var ts = (t /= d) * t;
              var tc = ts * t;
              return b + c * (tc + -3 * ts + 3 * t);
            },
            useGrouping: true,
            separator: ',',
            decimal: '.',
            prefix: '',
            suffix: ''
          });

          setTimeout(counter.start, 2000)
		})();

		// init tooltips
		(function() {
				var tooltipTarget = $('.tooltip-controller');

        if (tooltipTarget.length === 0) return;

        $("[data-toggle=tooltip]").tooltip();

        tooltipTarget.tooltip('hide');

        tooltipTarget.on('mouseover', function(){
            $(this).tooltip('show');
        });

        tooltipTarget.on('mouseout', function(){
            $(this).tooltip('hide');
        });
    })();
});
