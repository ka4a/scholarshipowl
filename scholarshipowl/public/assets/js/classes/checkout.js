function bindFreemiumHandler(holder) {
  if(!holder) throw Error('Please provide holder!');

  holder.click(function(e) {
      e.preventDefault();

      $.get('/apply-freemium/' + $(this).attr('data-package-id'))
        .done(function(response) {
          window.SOWLMixpanelTrack('FreemiumRedirect');

          $('#payment-popup').modal('hide');

          var data = response.data;

          if (window.triggerGTMSubscriptionEvents) {
            window.triggerGTMSubscriptionEvents(data);
          }

          modalVue.showModal({
            modalName: 'success-basic',
            content: {
              html: data.message
            },
            tracking: {
              hasOffersTransactionId: data.hasOffersTransactionId,
              isFreeTrial: data.isFreeTrial,
              isFreemium: data.isFreemium,
            },
            hooks: {after: function() {
              window.location = data.redirect || "/scholarships";
            }}
          })

          // vueModal.show('Freemium', response.data);

        })
        .fail(function() {
          window.location.reload();
        });
    })
}

var SOWLElementCheckout = Element.extend({

  events: null,

  $checkout: null,

  ccSelectors: {
    submit: 'button.btn-continue',
    firstName: 'input[name=first_name]',
    lastName: 'input[name=last_name]',
    number: '.cc-number input',
    month:  '.expiration-month select',
    year:   '.expiration-year select',
    cvv:    '.cc-cvv input'
  },

  _init: function($element) {
    var that = this;
    this._super($element);
    this.events = new EventEmitter();
    this.$checkout = $element;

    this.$cc        = $element.find('.tab-credit-card');
    this.$form      = $element.find('form');
    this.$tabs      = $element.find('.payment-tabs');
    this.$error     = $element.find('.bt-error');
    this.$freeTrial = $element.find('[name=free_trial]');

    this.initPayPal($element);

    this.$form.on('submit', function(e) {
        var $form = $(this);

        if ($form.data('submitted') === true) {
            e.preventDefault();
            e.stopPropagation();
        } else {
            $form.data('submitted', true);
            that.toggleSubmitButton(true);
        }
    });

    try {
      if (SOWLConfig.getDefaultPaymentMethod() === 3) {
        SOWLBraintreeCheckout(this);
      }

      if (SOWLConfig.getDefaultPaymentMethod() === 4) {
        SOWLRecurlyCheckout(this);
      }

      if (SOWLConfig.getDefaultPaymentMethod() === 5) {
        SOWLBraintreeCheckout(this, true);
        SOWLStripeCheckout(this);
      }

      this.initFreemium();

      this.events.emit('init-checkout');
    } catch (error) {
      console.error(error);
    }
  },

  initCreditCardValidation: function() {
    var that = this, $cc = this.$cc,
      $number = that.$cc.find(this.ccSelectors.number),
      $cvv    = that.$cc.find(this.ccSelectors.cvv);
    $cvv.payment('formatCardCVC');
    $number.payment('formatCardNumber');

    $cc.find('select').selectpicker({
      selectOnTab: true,
      mobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)
    });

    $cc.find('.btn-continue').click(function(e) {
      if (!that.validateCreditCard()) {
        e.preventDefault();
      }
    })
  },

  initPayPal: function() {
      var that = this;

      this.$tabs.find('.tab-paypal').click(function(e) {
          e.preventDefault();
          e.stopPropagation();

        window.SOWLMixpanelTrack('BraintreePaypal click');

          that.events.emit('paypal-click');
      });
  },

  initFreemium: function() {
    var $freemiumButton = $('.GetFreemiumButton');

    bindFreemiumHandler($freemiumButton);
  },

  setBillingAgreement: function(text) {
      this.$form.find('[name=billing-agreement]').val(text);
      this.events.emit('change.billing-agreement');
      return this;
  },

  getBillingAgreement: function() {
      return this.$form.find('[name=billing-agreement]').val();
  },

  setFreeTrial: function(freeTrial) {
      this.$freeTrial.val(freeTrial ? 'true' : 'false');

      var button = this.$this.find('.message');
      button.toggleClass('free-trial', freeTrial);
      button.text(freeTrial ? 'Activate Trial' : 'Continue')
  },

  setPackageId: function(packageId) {
      this.$form.find('[name=package_id]').val(packageId);
  },

  setTrackingParams: function(trackingParams) {
      this.$form.find('[name=tracking_params]').val(trackingParams);
  },

  clearErrorMessage: function() {
      this.$error.text('');
  },

  setErrorMessage: function(message) {
      this.toggleSubmitButton(false);
      this.$form.data('submitted', false);
      this.$error.html(message).parent().effect('shake', {distance: 4});
  },

  toggleSubmitButton: function(toggle) {
      var $button = this.$form.find('button[type=submit]');

      $button.prop('disabled', typeof toggle === 'undefined' ? !$button.prop('disabled') : !!toggle);
  },

  validateCreditCard: function() {
    var validity = {number: true, cvv: true, month: true, year: true},
      $number = this.$cc.find(this.ccSelectors.number),
      $cvv    = this.$cc.find(this.ccSelectors.cvv),
      $month  = this.$cc.find(this.ccSelectors.month),
      $year   = this.$cc.find(this.ccSelectors.year);

    validity.number = $.payment.validateCardNumber($number.val());
    validity.cvv = $.payment.validateCardCVC($cvv.val(), $.payment.cardType($number.val()));

    if (!$.payment.validateCardExpiry($month.val(), $year.val())) {
        if (!$month.val()) {
            validity.month = false;
        }

        if (!$year.val()) {
            validity.year = false;
        }

        if ($month.val() && $year.val()) {
            validity.year = false;
            validity.month = false;
        }
    }

    return this.affectValidityClasses(validity);
  },

  removeValidityClasses: function() {
    this.$cc.find('.invalid-input').removeClass('invalid-input').end()
      .find('.invalid-input-border').remove();
  },

  affectValidityClasses: function(validity) {
    var $cc = this.$cc,
      $month  = this.$cc.find('.expiration-month'),
      $year   = this.$cc.find('.expiration-year'),

      containerSelector = '.bt-input-container',
      invalidClass = 'invalid-input',
      invalidBorderClass = 'invalid-input-border',

      $invalidExpiration = $([]),
      $invalidBorder = $('<div>').addClass(invalidBorderClass),

      makeInvalidField = function($field) {
        $field.effect('shake', {distance: 4});
        $field.closest(containerSelector).addClass(invalidClass)
          .append($invalidBorder.clone());
      };

    this.removeValidityClasses();

    if (validity.hasOwnProperty('first_name') && !validity.first_name) {
      makeInvalidField($cc.find('.cc-first-name'));
    }

    if (validity.hasOwnProperty('last_name') && !validity.last_name) {
      makeInvalidField($cc.find('.cc-last-name'));
    }

    if (!validity.number) {
      makeInvalidField($cc.find('.cc-number'));
    }

    if (!validity.cvv) {
      makeInvalidField($cc.find('.cc-cvv'));
    }

    if (!validity.month || !validity.year) {
      $month.closest(containerSelector).addClass(invalidClass);

      if (!validity.month) $invalidExpiration.add($month);
      if (!validity.year) $invalidExpiration.add($year);
    }

    $invalidExpiration.next('.bootstrap-select')
        .find('button')
            .addClass(invalidClass)
            .append($invalidBorder)
        .find('.filter-option')
            .effect('shake', {distance: 4});

    $cc.find('.' + invalidBorderClass)
        .animate({height: 3}, 250, function() {
            $(this).animate({height: 1}, 250);
        });

    return validity.number && validity.cvv && validity.month && validity.year;
  },

  paymentRequest: function(url) {
    var successPaymentResponse = this.successPaymentResponse.bind(this);

    return $.post(url, this.$form.serialize())
      .done(function(response) {
        var data = response.data;
        if (window.triggerGTMSubscriptionEvents) {
          window.triggerGTMSubscriptionEvents(data);
        }
        successPaymentResponse(response, 'PaymentRedirect')
      })
      .fail(this.failedPaymentResponse.bind(this));
  },

  successPaymentResponse: function(response, mixpanelEvent) {
    var data = response.data || {};

    window.onbeforeunload = null;

    if (mixpanelEvent) {
      window.SOWLMixpanelTrack(mixpanelEvent);
    }

    $('#payment-popup').modal('hide');

    modalVue.showModal({
      modalName: 'success-basic',
      content: {
        html: data.message
      },
      tracking: {
        hasOffersTransactionId: data.hasOffersTransactionId,
        isFreeTrial: data.isFreeTrial,
        isFreemium: data.isFreemium,
      },
      hooks: {after: function() {
        window.location = data.redirect || "/scholarships";
      }}
    })
  },

  failedPaymentResponse: function(jqXHR, textStatus, errorThrown, data) {
    var message = jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.error ?
      ( jqXHR.responseJSON.error ) :
      ( errorThrown + ' please try again.' );

    this.setErrorMessage(message);

    // if (window.zE !== undefined) zE.activate();
  }
});

