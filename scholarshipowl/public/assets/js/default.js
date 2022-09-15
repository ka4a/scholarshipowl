;showHideCollegeFields = function() {
	if ($('[name=current_school_level]').val() == '') {
		$('.show_highschool').hide();
		$('.show_college').hide();
		$('.show_any').hide();
		$('#additional-data').hide();
	} else {
		var $college_options = ["College 1st year","College 2nd year","College 3rd year","College 4th year"];
		if ($college_options.indexOf($('[name=current_school_level]').val()) !== -1) {
			$('.show_highschool').hide();
			$('#additional-data').hide().delay('300').slideDown('500');
			$('.show_college').show();
		} else {
			$('.show_college').hide();
			$('#additional-data').hide().delay('300').slideDown('500');
			$('.show_highschool').show();
		}
		$('.show_any').show();
	}
};

function scrollTo(element) {
	$('html,body').animate({
	  scrollTop: element.offset().top
	}, 500);
}
$.fn.popTooltip = function( $content, $delay ) {
	this.popover('destroy')
	this.popover({
		html: true,
		content: $content,
		placement:'top',
		trigger: 'manual',
		template: '<div class="popover ' + $(this).attr('data-class') + '" style="' + $(this).attr('data-style') + '"><div class="arrow"></div><div class="popover-content"></div></div>'
	});
};

