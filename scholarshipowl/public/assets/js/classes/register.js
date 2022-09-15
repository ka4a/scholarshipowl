/*
 * EligibilityButton JS Class
 * By Branislav Jovanovic
 */
var EligibilityButton = AjaxButton.extend({
	onBeforeSend: function(request) {
		$(".help-block").remove();
		$(".has-error").removeClass("has-error");
	},

	onSuccess: function(response) {
		var mascot = $(".mascot");

		var alertFill = "<div style='z-index:666' id='alertFillContainer' \
			class='alert- alert-dismissible- fade in' \
			data-alertid='errorNotification' role='alert++'> \
			<div id='alertFill' class='center-block'> \
			<button type='button' class='close-alert' data-dismiss='close-alert' aria-label='Close Alert'> \
			<span aria-hidden='true'>&times;</span> \
			</button> \
			You must <strong>fill all required<br> fields</strong> in order to apply. \
			</div></div>";


		if(response.status == "error") {
			$.each(response.data, function(k,v) {
				var $errorElement = "<small class='help-block col-sm-offset-3 col-sm-12 " + k + "-error'>" + v + "</small>";
				$("[name=" + k + "]").parent().addClass( "has-error" );
				$("[name=" + k + "]").parent().parent().append($errorElement);
			});

			return mascot.after(alertFill);

		}
		else if(response.status == "redirect") {
			window.location = response.data;
		}
	},

	onComplete: function() {
		$("#msgNotification").delay(6000).fadeOut(500);
		$("html, body").animate({ scrollTop: 0 }, "slow");
	}
});

/*
 * RegisterButton JS Class
 * By Branislav Jovanovic
 */
var RegisterButton = AjaxButton.extend({
	onBeforeSend: function(request, s) {

    var properties = {};

    $(".coregBox").each(function() {
        var $name = $(this).attr('name').replace('agree_', '');

        if ($(this).is(":checked")) properties[$name] = true;
    });

		// TODO represent in new component
    window.SOWLMixpanelTrack('RegisterButton click', properties);

    var mustAggre = '<small class="mustAggre has-error">';
		mustAggre += 'You must agree with the terms and conditions before you continue!';
		mustAggre += '</small>';

    $(".mustAggre").remove();

    if (!$('[name=agree_terms]').is(':checked')) {
      $(".checkboxes").append(mustAggre);
      return false;
    }

		$(".error-tooltip").remove();
		$(".tooltip").remove();
		$(".mustAggre").remove();
		$(".has-error").removeClass("has-error");

    var $phoneInput = $('[data-input-id="Phone"]');

		if ($phoneInput.length && $phoneInput.get(0).__phone_country) {
	    s.data += '&phone=' + encodeURIComponent($phoneInput.intlTelInput('getNumber'));
	    s.data += '&country_code=' + ($phoneInput.intlTelInput('getSelectedCountryData').iso2 || '').toUpperCase();
		}
	},

  onError: function(response) {
    var errors = response,
      hideTooltips = function() {
        $(this)
          .closest('.form-group')
          .removeClass('has-error')
          .find('.error-tooltip .tooltip-trigger').tooltip('destroy').end()
          .find('.error-tooltip').remove();
      };

		if (response.responseText) {
      try {
        errors = $.parseJSON(response.responseText);
        if(errors.hasOwnProperty('errors')) {
          errors = errors.errors;

          for(var key in errors) {
            if(typeof errors[key] === 'string') return;

            errors[key] = errors[key][0];
          }
        }
      } catch(err) {
        throw err;
      }
		}

    errorLoader();
    $.each(errors, function(korig,v) {
      k = korig.indexOf('.') !== -1 ? korig.substring(0, korig.indexOf('.')) : korig;
      var $input = $('[data-validation='+k+']'),
        $formGroup = $input.closest('.form-group'),
        errorMessage = '<div class="error-tooltip">'
          + '<div class="register-tooltip tooltip-trigger" href="#" data-toggle="tooltip" data-original-title="'
          + v + '" data-animation="true" data-placement="top" class="center-block">' + v + '</div>' +
          '</div>';

      $formGroup.addClass('has-error').prepend(errorMessage);
      $('.error-tooltip .tooltip-trigger').tooltip('show');
    });

    $('input[data-validation]')
      .one('click', hideTooltips)
      .one('select2:open', hideTooltips);
  },

  onSuccess: function(response) {
		if (response.status == "redirect") {
      // submit fb checkbox
      if(window.hasOwnProperty('MC') && typeof MC === 'function') {
        MC.getWidget(1307366).submit();
      }

      preventReLoad();
			window.location = response.data;
		} else {
			this.onError(response.data);
		}
	}
});

