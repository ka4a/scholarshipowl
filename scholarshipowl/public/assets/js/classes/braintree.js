var SOWLBraintreeClientToken = {
  request: null,
  getToken: function() {
    var that = this;

    if (!this.request) {
      this.request = $.get('/braintree/token')
        .always(function () { that.request = null })
    }

    return this.request;
  }
};

var SOWLBraintree = Element.extend({

    id: 'braintree-checkout',

    tries: 0,

    formId: 'bt-form',

    $form: null,

    $tabs: null,

    $error: null,

    $freeTrial: null,

    paypalIntegration: null,

    ccSelectors: {
        number: 'input[name=cc-number]',
        cvv:    'input[name=cc-cvv]',
        month:  'select[name=cc-exp-month]',
        year:   'select[name=cc-exp-year]',
        submit: 'button.btn-continue'
    },

    _init: function(element) {
        this._super(element);

        var that = this;

        this.$form  = this.$this.find('form');
        this.$tabs  = this.$this.find('.payment-tabs');
        this.$error = this.$this.find('.bt-error');
        this.$freeTrial = this.$this.find('[name=free_trial]');
        this.$token = this.$this.find('input[name=braintree_token]');
        this.id = this.$this.get(0).id;
        this.formId = this.$form.get(0).id;


      this.$form.on('submit', function(e) {
            var $form = $(this);

            if ($form.data('submitted') === true) {
                e.preventDefault();
            } else {
                $form.data('submitted', true);
                that.toggleSubmitButton(true);
            }
        });

        this.$tabs.find('.tab-paypal').click(function(e) {
            e.preventDefault();
            e.stopPropagation();

            window.SOWLMixpanelTrack('BraintreePaypal click');

            if (that.paypalIntegration) {
                that.paypalIntegration.paypal.initAuthFlow();
            }
        });

        this.setFreeTrial(this.$freeTrial.val() === 'true');

        this.getToken(function(token) {
            this._initPayPal(token);
            this._initCreditCard(token);
        });
    },

    getToken: function(done) {
      var that = this;

      if (that.$token.val()) {
        done.call(that, that.$token.val());
        return;
      }

      if (this.tries === 0) this.tries = 1;

      SOWLBraintreeClientToken.getToken()
        .fail(function() {
          if (that.tries++ < 3) {
            setTimeout(function() { that.getToken(done); }, 500);
          } else {
            that.setErrorMessage('Internal error please try later.');
          }
        })
        .done(function(response) {
          var token = response.data.token;

          that.tries = 0;
          that.$token.val(token);

          done.call(that, token)
        });
    },

    setBillingAgreement: function(text) {
        this.$form.find('[name=billing-agreement]').val(text);
        this._reinitPayPal();
        return this;
    },

    getBillingAgreement: function() {
        return this.$form.find('[name=billing-agreement]').val();
    },

    setFreeTrial: function(freeTrial) {
        this.$freeTrial.val(freeTrial ? 'true' : 'false');
        this.$this.find('.message')
          .text(freeTrial ? 'Activate Trial' : 'Continue')
          .toggleClass('free-trial', freeTrial);
    },

    setPackageId: function(packageId) {
        this.$form.find('[name=package_id]').val(packageId);
    },

    setTrackingParams: function(trackingParams) {
        this.$form.find('[name=tracking_params]').val(trackingParams);
    },

    setPaymentNonce: function(nonce) {
        this.$form.find('[name=payment_method_nonce]').val(nonce);
    },

    toggleSubmitButton: function(toggle) {
        var $button = this.$form.find('button[type=submit]');

        $button.prop('disabled', typeof toggle === 'undefined' ? !$button.prop('disabled') : !!toggle);
    },

    clearErrorMessage: function() {
        this.$error.text('');
    },

    setErrorMessage: function(message) {
        this.$form.data('submitted', false);
        this.$error.html(message).parent().effect('shake', {distance: 4});
    },

    onPaymentMethodReceived: function (payload) {
        var that = this;

        this.setPaymentNonce(payload.nonce);

        $.post(this.$form.attr('action'), this.$form.serialize())
            .always(function() {
                that.toggleSubmitButton(false);
            })
            .done(function(response) {
              var data = response.data || {};

              window.onbeforeunload = null;
              window.SOWLMixpanelTrack('PaymentRedirect');

              if (data.redirect && data.html) {

                $('#payment-popup').modal('hide');

                $(data.html).appendTo(document.body)
                  .modal('show')
                  .click(function() {
                    $(this).modal('hide');
                    window.location = data.redirect;
                  })
                  .on('hidden.bs.modal', function() {
                    window.location = data.redirect;
                  });

              } else {
                window.location.reload();
              }
            })
            .fail(function(jqXHR, textStatus, errorThrown, data) {
                var message = jqXHR && jqXHR.responseJSON && jqXHR.responseJSON.error ?
                  ( jqXHR.responseJSON.error ) :
                  ( errorThrown + ' please try again.' );

                that.setErrorMessage(message);
                if (window.zE !== undefined) zE.activate();
            });

        return false;
    },

    _initCreditCard: function(token) {
        var that    = this,
            $cc     = this.$this.find('.bt-credit-card'),
            $submit = $cc.find(this.ccSelectors.submit),
            $number = $cc.find(this.ccSelectors.number),
            $cvv    = $cc.find(this.ccSelectors.cvv),
            $month  = $cc.find(this.ccSelectors.month),
            $year   = $cc.find(this.ccSelectors.year),

            containerSelector = '.bt-input-container',
            invalidClass = 'invalid-input',
            invalidBorderClass = 'invalid-input-border',

            $invalidBorder = $('<div>').addClass(invalidBorderClass);

        $cvv.payment('formatCardCVC');
        $number.payment('formatCardNumber');
        braintree.setup(token, "custom", {
            id: this.formId,
            onError: that._logErrorOnBackend,
            onPaymentMethodReceived: this.onPaymentMethodReceived.bind(this)
        });

        $cc.find('select').selectpicker({
            selectOnTab: true,
            mobile: /Android|webOS|iPhone|iPad|iPod|BlackBerry/i.test(navigator.userAgent)
        });

        $submit.click(function(event) {
            var $invalidExpiration = $([]);

            window.SOWLMixpanelTrack('BraintreeContinue click');
            that.clearErrorMessage();

            $cc.find('.' + invalidClass).removeClass(invalidClass).end()
                .find('.' + invalidBorderClass).remove();

            if (!$.payment.validateCardNumber($number.val())) {
                $number.effect('shake', {distance: 4});
                $number.closest(containerSelector).addClass(invalidClass)
                    .append($invalidBorder.clone());
            }

            if (!$.payment.validateCardCVC($cvv.val(), $.payment.cardType($number.val()))) {
                $cvv.effect('shake', {distance: 4});
                $cvv.closest(containerSelector).addClass(invalidClass)
                    .append($invalidBorder.clone());
            }

            if (!$.payment.validateCardExpiry($month.val(), $year.val())) {
                $month.closest(containerSelector).addClass(invalidClass);

                if (!$month.val()) {
                    $invalidExpiration = $invalidExpiration.add($month);
                }

                if (!$year.val()) {
                    $invalidExpiration = $invalidExpiration.add($year);
                }

                if ($month.val() && $year.val()) {
                    $invalidExpiration = $month.add($year);
                }

                $invalidExpiration.next('.bootstrap-select')
                    .find('button')
                        .addClass(invalidClass)
                        .append($invalidBorder)
                    .find('.filter-option')
                        .effect('shake', {distance: 4});
            }

            $cc.find('.' + invalidBorderClass)
                .animate({height: 3}, 250, function() {
                    $(this).animate({height: 1}, 250);
                });

            if ($cc.find('.' + invalidClass).length !== 0) {
                event.preventDefault();
            }
        });
    },

    _initPayPal: function(token) {
        var that = this;

        braintree.setup(token, 'custom', {
            onError: that._logErrorOnBackend,
            onPaymentMethodReceived: this.onPaymentMethodReceived.bind(this),
            onReady: function(integration) {
                that.paypalIntegration = integration;
            },
            paypal: {
                headless: true,
                singleUse: false,
                billingAgreementDescription: that.getBillingAgreement(),
                onSuccess: function() {
                    that.toggleSubmitButton(true);
                }
            }
        });
    },

    _reinitPayPal: function() {
        var that = this;

        this.getToken(function(token) {
            if (that.paypalIntegration) {
                that.paypalIntegration.teardown(function() {
                    that.paypalIntegration = null;
                    that._initPayPal(token);
                })
            }
        });
    },

    _logErrorOnBackend: function(error) {
        $.post('/log-error', {'error': JSON.stringify({
            braintree: error,
            currentUrl: window.location.href
        })});
    }
});
