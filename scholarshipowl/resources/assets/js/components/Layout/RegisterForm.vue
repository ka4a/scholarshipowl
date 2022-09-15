<template>
  <section class="form" id="registerForm">
    <h2 class="form__title title-form">Entry Form</h2>
    <h2 class="form__paragraph paragraph-form">Super Fast Registration</h2>
    <p class="form__field relative">
      <label class="form__error-label" v-if="errors.has('firstName')" for="firstName">{{ errors.first('firstName') }}</label>
      <input v-validate="'required'" name="firstName" v-model="form.firstName" class="filed__input" :class="{ 'field__error' : errors.has('firstName') }" id="firstName" placeholder="First Name:" type="text" autocorrect="off" autocapitalize="on">
    </p>
    <p class="form__field relative">
      <label class="form__error-label" v-if="errors.has('lastName')" for="lastName">Last name is required</label>
      <input v-validate="'required'" name="lastName" v-model="form.lastName" class="filed__input" :class="{ 'field__error' : errors.has('lastName') }" id="lastName" placeholder="Last Name:" type="text" autocorrect="off" autocapitalize="on">
    </p>
    <p class="form__field relative">
      <label class="form__error-label" v-if="errors.has('email')" for="first-name">Please enter a valid email</label>
      <input v-validate="'required|email'" name="email" v-model="form.email" class="filed__input" :class="{ 'field__error' : errors.has('email') }" id="email" placeholder="E-mail:" type="email" autocorrect="off" autocapitalize="off">
    </p>
    <p class="form__field relative">
      <label class="form__error-label" v-if="errors.firstByRule('phoneMask', 'required')" for="first-name">Please enter a valid phone number</label>
      <label class="form__error-label" v-if="errors.firstByRule('phoneMask', 'min')" for="first-name">Min phone length 10 characters</label>
      <input
        v-validate="'required|min:16'"
        name="phoneMask"
        :value="local.phoneMask"
        @input="ev => {local.phoneMask = formatUSAPhoneNumber(ev.target.value)}"
        :class="['filed__input', { 'field__error' : errors.firstByRule('phoneMask', 'required') }]"
        id="phone"
        placeholder="Phone:" type="tel">
    </p>

    <p class="form__checkbox vue-checkbox-lp">
      <input v-validate="'required'" name="terms" v-model="form.agree" id="terms-conditions" type="checkbox">
      <label for="terms-conditions">
        <span class="vue-checkbox-lp__item"></span>
        <span class="vue-checkbox-lp__text checkbox-text">
          I agree with the
          <a href="/terms" target="_blank">Terms of Use</a> and
          <a href="/privacy" target="_blank">Privacy Policy</a>, the
          <a href="/terms#exhibit" target="_blank">Official Rules of the You Deserve it Scholarship</a> and
          <a href="/promotion-rules" target="_blank">Official Rules of the Double Your Opportunity Promotion</a>
        </span>
      </label>
    </p>

    <transition name="top-transform">
      <p v-if="errors.firstByRule('terms', 'required')" class="notification-agree-terms">
        <i class="icon icon-attention-round notification-agree-terms__icon"></i>
        <span class="notification-agree-terms__text">
          Please indicate that you accept
          the Terms and Conditions to Apply.
        </span>
      </p>
    </transition>

    <button @click="register" class="top-30 bottom-15 center-margin btn-orange">Register</button>

    <p class="paragraph-member text-center">Already a member? <LoginButton className="link-member" text="Log In Here."/></p>

    <span class="delimeter-grey"></span>

    <div class="tac f_10 lh13" style="color: #2f2f2f">
      <p>No purchase or payment of any kind is necessary to</p>
      <p>enter or win the $1,000</p>
      <p>"You Deserve it!" scholarship</p>
    </div>

    <div class="top-15 tac f_10 lh13" style="color: #2f2f2f">
      <p>*Details and qualifications for participation in</p>
      <p>the promotions may apply.</p>
    </div>

    <p class="form__checkbox vue-checkbox-lp">
      <input v-model="form.agreeCall" :true-value="1" :false-value="0" id="privacy-policy" type="checkbox">
      <label for="privacy-policy">
        <span class="vue-checkbox-lp__item"></span>
        <span class="vue-checkbox-lp__text checkbox-text">By clicking, I consent to be called or texted regarding educational opportunities at the phone number
          provided, including mobile number, using an automated dialing technology, which may contain pre-recorded messages. I may be contacted by ScholarshipOwl.com
          and other partner companies regardless of being listed on a federal or state do not call list. This is a separate offer for education. Consent is not required
          as a condition of using this service.</span>
      </label>
    </p>

  </section>
</template>

<style>
</style>

<script>
import { mapActions } from "vuex";
import LoginButton from "../Layout/LoginButton.vue";
import { formatUSAPhoneNumber } from "lib/utils/format";

export default {
  components: {
    LoginButton
  },
  data () {
    return {
      local: {
        phoneMask: null,
      },
      form: {
        firstName: null,
        lastName: null,
        email: null,
        agree: null,
        agreeCall: 0
      }
    };
  },
  methods: {
    ...mapActions("account", [
      "registration"
    ]),
    formatUSAPhoneNumber,
    register () {
      this.$validator.validateAll().then((result) => {
        if(!result) return;

        // format phone after mask
        this.form.phone = this.local.phoneMask
          ? '+1' + this.local.phoneMask.replace(/\D+/g, "")
          : null;

        this.registration(this.form)
          .then(response => {
            if(response.status === 200 && response.data.data) {
              window.location = "/register2";
            }
          }, response => {
            if(response.status === 400 && response.data.error) {
              this.applyServerErrors(response.data.error);
            }
          });
      });
    },
    applyServerErrors(errors) {
      if(!(errors && Object.keys(errors).length)) {
        return;
      }

      for(var errorName in errors) {
        errors[errorName].forEach(message => {
          this.errors.add(errorName, message);
        });
      }
    }
  }
};
</script>