var RegisterButtonCTA = Element.extend({
    onBeforeAction: function() { return true; },
    onBeforeSend: function(request) {},
    onSuccess: function(response) {},
    onError: function(xhr, ajaxOptions, thrownError) {},
    onComplete: function() {},

    _init: function(element) {
        this._super(element);
        var caller = this;

        element.click(function(e) {
            e.preventDefault();

            var $performAction = caller.onBeforeAction.apply(caller, arguments);
            if($performAction == false) {
                return false;
            }

            var $token = $("input[name=_token]").val();
            if(typeof $token === "undefined") {
                throw "Token Not Defined";
            }

            var $form = $("#registerForm1");
            var $data = $form.serialize();
            var $action = $form.attr("action");
            var $type = "post";
            var $dataType = "json";

            var $ajax = new Ajax($action, $type, $dataType, $data);

            $ajax.onBeforeSend = function(request) {
                request.setRequestHeader("X-CSRF-Token", $token);
                caller.onBeforeSend.apply(caller, arguments);

                var properties = {};

                $(".coregBox").each(function() {
                    var $name = $(this).attr('name').replace('agree_', '');

                    if ($(this).is(":checked")) properties[$name] = true;
                });

                window.SOWLMixpanelTrack('RegisterButton click', properties);

                $(".error-tooltip").remove();
                $(".tooltip").remove();
                $(".mustAggre").remove();
                $(".has-error").removeClass("has-error");
            };

            $ajax.onSuccess = function(response) {
                caller.onSuccess.apply(caller, arguments);

                $('[name=agree_terms]').click(function() {
                    if($(this).is(':checked')) {
                        $(".mustAggre").remove();
                    }
                });

                if(response.status == "error") {
                    $('html, body').stop().animate({
                        scrollTop: $("#registerForm1").offset().top - 90
                    }, 600);
                }
                else if(response.status == "redirect") {
                    preventReLoad();
                    window.location = response.data;
                }
            };

            $ajax.onError = function(xhr, ajaxOptions, thrownError) {
                caller.onError.apply(caller, arguments);
            };

            $ajax.onComplete = function() {
                caller.onComplete.apply(caller, arguments);
            };

            $ajax.sendRequest();
        });
    }
});


/*
 * Register2Button JS Class
 * By Branislav Jovanovic
 */
var Register2Button = AjaxButton.extend({
	onBeforeSend: function(request) {
		$(this).find('.btn__loader').show();

		$(".has-error").removeClass("has-error");
		$(".errorState").remove();
        window.SOWLMixpanelTrack('Register2Button click');
	},

	onSuccess: function(response) {
		if(response.status == "error") {
			$(this).find('.btn__loader').hide();

      errorLoader();

      $.each(response.data, function(k,v) {
        selector = $('[name="'+k+'"]').closest('.form-group').addClass('has-error');
        if($.inArray(k, ['graduation_year','graduation_month']) === -1){
            selector.append("<div class='errorState clearfix'>" + v + "</div>");
        }
			});

      if(response.data["date_of_birth"]) {
        selector = $("#date_of_birth").find(".form-group").addClass("has-error");
        selector.append("<div class='errorState clearfix'>" + response.data["date_of_birth"] + "</div>");
      }

			$("html, body").animate({ scrollTop: $(".form-group.has-error").first().offset().top - 100 }, 1000);
		}
		else if(response.status == "redirect") {
			preventReLoad();
			window.location = response.data;
		}
	},
	orError: function(response) {
		$(this).find('.btn__loader').hide();
	}
});


/*
 * Register3Button JS Class
 * By Branislav Jovanovic
 */
/*
 * Register3Button JS Class
 * By Branislav Jovanovic
 */
function addError(fieldName, errorMessage) {
  var inputParent = $('[name*="' + fieldName + '"]').closest('.form-group');
  inputParent.addClass('has-error').append("<div class='errorState clearfix'>" + errorMessage + "</div>");
}

