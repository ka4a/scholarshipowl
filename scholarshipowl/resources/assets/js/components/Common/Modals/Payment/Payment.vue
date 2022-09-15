<template>
  <section class="payment-modal">
    <ul class="payment-ctrl">
      <li @click="initPayPalTokenization" class="payment-ctrl__paypal">
        <img src="./Payment/paypal.png">
      </li>
      <li class="payment-ctrl__card">
        <span class="payment-ctrl__text">Credit card</span>
      </li>
    </ul>
    <div class="payment-form">
      <div class="payment-form__item">
        <label for="card-number-p" class="payment-form__title card-label">
          Card number
          <i :class="['payment-card-type', cardType]"></i>
        </label>
        <div class="rlt">
          <div id="card-number-p"
          :class="[
            'payment-form__hosted-field',
            {'focused': fields.number.isFocused},
            {'invalid': fields.number.isInvalid}
          ]"></div>
          <span v-if="fields.number.isInvalid" class="payment-form__error">Incorrect card number</span>
          <transition name="slide-right-left">
            <i v-if="fields.number.isValid" class="payment-form__success"></i>
          </transition>
        </div>
      </div>

      <div class="payment-form__md">
        <div class="payment-form__item-date">
          <label for="expiration-date-p" class="payment-form__title">Expiration date</label>
          <div class="rlt">
            <div id="expiration-date-p"
            :class="[
              'payment-form__hosted-field',
              {'focused': fields.expirationDate.isFocused},
              {'invalid': fields.expirationDate.isInvalid}
            ]"></div>
            <span v-if="fields.expirationDate.isInvalid" class="payment-form__error">Incorrect expiration date </span>
            <transition name="slide-right-left">
              <i v-if="fields.expirationDate.isValid" class="payment-form__success"></i>
            </transition>
          </div>
        </div>

        <div class="payment-form__item-code">
          <transition name="bounceappear">
            <CVV v-if="isShowCVV" class="payment-form__cvv" />
          </transition>
          <label class="payment-form__title">Security code
            <i @mouseenter="toggleTooltip"
              @mouseleave="toggleTooltip"
              @touchstart="toggleTooltip"
              :class="['icon-question', {'active': isShowCVV}]">?</i>
          </label>
          <div class="rlt">
            <div id="cvv-p"
            :class="[
              'payment-form__hosted-field',
              {'focused': fields.cvv.isFocused},
              {'invalid': fields.cvv.isInvalid}
            ]"></div>
            <span v-if="fields.cvv.isInvalid" class="payment-form__error">Incorrect security code</span>
            <transition name="slide-right-left">
              <i v-if="fields.cvv.isValid" class="payment-form__success"></i>
            </transition>
          </div>
        </div>
      </div>

      <span v-if="transactionError" class="payment-form__trans-err" v-html="transactionError"></span>

      <Btn @click.native="pay"
        @click="pay"
        :showLoader="isDataSending['hostedFields'] || isDataSending['payPal']"
        :shouldHoldKeyPress="true"
        :icon="{position: 'start', name: 'lock'}"
        class="payment-form__btn"
        size="m"
        sizeLoader="s"
        :label="package ? package.popup_cta_text : ''" />

      <p class="payment-form__btm-text">
        By signing up, you agree to the <a href="/terms" target="_blank">ScholarshipOwl</a> terms
      </p>
    </div>
  </section>
</template>

