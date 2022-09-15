$(document).ready(function(){

	$(".tooltip-register a").tooltip('show');
	$("[data-toggle=tooltip]").tooltip('show');

	$('#birth-date').click(function(){
	  $(this).closest('.form-group').tooltip('destroy', {
	  	animated: 'fade',
	  	delay: {show: 100, hide: 5000}
	  });
	});

	$('#greet-window').modal('show');

	$('select').selectpicker({
    	size: false
    });

	$('ul.selectpicker').mCustomScrollbar({
		theme:"inset-dark",
		contentTouchScroll: true,
		scrollButtons:{
			enable:true
		}
    });

	$('ul.selectpicker li:first-child').addClass("hidden");

	$(".dropdown-menu, html").on("mouseup pointerup",function(e){ $(".dropdown-menu .mCSB_scrollTools").removeClass("mCSB_scrollTools_onDrag"); }).on("click",function(e){ if($(e.target).parents(".mCSB_scrollTools").length || $(".dropdown-menu .mCSB_scrollTools").hasClass("mCSB_scrollTools_onDrag")){ e.stopPropagation(); } });

	$('.firstName').focus(function(event) {
		$('.tooltip-register a').tooltip('destroy').fadeOut('fast');
		$(this).removeClass('highlighted');
	});	




	
	$(".navbar-fixed-top").autoHidingNavbar();
	
	$(".checkAllWrapper").click(function(event) {
		$("#selectAll").toggleClass('hidden');
		$("#selectNone").toggleClass('hidden');
	});
	
	// ---------------------------------------------
	// Eligibility Form Start
	// ---------------------------------------------
	
	// Birthday Input Mask
	if ($('input[name=dob_mm]').length) {
		$('input[name=dob_yyyy]').inputmask('9999');
		$('input[name=dob_dd]').inputmask('99');
		$('input[name=dob_mm]').inputmask('99');		
	}
	
	// Eligibility Form JS Validator
	$('#registerForm')
		.find('[name="gender"]')
			.selectpicker()
			.change(function(e) {
				// revalidate option when it is changed
				$('#registerForm').bootstrapValidator('revalidateField', 'gender');
			})
			.end()

		.find('[name="current_school_level"]')
			.selectpicker()
			.change(function(e) {
				// revalidate option when it is changed
				$('#registerForm').bootstrapValidator('revalidateField', 'current_school_level');
			})
			.end()

		.find('[name="field_of_study"]')
			.selectpicker()
			.change(function(e) {
				// revalidate option when it is changed
				$('#registerForm').bootstrapValidator('revalidateField', 'field_of_study');
			})
			.end()
		.bootstrapValidator({
            //excluded: ':disabled',
	        feedbackIcons: {
	            valid: 'glyphicon glyphicon-ok',
	            invalid: 'glyphicon glyphicon-remove',
	            validating: 'glyphicon glyphicon-refresh'
    	    },
    	    fields: {
	        	gender: {
	            	group: '.form-group',
	            	validators: {
                		notEmpty: {
                    		message: 'Please select one option'
                		}
            		}
	            },
	            current_school_level: {
	            	group: '.form-group',
	            	validators: {
                		notEmpty: {
                    		message: 'Please select one option'
                		}
            		}
	            },
	            field_of_study_select: {
	            	group: '.form-group',
	            	validators: {
                		notEmpty: {
                    		message: 'Please select one option'
                		}
            		}
	            }
       		}

		})
		.on('error.validator.bv', function() {
			$('#alertFillContainer').removeClass('hidden');
	});


	// Eligibility Form Server Side Validator
	if ($('#registerForm').length) {
		$("#registerForm").on('submit',function(e) {
		    e.preventDefault();
		});

		$("#sign_up_now_btn").on( "click", function(e) {
			e.preventDefault();
			var $action = "ajax/eligibility";

			var $school_level = $('[name=current_school_level]').val();
			var $field_of_study = $('[name=field_of_study_select]').val();
			var $gender = $('[name=gender]').val();
			var $dob_mm = $('[name=dob_mm]').val();
			var $dob_dd = $('[name=dob_dd]').val();
			var $dob_yyyy = $('[name=dob_yyyy]').val();
			var $ref = $('[name=ref]').val();

			//var $dob    = $('[name=dob_mm]').val() + '/' + $('[name=dob_dd]').val() + '/' + $('[name=dob_yyyy]').val();
			var $token 	= $("input[name=_token]").val();

			$.ajax({
				type : 'POST',
				url : $action,
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: {
					dob_mm: $dob_mm, 
					dob_dd: $dob_dd, 
					dob_yyyy: $dob_yyyy, 
					gender: $gender, 
					current_school_level: $school_level, 
					field_of_study_select: $field_of_study,
					ref: $ref
				},
				success: function(ret) {
					if (ret.success) {
						location.href = ret.redirect;
					} 
					else {
						//console.log(ret);
						$('#alertFillContainer').removeClass('hidden');
						$(".help-block").html("");
						$.each(ret.errors, function(k,v) {
							var errorElement = ".help-block[data-bv-for=" + k + "]";
							
							$(errorElement).html(v)
							$(errorElement).show()
						});
						$(".eligibility .help-block").closest('.form-group').addClass('has-error');

						$(".eligibility .help-block").closest('.form-group').click(function() {
							$(this).select().removeClass('has-error');
							$(this).select(function() {
								$(this).find('.help-block').remove();
							});
						});
						$('.tooltip-inner').css({
							backgroundColor: '#ff6a6a'
						});
						$('.tooltip-arrow').css({
							borderTopColor: '#ff6a6a'
						});
					}
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
		});
	}
	
	// ---------------------------------------------
	// Eligibility Form End
	// ---------------------------------------------
	
	
	
	// ---------------------------------------------
	// Register 1 Form Start
	// ---------------------------------------------
	
	// Phone Input Mask
	if($("#registerForm1").length) {
		$("[name=phone]").inputmask("(999) 999 - 9999", { "placeholder": "(   )     -     ", showMaskOnHover: false });
	}
	
	    
	    // Register 1 Form Server Side Validation
	 	$("#registerForm1").on('submit',function(e) {
	    	e.preventDefault();
		});
	    	
	   	$("#btnRegister1").on("click", function(e) {
			e.preventDefault();
			

			var mustAggre = '<small class="mustAggre has-error">';
			mustAggre += 'You must agree with the terms and conditions before you continue!';
			mustAggre += '</small>';

			if (!$('[name=agree_terms]').is(':checked')) {
				$(".checkboxes").append(mustAggre);
				$(".checkboxes > .form-group").first().addClass('has-error');
				return false;
			}

			$('[name=agree_terms]').click(function() {
				if($(this).is(':checked')) { 
					$(".checkboxes > .form-group").first().removeClass('has-error');
					$(".mustAggre").remove();
				}
			});
				
			var $token 	= $("input[name=_token]").val();
			var $data = $(this).serialize();
			var $action = "ajax/register-step-one";
			
			var $firstName = $("input[name=first_name]").val();
			var $lastName = $("input[name=last_name]").val();
			var $email = $("input[name=email]").val();
			var $phone = $("input[name=phone]").val();
			var $ref = $("input[name=ref]").val();
			
			$.ajax({
				type : 'POST',
				url : $action,
				dataType: 'JSON',
				beforeSend: function(request) {
					request.setRequestHeader('X-CSRF-Token', $token);
				},
				data: {
					first_name: $firstName, 
					last_name: $lastName, 
					email: $email, 
					phone: $phone,
					ref: $ref
				},
				success: function(ret) {
					if (ret.success) {
						location.href = ret.redirect;
					}
					else {
						//alert(ret.error);

					var errorMessage = '<div class="error-tooltip">';
					errorMessage += '<a class="register-tooltip" href="#" data-toggle="tooltip" data-original-title="';
					errorMessage += ret.error;
					errorMessage += '" data-animation="true" data-placement="top" ';
					errorMessage += 'class="center-block">';
					errorMessage += ret.error;
					errorMessage += '</a></div>';


						$('input[name="'+ret.field+'"]').addClass('xxx');

						$(".xxx").closest('.form-group').addClass('has-error').prepend(errorMessage);
						$('.error-tooltip a').tooltip('show');

						$('.tooltip-register a').tooltip('destroy');

						$('input[name="'+ret.field+'"]').addClass('xxx');

						$(".xxx").closest('.form-group').addClass('has-error').prepend(errorMessage);

						$('.error-tooltip a').tooltip('show');

						$(".xxx").focus(function() {
							$(".error-tooltip a").tooltip('destroy');
							$(".error-tooltip").remove();
							$(this).closest('.form-group').removeClass('has-error');
							$(this).removeClass('xxx');
						});


						$('[name=agree_terms]').click(function() {
							if($(this).is(':checked')) { 
								$(".checkboxes > .form-group").first().removeClass('has-error');
								$(".mustAggre").remove();
							}
						});

					}
				},
				fail: function(ret) {
					//console.log(ret);
				}
			});
		})
	$(".checkboxes i.form-control-feedback").remove();
	
	
	// ---------------------------------------------
	// Register 1 Form End
	// ---------------------------------------------
	
	
	
	// ---------------------------------------------
	// Register 2 Form End
	// ---------------------------------------------
    	
    	// Register 2 Form Server Side Validation
	 	$("#registerForm2").on('submit',function(e) {
	    	e.preventDefault();
		});
	 	
	 	if ($('#registerForm2').length) {
    		$('#btnRegister2').on("click", function(e) {
    			e.preventDefault();

    			var $token 	= $("input[name=_token]").val();
    			var $data = $('#registerForm2').serialize();
    			var $action = "ajax/register-step-two";
    			
    			$.ajax({
    				type: 'POST',
    				url: $action,
    				dataType: 'JSON',
    				beforeSend: function(request) {
    					$(".has-error").removeClass("has-error");
    					$(".errorState").remove();
    					request.setRequestHeader('X-CSRF-Token', $token);
    				},
    				data: $data,
    				success: function(ret) {
    					if (ret.success) {
    						location.href = ret.redirect;
    					}
    					else {

    						$.each(ret.errors, function(key, value) {
    							$('[name="'+key+'"]').closest('.form-group').addClass('has-error').append("<div class='errorState clearfix'>" + value + "</div>");
    						});

    					}
    				},
    				fail: function(ret) {
    					//console.log(ret);
    				}
    			});
    		});
    	}
    	
    
    // ---------------------------------------------
	// Register 2 Form End
	// ---------------------------------------------
	
    
    
	// ---------------------------------------------
	// Register 3 Form Starts
	// ---------------------------------------------
	
	// Register 3 Form Server Side Validation
	 	
	 
	 	
	 	$("#registerForm3").on('submit', function(e) {
	    	e.preventDefault();
		});
 		
 		if ($('#registerForm3').length) {
	 		$('#btnRegister3').on("click", function(e) {
    			e.preventDefault();

    			var $token 	= $("input[name=_token]").val();
    			var $data = $('#registerForm3').serialize();
    			var $action = "ajax/register-step-three";
    			
    			$.ajax({
    				type: 'POST',
    				url: $action,
    				dataType: 'JSON',
    				beforeSend: function(request) {
    					request.setRequestHeader('X-CSRF-Token', $token);
    				},
    				data: $data,
    				success: function(ret) {
    					if (ret.success) {
    						location.href = ret.redirect;
    					} 
    					else {
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
	 	
	// ---------------------------------------------
	// Register 3 Form Ends
	// ---------------------------------------------
		
	
	 
	$('#loginModal').bootstrapValidator({
        message: 'This value is not valid',
        excluded: [':disabled'],
            excluded: ':disabled',
	        fields: {        
		        username: {
		        	group: '.form-group',
		        	validators: {
		                notEmpty: {
		                    message: 'The password is required'
		                },
		        		emailAddress: {
		            		message: 'The value is not a valid email address'
		        		}
		    		}
		        },
		        password: {
					group: '.form-group',
		            validators: {
		                notEmpty: {
		                    message: 'The password is required'
		                }
		            }
		        }
			}
    	})
		.on('shown.bs.modal', function() {
    		$('#loginModal').bootstrapValidator('resetForm', true);
    	});
	
	
	$('#loginModal').on('submit', function(e) {
		e.preventDefault();
		var $token = $('meta[name="csrf-token"]').attr('content');
		var $data = $(this).serialize();
		$.ajax({
			type:'POST',
			url : $(this).attr('action'),
			data: $data,
			dataType:'JSON',
			beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', $token);
			},
			success: function(data) {
				if (data.success) {
					location.href = data.redirect;
				} else {
					console.log('BESE GRESKA');
					console.log(data);
					var errorMessage = '<div class="error">';
					errorMessage += '<div class="icon"></div>';
					errorMessage += '<div class="message">We didn\'t recognize the username or password you entered. Please try again.</div>';
					$('#loginModal').addClass('error');
					$('#loginModal .dialog-message').html(errorMessage);
				}
			},
			fail: function(data) {
				//
			}
		});
	});
	
   
	
	$("#contact-form").on('submit',function(e) {
	    e.preventDefault();
	});
	
	$('.contact-btn').on('click', function(e) {
		e.preventDefault();
		
	    var values = $('#contact-form').serialize();
	    var $token 	= $("input[name=_token]").val();
	    
	    $.ajax({
	      url:'ajax/contact-email',
	      type:'post',
	      data:values,
	      dataType:'json',
	      beforeSend: function(request) {
				request.setRequestHeader('X-CSRF-Token', $token);
			},
			success: function(data) {
	        if (data.success == true) {
	          var message = '<p class="success">Your mail was successfully sent</p>';
	          $('#contact-form').html(message).attr('style', 'font-size: 18px; text-align: center;');
	        } 
	        else {
	          var message = '';
	          $("#content").removeClass("error");
	          $("input[name=name]").removeClass("error");
	          $("input[name=email]").removeClass("error");
	          
	          
	          for (var error in data.errors) {
	        	  $('#' + error).addClass('error');
	        	  $('input[name=' + error + ']').addClass('error');
	        	  
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

});

var countWords = function(textField, countField) {
	this.textField = textField;
	this.countField = countField;
};
countWords.prototype.count = function() {
	var field = $('#' + this.textField).val();
	var matches = field.match(/\S+\s*/g);
	var numWords = matches !== null ? matches.length : 0;
	$('#' + this.countField).text(numWords);
};