function removeError(fieldName) {
  var inputParent = $('[name*="' + fieldName + '"]').closest('.form-group');
  inputParent.removeClass('has-error').find('.errorState').remove();
}

function removeAllErrors() {
  $(".has-error").removeClass("has-error");
  $(".errorState").remove();
}

var Register3Button = AjaxButton.extend({
    onBeforeAction: function(value) {
      return validateFormReg3();
    },

    onBeforeSend: function(request) {
      $(this).find('.btn__loader').show();

      removeAllErrors();

      var properties = {};

      $(".coregBox").each(function() {
            var $name = $(this).data("name");
         if ($(this).is(":checked")) properties[$name] = true;
       });

        if($('#berecruited-yes').prop('checked')) {
            properties['berecruited'] = true;
        }

      window.SOWLMixpanelTrack('Register3Button click', properties);

			if (typeof SOWLRegister3Form !== 'undefined') {
        SOWLRegister3Form.valid()
          .then(function(result) {
            if (!result) {
              request.abort();
            }
          })
          .catch(function() {
            request.abort();
          })
			}
    },

    onSuccess: function(response) {
        if(response.status == "error") {
					$(this).find('.btn__loader').hide();

            var inputSet = {};

            errorLoader();
            $.each(response.data, function(k,v) {
                addError(k, v);
            });

            if(inputSet['state_id'] && inputSet['state_id'].length) {
              var stateDropDown = inputSet['state_id'].find('.selectpicker');

              stateDropDown.on('change', function(ev) {
                if(ev.target.value) {
                  inputSet['state_id'].removeClass('has-error').find('.errorState').remove();
                  stateDropDown.off('change');
                }
              })
            }

            if(inputSet['state_name'] && inputSet['state_name'].length) {
              inputSet['state_name'].find('[name*="state_name"]').on('focus', function() {
                inputSet['state_name'].find('.errorState').remove();
                inputSet['state_name'].off('focus');
              });
            }

						if (typeof SOWLRegister3Form !== 'undefined') {
							SOWLRegister3Form.setFormErrors(response.data);
						}
        } else if(response.status == "redirect") {
            preventReLoad();
            window.location = response.data;
        }
    },

		onError: function(response) {
			$(this).find('.btn__loader').hide();
		}
});

$(function() {
  function initValidation() {
    if(!window['registerForm3']) return;

    function isEmptyString(value) {
      if(typeof value !== 'string')
        throw Error('Please set string value');

      return !value.length;
    }

    function isStringLengthLessThan(number) {
      if(!number || typeof number !== 'number')
        throw Error('Please provide correct value');

      return function(value) {
        if(typeof value !== 'string')
          throw Error('Please set string value');

        return value.length < number;
      }
    }

    function leave5Numbers(str) {
      return str.replace(/\D+/g, '').substr(0, 5);
    }

    function leaveAlphaNum(str) {
      return str.replace(/[^a-z0-9]/gi,'').substr(0, 31);
    }

    var isValid = true;

    var validationRules = {
      address: {
        rules: [{
          rule: isEmptyString,
          error: 'Please enter your address!'
        }]
      },
      zip: {
        rules: [{
          rule: isEmptyString,
          error: 'Please enter zip code'
        }, {
          rule: function(value) {
            return isStringLengthLessThan(5)(value);
          },
          error: 'Please enter 5 digit zip code'
        }]
      },
      city: {
        rules: [{
          rule: isEmptyString,
          error: 'Please enter your city!'
        }]
      },
      state_id: {
        rules: [{
          rule: isEmptyString,
          error: 'Please select a state!'
        }]
      },
      state_name: {
        rules: [{
          rule: isEmptyString,
          error: 'Please enter a State / Province / Region!'
        }]
      },
      password: {
        rules: [{
          rule: function(value) {
            return !isEmptyString(value)
              && isStringLengthLessThan(6)(value);
          },
          error: 'Password too short. Minimum 6 characters!'
        }]
      },
      confirmPassword: {
        rules: [{
          rule: function(value) {
            return !isEmptyString(value)
              && isStringLengthLessThan(6)(value);
          },
          error: 'Password too short. Minimum 6 characters!'
        }]
      }
    };

    var form = window['registerForm3'],
        button = window['btnRegister3'],
        inputFormat = SOWLStorage.settings.uc === 'US'
          ? leave5Numbers
          : leaveAlphaNum;

    for(var key in validationRules) {
      if((key === 'state_id' && form['state_name'])) {
        key = 'state_name';
      }

      if((key === 'state_name' && form['state_id'])) {
        key = 'state_id';
      }

      if(!form[key]) {
        throw Error('Please provide ' + key + ' input data');
      }

      validationRules[key]['elem'] = form[key];
      validationRules[key]['elem'].addEventListener('focus', function() {
        removeError(this.name);
      })
    }

    validationRules['zip']['elem'].addEventListener('input', function(ev) {
      ev.target.value = inputFormat(ev.target.value);
    })


    validationRules[ form['state_name'] ? 'state_name' : 'state_id']['elem'].addEventListener('change', function () {
      removeError(this.name);
    })


    form.addEventListener('submit', function(ev) {
      this.preventDefault();
      return false;
    })

    return function(ev) {
      isValid = true;
      removeAllErrors();

      for(var key in validationRules) {
        if((key === 'state_id' && form['state_name'])) {
          continue;
        }

        if((key === 'state_name' && form['state_id'])) {
          continue;
        }

        var field = validationRules[key];

        for(var i = 0; i < field.rules.length; i += 1) {
          var validation = field.rules[i];

          if(validation.rule(field.elem.value)) {
            addError(key, validation.error);
            isValid = isValid && false;
            break;
          }
        }
      }

      return isValid;
    }
  }

  window.validateFormReg3 = initValidation();
})

