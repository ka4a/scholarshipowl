$(document).ready(function() {
    if ($(".checkboxes-below .formGroupCheckbox.position-coreg3").length > 0) {
        $(".checkboxes-below .formGroupCheckbox").addClass("col-md-3");
        $(".checkboxes-below .formGroupCheckbox.position-coreg3").removeClass("col-md-3");
        $(".checkboxes-below .formGroupCheckbox.position-coreg3").addClass('col-md-6');
    } else {
        if ($(".checkboxes-below .formGroupCheckbox").length == 2) {
            $(".checkboxes-below .formGroupCheckbox").addClass("col-md-6");
        }
        else if ($(".checkboxes-below .formGroupCheckbox").length == 3) {
            $(".checkboxes-below .formGroupCheckbox").addClass("col-md-4");
        }
    }

    if ($(".checkboxes-above .formGroupCheckbox").length == 2) {
        $(".checkboxes-above .formGroupCheckbox").addClass("col-md-6");
    }
    else if ($(".checkboxes-above .formGroupCheckbox").length == 3) {
        $(".checkboxes-above .formGroupCheckbox").addClass("col-md-4");
    }


    $(".registerAside").find(".formGroupContainer, .formGroupCheckbox").removeClass("col-sm-6 col-md-6 col-md-4 col-md-3 col-md-2");

    // Show register form
    $("#registerForm1").removeClass("invisible");

    var tooltipRegister = $(".tooltip-register a");

    if(tooltipRegister.length && tooltipRegister.tooltip) {
        tooltipRegister.tooltip('show');
    }

    var tooltipToggle = $("[data-toggle=tooltip]");

    if(tooltipToggle.length && tooltipToggle.tooltip) {
        tooltipToggle.tooltip('show');
    }

    // hide tooltip about recurring scholarships
    var tooltipRecurrent = $('.recurrent-icon');

    if(tooltipRecurrent.length && tooltipRecurrent.tooltip) {
        tooltipRecurrent.tooltip('hide');
    }

    $('#birth-date').click(function() {
        $(this).closest('.form-group').tooltip('destroy', {
            animated: 'fade',
            delay: {
                show: 100,
                hide: 5000
            }
        });
    });

    $('#greet-window').modal('show');

    $( document.body ).on( 'click', '.bootstrap-select.open .dropdown-menu.open li a', function( event ) {
        $('.selectpicker').parent('.has-error').removeClass('has-error');
        $('.bootstrap-select.has-error').removeClass('has-error');
        $('.help-block').fadeOut('slow');
        $('#alertFillContainer').fadeOut('fast');
    });
    $('.form-control').on('click', function(event) {
        $(this).closest('.form-group').removeClass('has-error');
        $(this).parent().next('.errorState').remove();
    });
    $(document.body).on('click', '.close-alert', function(event) {
        $(this).closest('.alert-').fadeOut('fast');
    });

    /*==========  Navbar toggle class  ==========*/

    $('.navbar-toggle').on('click', function() {
        $(this).parent('div').toggleClass('oppened');
    });


    /*==========  toggle phone number on small screen size  ==========*/
    checkSize(); // run test on initial page load
    $(window).resize(checkSize); // run test on resize of the window

    //Function to the css rule
    function checkSize() {
        if ($('.phone-number').css("display") == "none") {

            $('.glyphicon-earphone').click(function() {
                var phone = $(this).parent().find('.phone-number').data('phone');
                phone = phone.replace(/[\s()–]+/gi, '');

                $('.phoneNumber').toggleClass('phone-number-toggle');

                if ($('.phone-number').parent('div').hasClass('phone-number-toggle')) {
                    $('.phone-number').wrap('<a class="phone-number-link" href="tel:'+phone+'"></a>');
                } else
                    $('.phone-number').unwrap();
            });
        }
    };

/*
        //    $('.selected-package').attr('style', 'height: 100%');
        //  e.target // newly activated tab
        //  e.relatedTarget // previous active tab

        $('a#upgrade').on('click', function(e) {
            $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
                var liHeight = $('.selected li').outerHeight();
                $('.selected li').attr('style', 'height:' + liHeight );
            });
        });
*/

/*
   //set the initial body width
   var originalWidth = 1000;
   resizeTargets();
   $(window).resize(resizeTargets);

   function resizeTargets() {
       $(".selected li").each(function() {
           //get the initial height of every div
           var originalHeight = $(this).height();
           //get the new body width
           var bodyWidth = $("body").width();
           //get the difference in width, needed for hight calculation
           var widthDiff = bodyWidth - originalWidth;
           //new hight based on initial div height
           var newHeight = originalHeight + (widthDiff / 10);
           //sets the different height for every needed div
           $(this).css("height", newHeight);
       });
   }
*/

    // Find matches
    var mql = window.matchMedia("(min-width: 480px)");

    // Add a media query change listener
    mql.addListener(function(m) {
        if (m.matches) {
            // Changed to portrait
            $(window).resize(checkSize); // run test on resize of the window
        } else {
            // Changed to landscape
            $(window).resize(checkSize); // run test on resize of the window
        }
    });

    /*==========  end toggle phone number on small screen size  ==========*/



    //$('#EssaySelect').selectpicker('val', '421');

    // comma
    $.fn.digits = function() {
        return this.each(function() {
            $(this).text($(this).text().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,"));
        })
    };
    $("span.number").digits();


    $('.firstName').focus(function(event) {
        $('.tooltip-register a').tooltip('destroy').fadeOut('fast');
        $(this).removeClass('highlighted');
    });

    // match height
    /*  $(function() {
            $('.selected li').matchHeight({ property: 'min-height' });
        });
    */

    // Select/unselect all
    $(".checkAllWrapper").click(function() {

        $("#selectAll").toggleClass('hidden');
        $("#selectNone").toggleClass('hidden');

        if ($("#selectAll").hasClass("hidden")) {
            // @TODO: Remove to class
            var $isFree = $("input[name=is_free][type=hidden]").val();
            var $credit = $("input[name=credit][type=hidden]").val();
            var $totalCount = $.find("input[name='apply[]']").length;

            if ($totalCount > $credit && !$isFree) {
                //  Removed opening payment popup when too many scholarships are selected
            }
        }
    });


    //  Message count loading
    if ($(".mail-notification-wrapper").length) {
        var $action = "/api/v1.0/mailbox/count";
        var $type = "get";
        var $dataType = "json";

        var $ajax = new Ajax($action, $type, $dataType);

        $ajax.onBeforeSend = function(request) {};

        $ajax.onSuccess = function(response) {
            if (response.status == "ok" && response.data != null) {
                if (response.data.unread != 0) {
                    $("#message-count").text(response.data.unread);
                    $(".mail-notification-wrapper").removeClass("hidden");
                    $(".MailboxButton").attr("data-message-count", response.data.unread);
                }

                $(".unread-messages").text(response.data.unread);
            	$(".total-messages").text("/" + response.data.total);
            }
            else if (response.status == "error") {

            } else if (response.status == "redirect") {
                window.location = response.data;
            }
        };

        $ajax.sendRequest();
    }

    // activate carousel
    $('.carousel').carousel();

    // Refer a Friend widget
    $("#refer-friend-call").click(function(){
        $(this).removeClass('bounceInUp').addClass('slideOutDown').each(function(){this.onmouseup = this.blur();});
        $("#refer-friend").removeClass('bounceOutDown fadeOutDownBig').addClass('openup bounceInUp').each(function(){this.onmouseup = this.blur();});
    });
    $("#close-widget-refer").click(function(){
       $("#refer-friend").removeClass('bounceInUp').addClass('bounceOutDown fadeOutDownBig').each(function(){this.onmouseup = this.blur();});
       $("#refer-friend-call").removeClass('bounceInUp slideOutDown').addClass('bounceInUp').each(function(){this.onmouseup = this.blur();});
    });

	// Missions mobile screen
	$("#step2A-trigger").click(function () {
		if ( $("#step2A").is(":hidden") ) {
			$("#step2A").slideDown("slow");
			document.getElementById('missionsScreen').style.marginTop = "-40px";
		}
		else {
			$( "#step2A" ).hide();
			document.getElementById('missionsScreen').style.marginTop = "-1px";
		};
	});

	// Open specific tab in my account
	var hash = window.location.hash;
	if (hash != "") {
		$('#usersTab li').each(function() {
			$(this).removeClass('active');
		});
		$('.tab-content div.tab-pane.active').each(function() {
			$(this).removeClass('active');
		});
		var link = "";
		$('#usersTab li').each(function() {
			link = $(this).find('a').attr('href');
		if (link == hash) {
			$(this).addClass('active');
		}
		});
		$('.tab-content div').each(function() {
			link = $(this).attr('id');
			if ('#'+link == hash) {
				$(this).addClass('active');
			}
		});
	}
});

function preventReLoad() {
	$(document).on({
		ajaxStop: function(event) {
			event.preventDefault();
			$("body").addClass("loading");
			$(".loadingAnim").css("display", "block");
		}
	});
};

function errorLoader() {
	$(".loadingAnim").css("display", "none");
};

$(".animateToTop").click(function() {
    $("html, body").animate({ scrollTop: $(".winnerRightBlock").offset().top - 66 }, 500);
    return true;
});
