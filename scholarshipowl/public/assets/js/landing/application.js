$(document).ready(function() {

// Register Aside form
	$(".registerAside").find(".formGroupContainer").removeClass("col-sm-6 col-md-6 col-md-4 col-md-3 col-md-2");
	$(".registerAside").find(".applyButtonContainer").removeClass("col-lg-offset-3 col-lg-6");
	$(".registerAside").find(".youDeserveItPContainer").removeClass("col-lg-3");
	$(".registerAside").removeClass("hide");

	$('#birth-date').click(function () {
		$(this).closest('.form-group').tooltip('destroy', {
			animated: 'fade',
			delay: {show: 100, hide: 5000}
		});
	});

	$('#greet-window').modal('show');

	$('ul.selectpicker li:first-child').addClass("hidden");

	$(".dropdown-menu, html").on("mouseup pointerup", function (e) {
		$(".dropdown-menu .mCSB_scrollTools").removeClass("mCSB_scrollTools_onDrag");
	}).on("click", function (e) {
		if ($(e.target).parents(".mCSB_scrollTools").length || $(".dropdown-menu .mCSB_scrollTools").hasClass("mCSB_scrollTools_onDrag")) {
			e.stopPropagation();
		}
	});

	$('.firstName').focus(function (event) {
		$(this).removeClass('highlighted');
	});

	$(".checkAllWrapper").click(function (event) {
		$("#selectAll").toggleClass('hidden');
		$("#selectNone").toggleClass('hidden');
	});

	// ---------------------------------------------
	// Register 1 Form Start
	// ---------------------------------------------

	// Phone Input Mask
	if ($("#landingRegForm1").length) {
		$("[name=phone]").inputmask("(999) 999 - 9999", {"placeholder": "(   )     -     ", showMaskOnHover: false});
		var timeoutID = setTimeout(function() {$('[name=first_name]').popover('show')}, 1000);

		$('[name=first_name],[name=last_name],[name=email],[name=phone]').focus(function(e) {
			$('[name=first_name]').popover('hide')
			$('[name=last_name]').popover('hide')
			$('[name=email]').popover('hide')
			$('[name=phone]').popover('hide')
		})
		$('form[name=landingRegForm1]').submit(function(e) {
			e.preventDefault();
			if (!$('[name=agree_terms]').is(':checked')) {
				alert("You must agree with the terms and conditions before you continue!");
				return false;
			}
			var $token = $('meta[name="csrf-token"]').attr('content');
			var $data = $(this).serialize();

			$.ajax({
				type : 'POST',
				url : $(this).attr('action'),
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: $data,
				success: function(ret) {
					if (ret.success) {
						location.href = ret.redirect;
					} else {
						$('[name=first_name]').popover('hide');
						$('[name=first_name]').popover('hide')
						$('[name=last_name]').popover('hide')
						$('[name=email]').popover('hide')
						$('[name=phone]').popover('hide')
						$('[name=' + ret.field + ']').popTooltip( ret.error )
						$('[name=' + ret.field + ']').popover('show')
					}
					//console.log(ret)
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
		})
	}

	/*
	 * RegisterButton JS Class
	 * By Branislav Jovanovic
	 */
	var RegisterButton = AjaxButton.extend({
		onBeforeSend: function(request) {

            $('[name=agree_terms]').click(function() {
                if($(this).is(':checked')) {
                    $(".mustAggre").remove();
                }
            });
			$(".error-tooltip").remove();
			$(".tooltip").remove();
			$(".mustAggre").remove();
			$(".has-error").removeClass("has-error");
		},

		onSuccess: function(response) {



			if(response.status == "error") {
				$.each(response.data, function(k,v) {
					var errorMessage = '<div class="error-tooltip">';
					errorMessage += '<div class="register-tooltip tooltip-trigger" href="#" data-toggle="tooltip" data-original-title="';
					errorMessage += v;
					errorMessage += '" data-animation="true" data-placement="top" ';
					errorMessage += 'class="center-block">' + v + '</a></div>';

					if(k == 'agree_terms'){
                        var mustAggre = '<span class="mustAggre has-error">';
                        mustAggre += 'You must agree with the terms and conditions before you continue!';
                        mustAggre += '</span>';

                        if (!$('[name=agree_terms]').is(':checked')) {
                            $(".checkboxes").append(mustAggre);
                            return false;
                        }

                        $('[name=agree_terms]').click(function() {
                            if($(this).is(':checked')) {
                                $(".mustAggre").remove();
                            }
                        });
                    }else {
                        $('input[name="' + k + '"]').addClass('xxx');
                        $('input[name="' + k + '"]').closest('.form-group').addClass('has-error').prepend(errorMessage);
                        $('.error-tooltip .tooltip-trigger').tooltip('show');

                        $(".xxx").focus(function () {
                            $(".error-tooltip .tooltip-trigger").tooltip('destroy');
                            $(".error-tooltip").remove();
                            $(this).closest('.form-group').removeClass('has-error');
                            $(this).removeClass('xxx');
                        });
                    }
				});
			}
			else if(response.status == "redirect") {
				window.location = response.data;
			}
		}
	});

	$(".RegisterButton").each(function() {
		new RegisterButton($(this));
	});
});