var SOWLRecurlyCheckout = function(checkout) {
  window.SOWLRecurlyConfigure(function() {

    // When a customer hits their 'enter' key while in a field
    recurly.on('field:submit', function () {
      checkout.$form.submit();
    });

    checkout.$form.on('submit', function (event) {
      var form = this;

      event.preventDefault();
      checkout.clearErrorMessage();
      checkout.removeValidityClasses();

      recurly.token(form, function (err, token, id) {
        if (err) {
          checkout.setErrorMessage(err.message);
          var validity = { first_name: true, last_name: true, number: true, cvv: true, month: true, year: true };
          err.fields.forEach(function(field) {
                                               if (validity.hasOwnProperty(field)) {
                                               validity[field] = false;
                                               }
                                               });
          checkout.affectValidityClasses(validity);
        } else {
          checkout.paymentRequest('/recurly');
        }
      });
    });

    checkout.events.on('paypal-click', function () {
      recurly.paypal({ description: checkout.getBillingAgreement()}, function (err, token) {
        if (err) {
          console && console.error(err);
          checkout.setErrorMessage('There was a problem intializing the PayPal transaction! Please try again in a few moments.');
        } else {
          checkout.toggleSubmitButton(true);
          checkout.$form.find('input[data-recurly=token]').val(token.id);
          checkout.paymentRequest('/recurly');
        }
      });
    });
  });
};