document.addEventListener('DOMContentLoaded', function() {
	(function($){
    var $container = $('#study-country-container'),
      $list = $container.find('.study-country__list'),
      $checkbox = $container.find('input[type=checkbox][name=want_to_study]'),
			$phoneInput = $('[data-input-id="Phone"]'),
      country = SOWLConfig.get('uc', 'CA') !== 'XX' ? SOWLConfig.get('uc', 'CA') : 'US',
			studyCountries = $list.find('select');

		if ($phoneInput.attr('data-mask') === 'true') {
			$phoneInput.inputmask("(999) 999 - 9999", {
				"placeholder": "(   )     -     ",
				showMaskOnHover: false
			});
		}


    if (!$phoneInput.length || !$container.length) {
      return;
    }

    function configureInputMask(placeholder) {
      $phoneInput.inputmask('remove');
      $phoneInput.inputmask(placeholder.replace(/[0-9]/g, "9"), {
        showMaskOnHover: false,
      });
    }

		$phoneInput.get(0).__phone_country = true;

    // $phoneInput.inputmask("Regex");
		$phoneInput.inputmask({mask: '+9999999999999999999', placeholder: ""});

    $phoneInput.intlTelInput({
      utilsScript: 'assets/js/utils.js',
      nationalMode: false,
      initialCountry: country,
      placeholderNumberType: 'PERSONAL_NUMBER',
			autoPlaceholder: 'aggressive',
      preferredCountries: ['us', 'ca'],
      // customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
			//
			// 	if(selectedCountryData.iso2 === 'us') {
			// 		if(selectedCountryData.iso2 !== selectedCountry) {
			// 			configureInputMask(selectedCountryPlaceholder);
			// 			selectedCountry = selectedCountryData.iso2;
			// 		}
			// 	} else {
			// 		$phoneInput.inputmask('remove');
			// 		selectedCountry = selectedCountryData.iso2;
			// 	}
			//
      // }
    });

    $("#phone").on("countrychange", function(e, countryData) {
      if (countryData.iso2 !== 'us') {
        $container.slideDown();
      } else {
        $container.slideUp();
        $checkbox.prop('checked', true);
      }
    });

		studyCountries
      .select2({
        minimumInputLength: 1
      })
			.on('select2:opening', function() {
				if ($list.hasClass('has-error')) {
					$(this)
	          .closest('.form-group')
	          .removeClass('has-error')
	          .find('.error-tooltip .tooltip-trigger').tooltip('destroy').end()
	          .find('.error-tooltip').remove();
				}
			});

    $checkbox
      .on('change', function(event) {
        if (!event.target.checked) {
          $list.slideDown();
        } else {
          $list.slideUp();
        }
      });

		setTimeout(function() {
			if (country !== 'US') {
				$container.slideDown();
			}
		}, 1000);

  })(jQuery);
});