/* our custom dropdown */
$(function() {
	// global popovers
	$('.pop').each(function() {
		$(this).popTooltip()
	})

	$('.carousel').carousel()

	var $lastScrollPos = 0;
	$( window ).scroll(function() {
		if ($( window ).scrollTop() > $lastScrollPos  || ($( window ).scrollTop() < 1)) {
			if ($('.navbar-2').css('position') !== 'absolute') {
				$('.navbar-2').css('position','absolute');
			}
		} else {
			if ($('.navbar-2').css('position') !== 'fixed') {
				$('.navbar-2').css('position','fixed');
				$('.navbar-2').css('height','0px').animate({height: "83px"}, 200);
			}
		// $('.navbar-2').removeClass('affix');
		// } else {
		//	$('.navbar-2').addClass('affix');
		}
		$lastScrollPos = $( window ).scrollTop();
	});


	// add events for open/close, bootstrap 2.3.2 does not have them
//	$('.btn-group.custom-select').on('click', function () {
//		$(this).toggleClass('open');
//		if ($(this).hasClass('open')) {
//			$(this).trigger('open');
//		} else {
//			$(this).trigger('close');
//		}
//		return false;
//	});
//
//	$('.btn-group.custom-select ul.dropdown-menu li > a').on('click', function() {
//		$('.btn.select' , $(this).closest('.btn-group.custom-select')).html( $(this).html() );
//		$(this).closest('.btn-group.custom-select').attr('value', $(this).attr('value')).removeClass('open').trigger('change',[ $(this).attr('value') ]).trigger('close');

//		return false;
//	});

	// enable our custom scrollbar
	$('.btn-group.custom-select').on('open', function () {
		$(".scroll", $(this) ).mCustomScrollbar();
	});
	$('.btn-group.custom-select').on('close', function () {
		$(".scroll", $(this) ).mCustomScrollbar("destroy");
	});

	$('.selectpicker').selectpicker();

	$('.datepicker').datepicker({
		orientation: "top right",
		startDate: "1/1/1950",
		endDate: "12/31/2005",
		autoclose: true
	});

	// dummy submit to spawn error message
	$('#login-form').on('click', '#login-submit', function(e) {
		$.post('/user/login',{
				email: $('[name=email]').val(),
				password: $('[name=password]').val()
			}, function(ret) {
			if (ret.success) {
				location.href = ret.redirect;
			} else {
				var errorMessage = '<div class="error">';
				errorMessage += '<div class="icon"></div>';
				errorMessage += '<div class="message">We didn\'t recognize the username or password you entered. Please try again.</div>';
				//console.log(errorMessage);
				$('#login-form').addClass('error');
				$('#login-form .dialog-message').html(errorMessage);
			}
		},'json')

		e.preventDefault();
	});

	// password reminder
	$('#login-form').on('click', '#remind', function(e) {
		$('#login-form').toggleClass('error', false);
		e.preventDefault();
		var message = '<input id="email" name="email" type="text" placeholder="Please enter your email address..."/>';
		message += '<div class="dialog-message email"></div>';
		message += '<input type="submit" id="reminder-submit" name="reminder-submit" value="Send me reset instructions" />';
		$('#login-form .login-title').html('<div class="remind-title">Forgot your password?</div>');
		$('#login-form form').html(message);
		$('#login-form .reminder').html('');
	});

	$('#login-form').on('click', '#reminder-submit', function(e) {
		e.preventDefault();
		$.post('?page=ajax&action=reset-password', {email: $('#login-form.modal [name=email]').val()},function(ret) {
			if (ret.success) {
				var message = '<div class="note-title">Password reset notification</div>';
				message += '<div class="note-text"><span class="heading">Dear student,</span>';
				message += 'Your password has been successfully reset. Check<br />';
				message += 'your mail for your new login details.</div>';
				$('#login-form .content-wrapper').html(message);
			} else {
				var message = '<div class="error">';
				message += '<div class="icon"></div>';
				message += '<div class="message">' + ret.message + '</div>';
				$('#login-form').toggleClass('error', true);
				$('#login-form .dialog-message').html(message);
			}

		},'json')
	});

	$('.refer').on('click', 'li', function(e) {
			var id = $(this).attr('id');
			var message = '<form method="post" action="">';
			if (id == 'invite-email') {
				message += '<div class="heading-refer email">Invite your email contacts</div>';
				message += '<div class="comment">or enter email addresses here:</div>';
				message += '<input type="text" name="email" placeholder="Add names or emails" />';
				message += '<div class="submit-btn"><input type="submit" name="email-submit" class="email" value="Send invites" /></div>';
			} else if (id == 'invite-fb') {
				message += '<div class="heading-refer fbtw">Invite friends from Facebook</div>';
				message += '<textarea placeholder="Lorem ipsem..."></textarea>';
				message += '<div class="submit-btn"><input type="submit" name="fb-submit" class="fb" value="" /></div>';
			} else {// invite-tw
				message += '<div class="heading-refer fbtw">Invite friends from Twitter</div>';
				message += '<textarea placeholder="Lorem ipsem..."></textarea>';
				message += '<div class="submit-btn"><input type="submit" name="tw-submit" class="tw" value="" /></div>';
			}
			message += '</form>';
			$('#invite-form .content-wrapper').html(message);
			});

	$('#contact-form').on('submit', function(e) {
		e.preventDefault();

		var $data = $('#contact-form').serialize();
		var $token = $('meta[name="csrf-token"]').attr('content');
		$.ajax({
			type:'POST',
			url : $(this).attr('action'),
			data: $data,
			dataType:'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', $token);
			},
			success: function(data) {
				if (data.success == true) {
					var message = '<p class="success">You mail was successfully sent</p>';
					$('#contact-form').html(message).attr('style', 'font-size: 18px; text-align: center;');
				} else {
					//console.log(data);
					var message = '';
					for (var error in data.errors) {
						$('[name='+error+']').addClass('error');
						//$('#' + error).addClass('error');
						message += '<p class="error">' + data.errors[error] + '</p>';
					}
					$('#form-msg').html('<p class="error">Please correct errors below').addClass('error');
				}
			},
			fail: function(data) {
				//
			}
		});
	});

	// eligibility section
	if ($('.eligibility-section').length) {
		var timeoutID = setTimeout(function() {$('.dob').popover('show')}, 1000);
		$('[name=dob]').inputmask("mm/dd/yyyy",{ "placeholder": "MM/DD/YYYY", showMaskOnHover: false });
		$( "#checkChances" ).on( "click", function(e) {
			e.preventDefault();

			var $school_level = $('[name=current_school_level]').val();
			var $field_of_study = $('[name=field_of_study_select]').val();
			var $gender = $('[name=gender]').val();
			var $dob    = $('[name=dob_mm]').val() + '/' + $('[name=dob_dd]').val() + '/' + $('[name=dob_yyyy]').val();
			var $token  = $('meta[name="csrf-token"]').attr('content');
			var $action = $('#form-eligibility').attr('action');

			$.ajax({
				type : 'POST',
				url : $action,
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: {dob: $dob, gender: $gender, current_school_level: $school_level, field_of_study_select: $field_of_study},
				success: function(ret) {
					if (ret.success) {
						location.href = ret.redirect;
					} else {
						//console.log(ret);
						$('.form-element div.error').html('')
						$.each(ret.errors, function(k,v) {
							$('[name=' + k +']').closest('.form-element').find('div.error').html(v)
						})
					}
					//console.log(ret)
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
		});
	}

	// register step 1
	if ($('.box-register-form.step1').length) {
		$('[name=phone]').inputmask("(999) 999 - 9999",{ "placeholder": "(   )     -     ", showMaskOnHover: false });
		var timeoutID = setTimeout(function() {$('[name=first_name]').popover('show')}, 1000);

		$('[name=first_name],[name=last_name],[name=email],[name=phone]').focus(function(e) {
			$('[name=first_name]').popover('hide')
			$('[name=last_name]').popover('hide')
			$('[name=email]').popover('hide')
			$('[name=phone]').popover('hide')
		})
		$('[name=first_name]').focus();
		$('form[name=form-register-step1]').submit(function(e) {
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

	// register step 2
	if ($('.box-register-form.step2').length) {
		var timeoutID = setTimeout(function() {$('#ed-level').popover('show')}, 1000);
		$('[name=dob]').inputmask("mm/dd/yyyy",{ "placeholder": "MM/DD/YYYY", showMaskOnHover: false });
		// show hide corresponding college fields

		$('#ed-level').change(function() {
			showHideCollegeFields();
		})
		showHideCollegeFields();

		$('form[name=form-register-step2]').submit(function(e) {
			e.preventDefault();

			var $token = $('meta[name="csrf-token"]').attr('content');
			var $data = $(this).serialize();

			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: $data,
				success: function(ret) {
					if (ret.success) {
						location.href = ret.redirect;
					} else {
						$('input[type=text]').removeClass('error')
						$('bootstrap-select').removeClass('error')
						$('.form-element div.error').html('')
						$.each(ret.errors, function(k,v) {
							$('[name=' + k +']').closest('.form-element').find('div.error').html(v)
						})
					}
					//console.log(ret);
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
		});
	}

	if ($('.box-register-form.step3').length) {
		$('#greet-window').modal('show');
		$('form[name=form-register-step3]').submit(function(e) {
			e.preventDefault();

			var $token = $('meta[name="csrf-token"]').attr('content');
			var $data = $(this).serialize();

			$.ajax({
				type: 'POST',
				url: $(this).attr('action'),
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: $data,
				success: function(ret) {
					if (ret.success) {
						location.href = ret.redirect;
					} else {
						$('input[type=text]').removeClass('error')
						$('bootstrap-select').removeClass('error')
						$('.form-element div.error').html('')
						$.each(ret.errors, function(k,v) {
							$('[name=' + k +']').addClass('error').closest('.form-element').find('div.error').html(v)
						})
					}
					//console.log(ret);
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
		})

	}
	// my account
	if ($('.box-register-form.step5').length) {
		//var hideIt = setTimeout(function() {$('#thank-you').modal('hide');}, 10000);
		$('[name=dob]').inputmask("mm/dd/yyyy",{ "placeholder": "MM/DD/YYYY", showMaskOnHover: false });

		$('[name=phone]').inputmask("(999) 999 - 9999",{ "placeholder": "(   )     -     ", showMaskOnHover: false });


		$('.tab-pane form').submit(function() {
			$.post($(this).attr('action'), $(this).serialize(), function(ret) {
				if (ret.success) {
					$('#thank-you').modal('show');
					$('#thank-you').on('hidden.bs.modal', function (e) {
						location.reload(); // need to reload to show new profile completition percentage
					})

				} else {
					$('input[type=text]').removeClass('error')
					$('bootstrap-select').removeClass('error')
					$('.form-element div.error').html('')
					$.each(ret.errors, function(k,v) {
						$('[name=' + k +']').addClass('error').closest('.form-element').find('div.error').html(v)
					})
				}
			},'json')
			return false;
		})
		/*
		$('form[name=form-register-review]').submit(function() {
			$.post($(this).attr('action'), $(this).serialize(), function(ret) {
				if (ret.success)
					location.href = ret.redirect
				else {
					$('input[type=text]').removeClass('error')
					$('bootstrap-select').removeClass('error')
					$('.form-element div.error').html('')
					$.each(ret.errors, function(k,v) {
						$('[name=' + k +']').addClass('error').closest('.form-element').find('div.error').html(v)
					})
				}
			},'json')
			return false;
		});
		*/
		$('.save-changes').click(function(e) {
			e.preventDefault();

			var $token = $('meta[name="csrf-token"]').attr('content');
			var $form = $(this).closest('form');
			var $data = $form.serialize();

			$.ajax({
				type: 'POST',
				url: $form.attr('action'),
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: $data,
				success: function(ret) {
					if (ret.success) {
						//console.log(ret);
						$('input[type=text]').removeClass('error')
						$('bootstrap-select').removeClass('error')
						$('.form-element div.error').html('')
					} else {
						$('input[type=text]').removeClass('error')
						$('bootstrap-select').removeClass('error')
						$('.form-element div.error').html('')
						$.each(ret.errors, function(k,v) {
							$('[name=' + k +']').addClass('error').closest('.form-element').find('div.error').html(v)
						})
					}
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
			//$(this).closest('form').submit()
			//return false;
		})

		$('.info-toggle').on('click',function() {
			$('#goals-info').css({border:'none'});
			return true;
		});
		$('[name=current_school_level]').change(function() {
			showHideCollegeFields();
		});
		$('#myTab a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		});
		showHideCollegeFields();

	}
})