var SOWLBraintreeToken = (function() {
  var request, promise;

  return function () {
    if (promise) return promise;

    if (!request) {
      request = $.get('/braintree/token')
        .always(function () { request = null; })
        .done(function(response) {
          var defer = jQuery.Deferred();
          defer.resolve(response);
          promise = defer.promise();
        });
    }

    return request;
  }
})();

var SOWLBraintreeCheckout = function(checkout, onlyPaypal) {
  var that = this, paypalIntegration;

  var onPaymentMethodReceived = function (payload) {
    checkout.$form.find('[name=payment_method_nonce]').val(payload.nonce);
    checkout.paymentRequest('/braintree');

    return false;
  };

  var initBTCreditCard = function (token) {
    if (checkout.$form && checkout.$form.length) {
      braintree.setup(token, 'custom', {
        id: checkout.$form.get(0).id,
        onError: checkout._logErrorOnBackend,
        onPaymentMethodReceived: onPaymentMethodReceived
      });
    }
  };

  var initBTPayPal = function(token) {
    braintree.setup(token, 'custom', {
      onError: checkout._logErrorOnBackend,
      onPaymentMethodReceived: onPaymentMethodReceived,
      dataCollector: {
            kount: {environment: SOWLStorage.settings.btEnv || 'production' }
        },
      onReady: function (integration) {
        paypalIntegration = integration;
        checkout.$form.find('[name=device_data]').val(integration.deviceData);
      },
      paypal: {
        headless: true,
        singleUse: false,
        billingAgreementDescription: checkout.getBillingAgreement(),
        onSuccess: function () {
          checkout.toggleSubmitButton(true);
        }
      }
    });
  };

  checkout.events.on('paypal-click', function ()  {
    if (paypalIntegration) {
      paypalIntegration.paypal.initAuthFlow();
    }
  });

  checkout.events.on('change.billing-agreement', function() {
      SOWLBraintreeToken().done(function(response) {
          if (paypalIntegration) {
              paypalIntegration.teardown(function() {
                  paypalIntegration = null;
                  initBTPayPal(response.data.token);
              })
          }
      });
  });

  if (!onlyPaypal) {
    checkout.initCreditCardValidation();
  }

  SOWLBraintreeToken()
    .fail(function() { checkout.setErrorMessage('Internal error please try later.') })
    .done(function(response) {
      if (!onlyPaypal) {
        initBTCreditCard(response.data.token);
      }
      initBTPayPal(response.data.token);
    });
};


var SOWLStripeCheckout = function(checkout) {
  var elements = window.SOWLStripe.elements();

  // Custom styling can be passed to options when creating an Element.
  // (Note that this demo uses a wider set of styles than the guide below.)
  var style = {
    base: {
      fontSize: '18px',
    },
  };

  // Create an instance of the card Element
  // var card = elements.create('card', {style: style});

  // Add an instance of the card Element into the `card-element` <div>
  // card.mount('#card-element');

  var cardNumber = elements.create('cardNumber', {
    style: style
  });

  cardNumber.mount(checkout.$form.find('.cc-number').get(0));


  var cardExpiry = elements.create('cardExpiry', {
    style: style
  });
  cardExpiry.mount(checkout.$form.find('.cc-expiry').get(0));


  var cardCvc = elements.create('cardCvc', {
    style: style
  });

  cardCvc.mount(checkout.$form.find('.cc-cvv').get(0));
  // Handle real-time validation errors from the card Element.
  // card.addEventListener('change', function(event) {
  //   console.log('event', event);
  //   checkout.setErrorMessage(event.error ? event.error.message : '');
  // });

  var savedErrors = {};
  [cardNumber, cardExpiry, cardCvc].forEach(function(element, idx) {
    element.on('change', function(event) {
      if (event.error) {
        checkout.setErrorMessage(event.error.message);
      } else {
        savedErrors[idx] = null;

        // Loop over the saved errors and find the first one, if any.
        var nextError = Object.keys(savedErrors)
          .sort()
          .reduce(function(maybeFoundError, key) {
            return maybeFoundError || savedErrors[key];
          }, null);

        if (nextError) {
          checkout.setErrorMessage(nextError);
        } else {
          checkout.clearErrorMessage();
        }
      }
    });
  });

  checkout.$form.on('submit', function(event) {
    event.preventDefault();
    checkout.clearErrorMessage();

    window.SOWLStripe.createToken(cardNumber).then(function(result) {
      if (result.error && result.error.message) {
        checkout.setErrorMessage(result.error.message);
      } else {
          checkout.$form.find('[name=stripe_token]').val(result.token.id);
          checkout.paymentRequest('/stripe');
      }
    });
  });
}
