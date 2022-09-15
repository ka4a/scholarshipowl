<template>
  <div class="auth-form">
    <div class="logo">
      <nuxt-link to="/">
        <img src="~/assets/img/logo_wide_desktop.svg" alt="logo">
      </nuxt-link>
    </div>

    <h2 class="auth-title">Get started with Sunrise</h2>
    <h4 class="auth-subtitle">
      or
      <nuxt-link
        to="/login"
        class="red-link"
      >
        login
      </nuxt-link>
      to existing account
    </h4>

    <form novalidate @submit.prevent="onFormSubmit">
      <sun-input
        ref="emailInput"
        v-model="form.email"
        :errors="{
          required: 'Type your e-mail',
          type: 'Wrong e-mail. Try again.'
        }"
        :required="true"
        type="email"
        variant="dark"
        label="Email *"
        class="auth-input"
      />

      <sun-input
        ref="passInput"
        v-model="form.password"
        :errors="{
          required: 'Create password.',
          type: 'Wrong password. Try again.'
        }"
        :required="true"
        type="password"
        label="Create Password *"
        variant="dark"
        class="auth-input"
      />

      <sun-input
        ref="repeatPassInput"
        v-model="form.repeatPassword"
        :errors="{
          required: 'Repeat password.',
          match: 'Passwords don\'t match',
        }"
        :required="true"
        :match="form.password"
        type="password"
        label="Repeat password *"
        variant="dark"
      />

      <sun-checkbox
        v-model="form.terms"
        text="I agree with <nuxt-link class='red-link' to='/terms'>Terms&amp;Conditions</nuxt-link>"
        type="dark"
      />

      <div class="submit-wrapper">
        <sun-button
          :icon="true"
          :disabled="!isFormValid"
          class="submit-btn"
          variant="red"
          text="REGISTER AND START"
        />
      </div>
    </form>
  </div>
</template>

<script>
import { SunInput, SunCheckbox, SunButton } from '~/components';

export default {
  name: 'Registration',

  layout: 'auth',

  components: {
    SunInput,
    SunCheckbox,
    SunButton,
  },

  data() {
    return {
      form: {
        email: '',
        password: '',
        repeatPassword: '',
        terms: false,
      },
    };
  },

  computed: {
    isFormValid() {
      return this.form.email && this.form.password && this.form.repeatPassword;
    },
  },

  methods: {
    onFormSubmit() {
      if (this.formSubmitValidate()) {
        console.log('registration submit: ', this.form);
      }
    },

    formSubmitValidate() {
      return this.$refs.emailInput.isInputValid
        && this.$refs.passInput.isInputValid
        && this.$refs.repeatPassInput.isInputValid;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

.auth-form {
  position: relative;
  padding-top: 51px;
  text-align: center;
  color: $dark-blue;
  font-family: 'Catamaran-Light';

  @include sun-desktop {
    padding-top: 100px;

    @include sun-hd {
      padding-top: 35%;
    }
  }

  .logo {
    margin-bottom: 28px;
  }

  .auth-title {
    line-height: 38px;
    font-size: 32px;
  }

  .auth-subtitle {
    color: $brownish;
    line-height: 89px;
    font-size: 20px;
  }

  .auth-input {
    margin-bottom: 18px;
  }

  .submit-wrapper {
    margin-top: 30px;
    margin-bottom: 43px;

    @include sun-desktop {
      display: flex;
    }

    @include sun-widescreen {
      margin-top: 60px;
    }

    .submit-button {
      margin-bottom: 20px;

      @include sun-desktop {
        margin-right: 20px;
        margin-bottom: 0;
      }

      @include sun-widescreen {
        margin-right: 40px;
      }
    }

    .problems-link-holder {
      display: flex;
      align-items: center;
      justify-content: center;

      .red-link {
        color: $red;
      }
    }
  }
}
</style>
