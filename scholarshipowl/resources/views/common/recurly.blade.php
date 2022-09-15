@if (\App\Entity\PaymentMethod::isRecurly())
    <script src="https://js.recurly.com/v4/recurly.js"></script>
    <script>
        window.SOWLRecurlyConfigure = function(done) {
            var configure = function () {
                if (typeof recurly !== 'undefined' && recurly.on) {
                    recurly.configure({
                        publicKey: '{!! config('services.recurly.public_key') !!}',
                        fields: {
                            all: {
                                style: {
                                    height: '100%',
                                    width: '100%',
                                    margin: 0,
                                    padding: '0 0 0 10px',
                                    backgroundColor: 'transparent',
                                    fontSize: '18px',
                                    fontWeigh: 'normal',

                                    border: '1px',
                                    borderColor: 'red',
                                    boxSizing: 'border-box',
                                    borderBottom: '1px solid #ddd',
                                    borderRadius: '0 !important'
                                }
                            },
                            number: {
                                style: {
                                    placeholder: {
                                        content: 'Card Number'
                                    }
                                }
                            },
                            month: {
                                style: {
                                    placeholder: {
                                        content: 'MM'
                                    }
                                }
                            },
                            year: {
                                style: {
                                    placeholder: {
                                        content: 'YY'
                                    }
                                }
                            },
                            cvv: {
                                style: {
                                    placeholder: {
                                        content: 'CVV'
                                    }
                                }
                            }
                        }
                    });

                    if (typeof done === 'function') done();
                } else {
                    setTimeout(configure, 500);
                }
            };

            configure()
        };
    </script>
@endif
