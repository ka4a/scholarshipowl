var SOWLConfig = {
  all: (function() {
    var configs;

    return function() {
      if (!configs) {
        configs = JSON.parse($('#sowl-config').html());
      }

      return configs;
    }
  })(),

  get: function(name, def) {
    if (typeof this.all()[name] !== 'undefined') {
      return this.all()[name];
    }

    if (typeof def === 'undefined') {
      throw new Error('Unknown SOWL config: ' + name);
    }

    return def;
  },

  /**
   * CREDIT_CARD = 1;
   * PAYPAL = 2;
   * BRAINTREE = 3;
   * RECURLY = 4;
   */
  getDefaultPaymentMethod: function() {
    return parseInt(this.get('defaultPaymentMethod'));
  }
};