<script>
  import mixpanel from "lib/mixpanel";
  import { PAYMENT_REDIRECT, PAYMENT_BTN_CLK } from "lib/mixpanel";
  import { Payment } from "resource";
  import { isMobile } from "lib/utils/utils";
  import { mapState } from "vuex";

  import InputText from "components/Common/Input/Text/InputTextBasic.vue";
  import InputDate from "components/Common/Input/Select/InputDate.vue";
  import CVV from "components/Common/Modals/Payment/Payment/CVVClarification.vue";
  import Btn from "components/Common/Buttons/ButtonCustom.vue";

  const hostedFieldsOptions = {
    styles: {
      'input': {
        'font-size': '16px',
        'color': '#2F2F2F',
        'border': '1px solid #e8e8e8',
        'padding': '17px 35px 17px 0.6em'
      },
      '::-webkit-input-placeholder': {
        'color': '#C4C4C4'
      },
      ':-moz-placeholder': {
        'color': '#C4C4C4'
      },
      ':-ms-input-placeholder': {
        'color': '#C4C4C4'
      },
      'input.invalid::-webkit-input-placeholder': {
        'color': '#f34857'
      },
      'input.invalid:-moz-placeholder': {
        'color': '#f34857'
      },
      'input.invalid:-ms-input-placeholder': {
        'color': '#f34857'
      },
      ':focus' : {
        'border-color': '#597ce1'
      },
      'input.invalid': {
        'color': '#f34857'
      },
    },
    fields: {
      number: {
        selector: '#card-number-p',
        placeholder: 'Card number',
        maxCardLength: 16
      },
      cvv: {
        selector: '#cvv-p',
        placeholder: 'CVV'
      },
      expirationDate: {
        selector: '#expiration-date-p',
        placeholder: 'MM / YY'
      }
    }
  };

  export default {
    components: {
      InputText,
      InputDate,
      CVV,
      Btn
    },
    mounted() {
      this.$store.dispatch('payment/initPaymentProcessing', {
        paymentClientConsumers: [
          {name: 'hostedFields', options: hostedFieldsOptions},
          {name: 'payPal'},
          {name: 'dataCollector', options: {kount: true}}
        ]
      })
      .then(results => {
        this.initHostedFields(results[0]);
      })
      .catch(response => {
        console.log('error', response);
      })
    },
    props: {
      package: {type: Object, required: true},
      isModalShow: {type: Boolean, required: true}
    },
    data() {
      return {
        isShowCVV: false,
        isDataSending: {
          hostedFields: false,
          payPal: false
        },
        fields: {
          number: {
            isValid: false,
            isInvalid: false,
            isFocused: false,
          },
          expirationDate: {
            isValid: false,
            isInvalid: false,
            isFocused: false
          },
          cvv: {
            isValid: false,
            isInvalid: false,
            isFocused: false
          }
        },
        transactionError: undefined,
        cardType: undefined
      }
    },
    watch: {
      isModalShow(state) {
        if(state) {
          setTimeout(() => this.hostedFields.focus('number'), 500);
        } else {
          Object.keys(this.fields).forEach(name => {
            this.fields[name].isInvalid = false;
            this.hostedFields.removeClass(name, 'invalid');
          })
        }
      }
    },
    computed: {
      now() {
        return new Date();
      },
      ...mapState({
        payPal: state => state.payment.brainTree.payPalInstance,
        hostedFields: state => state.payment.brainTree.hostedFieldsInstance,
        dataCollectorInstance: state => state.payment.brainTree.dataCollectorInstance
      })
    },
    methods: {
      initHostedFields(hostedFields) {
        this.hostedFields = hostedFields;
        hostedFields.on('focus', this.focus);
        hostedFields.on('blur', this.blur);
        hostedFields.on('validityChange', this.validityChanged);
        hostedFields.on('cardTypeChange', ev => {
          if(ev.cards.length === 1) {
            console.log(ev.cards[0].type);
            this.cardType = ev.cards[0].type;
          }
        });
        hostedFields.on('empty', () => (this.cardType = undefined));
      },
      toggleTooltip(ev) {
        const eventType = ev.type;

        if(isMobile() && (eventType === "mouseenter"
          || eventType === "mouseleave")) return;

        this.isShowCVV = !this.isShowCVV;
      },
      focus(ev) {
        const name = ev.emittedBy;

        this.hostedFields.removeClass(name, 'invalid');
        this.fields[name].isInvalid = false;
        this.fields[name].isFocused = true;
      },
      blur(ev) {
        const name = ev.emittedBy;

        this.fields[name].isFocused = false;
      },
      validityChanged(ev) {
        const name = ev.emittedBy;
        const field = ev.fields[name];

        this.fields[name].isInvalid = !field.isValid && !field.isPotentiallyValid;
        this.fields[name].isValid = field.isValid;
      },
      initPayPalTokenization() {
        this.transactionError = undefined;
        this.isDataSending['payPal'] = true;

        this.payPal.tokenize({
            flow: "vault",
            billingAgreementDescription: this.package.billing_agreement
          })
          .then(playload => {
            if(!playload) return;

            return this.sendPaymentData(playload, 'payPal')
          })
          .catch(err => {
            this.isDataSending['payPal'] = false;

            if(err.name !== 'BraintreeError') {
              throw Error(err);
            }

            console.log(err);
          })
      },
      pay() {
        mixpanel.track(PAYMENT_BTN_CLK);

        this.transactionError = undefined;

        if(!this.hostedFields || this.isDataSending['hostedFields']) return;

        const state = this.hostedFields.getState();

        const fieldsAreValid = Object.keys(state.fields)
          .map(name => {
            const isValid = state.fields[name].isValid
              && state.fields[name].isPotentiallyValid;

            if(!isValid) {
              this.hostedFields.addClass(name, 'invalid');
              this.fields[name].isInvalid = true;
            }

            return isValid;
          })
          .every(isValid => isValid);

        if(!fieldsAreValid) return;

        this.isDataSending['hostedFields'] = true;

        this.hostedFields.tokenize((err, payload) => {
          if(err) {
            this.isDataSending['hostedFields'] = false;
            this.transactionError = getTransactionError(err);
            return;
          }

          this.sendPaymentData(payload, 'hostedFields')
        })
      },
      prepareData({ nonce }) {
        const pack = this.package;

        if(!pack) throw Error("Package instance is not defined");

        const data = new FormData();

        data.append('package_id', pack.package_id);
        data.append('free_trial', pack.free_trial);
        data.append('payment_method_nonce', nonce);

        if(this.dataCollectorInstance) {
          data.append('device_data', this.dataCollectorInstance.deviceData);
        }

        return data;
      },
      sendPaymentData(payload, paymentMethodType) {
        Payment.sendTokenizedData(this.prepareData(payload))
          .then(response => {
            if(!response.body || response.body.status !== 200)
              throw Error("Send braintree token response is wrong");

            const data = response.body.data;

            mixpanel.track(PAYMENT_REDIRECT);

            if (window.triggerGTMSubscriptionEvents) {
              window.triggerGTMSubscriptionEvents(data);
            }

            this.isDataSending[paymentMethodType] = false;

            this.$emit('hide', data);
          })
          .catch(response => {
            this.isDataSending[paymentMethodType] = false;

            if(response.body && response.body.error) {
              this.transactionError = response.body.error;
              return;
            }

            const shouldRefresh = confirm("Oops, something went wrong. Do you want to refresh the page and try again?");

            if(shouldRefresh) document.location.reload(true);
          });
      }
    }
  }
</script>

<style lang="scss">
  .payment-form {
    padding: 1px 15px 0 15px;
    background-color: $alice-blue;
    padding-bottom: 18px;
    border: 1px solid $botticelli;
    border-radius: 0 0 15px 15px;

    @include breakpoint($m) {
      border-radius: 0 15px 15px 15px;
      padding: 10px 45px 17px 40px;
    }

    .rlt {
      position: relative;
    }

    &__hosted-field {
      height: 50px;
      background-color: $white;
      box-sizing: border-box;
      border: 1px solid #dde3e7;
      transition: all 200ms;

      &.focused {
        border-color: $havelock-blue;
      }

      &.invalid {
        border-color: $carnation;
      }
    }

    &__item-date,
    &__item-code,
    &__item {
      position: relative;
      margin-top: 15px;

      @include breakpoint($xl) {
        margin-top: 16px;
      }
    }

    &__item-code {
      position: relative;

      @include breakpoint($m) {
        flex: 37.8% 1 1;
        margin-left: 17px;

        .payment-form__title {
          position: relative;
        }

        .icon-question {
          position: absolute;
        }
      }

      @include breakpoint($l) {
        .icon-question {
          top: -2px;
        }
      }
    }

    &__item-date {
      .input-date__month,
      .input-date__year {
        flex: 1 1 50%;
        padding-right: 0;
      }

      @include breakpoint($m) {
        flex: 56.5% 1 1;
      }
    }

    &__title {
      color: $blue-bayoux;
      font-weight: 600;
      display: block;
      font-size: 14px;
      margin-bottom: 5px;

      @include breakpoint($m) {
        margin-bottom: 10.5px;
        font-size: 15px;
      }
    }

    &__md {
      @include breakpoint($m) {
        display: flex;
      }
    }

    &__btn {
      margin-top: 18px;
      padding-left: 15px;
      padding-right: 5px;

      @include breakpoint($m) {
        margin-top: 18px;
      }
    }

    &__trans-err,
    &__error {
      @extend %input-text-error;
    }

    &__trans-err {
      margin-top: 12px;
      display: block;
    }

    &__error {
      position: absolute;
    }

    &__success {
      @include check-mark($turquoise, 12px, 18px, 2px);
      position: absolute;
      right: 14px; top: 14px;
    }

    &__btm-text {
      font-size: 10px;
      line-height: 17px;
      margin-top: 15px;
      text-align: center;
      margin-left: auto;
      margin-right: auto;

      @include breakpoint($m) {
        margin-top: 16px;
      }

      @include breakpoint($l) {
        font-size: 12px;
      }

      a {
        color: $havelock-blue;

        &:hover {
          text-decoration: underline;
        }
      }
    }

    &__cvv {
      position: absolute;
      z-index: 1;
      left: -18px;
      top: -204px;

      @include breakpoint($m $xl - 1px) {
        left: -78px;

        &:before {
          left: 62%;
        }
      }

      @include breakpoint($xl) {
        left: -43px;

        &:before {
          left: 50%;
        }
      }
    }
  }

  .icon-question {
    font-size: 13px;
    height: 18px;
    width: 11.5px;
    padding-left: 6.5px;
    border-radius: 50%;
    background-color: $blue-bayoux;
    color: $white;
    display: inline-block;
    line-height: 1.4em;
    cursor: pointer;

    margin-left: 5px;

    &.active {
      background-color: $havelock-blue;
    }
  }

  .payment-ctrl {
    border-radius: 15px 15px 0 0;
    border-left: 1px solid $botticelli;
    border-top: 1px solid $botticelli;
    border-right: 1px solid $botticelli;
    display: flex;
    overflow: hidden;

    @include breakpoint($m) {
      width: 78.4%;
    }

    @include breakpoint($l) {
      width: 52%;
    }

    &__paypal,
    &__card {
      text-align: center;
      padding: 10px 0;
      cursor: pointer;
      flex: 50% 1 1;

      @include breakpoint($m) {
        padding: 13px 0;
      }
    }

    &__paypal {
      order: 2;
      border-left: 1px solid $botticelli;

      @include breakpoint($xl) {
        max-width: 144px;
      }
    }

    &__card {
      background-color: $alice-blue;

      @include breakpoint($xl) {
        max-width: 187px;
      }
    }

    &__text {
      color: $havelock-blue;
      font-size: 16px;
      line-height: 22px;
      font-weight: 600;

      @include breakpoint($m) {
        font-size: 18px;
      }

      @include breakpoint($xl) {
        font-size: 20px;
      }
    }
  }

  .card-label {
    display: flex;
    justify-content: space-between;
    align-items: flex-end;
  }

  .payment-card-type {
    background: left top no-repeat;
    width: 50px;
    height: 17px;
    display: block;
    background-size: 100%;
    margin-bottom: -9px;

    &.visa {
      width: 38px;
      background-image: url('./Payment/visa.svg');
      height: 15px;
      margin-bottom: -2px;

      @include breakpoint($l) {
        width: 46px;
      }
    }

    &.master-card {
      width: 29px;
      margin-bottom: -5px;
      height: 19px;
      background-image: url('./Payment/mastercard.svg');

      @include breakpoint($l) {
        width: 37px;
        height: 22px;
      }
    }

    &.maestro {
      width: 29px;
      height: 19px;
      margin-bottom: -4px;
      background-image: url('./Payment/maestro.svg');

      @include breakpoint($l) {
        width: 37px;
        height: 24px;
      }
    }

    &.discover {
      background-image: url('./Payment/discover.svg');
      width: 64px;
      height: 11px;
      margin-bottom: -2px;
    }

    &.american-express {
      background-image: url('./Payment/american-express.svg');
      width: 48px;
      height: 21px;
      margin-bottom: -6px;

      @include breakpoint($l) {
        width: 60px;
      }
    }

    &.diners-club {
      background-image: url('./Payment/diners-club.svg');
      width: 86px;
      margin-bottom: -2px;
    }
  }
</style>