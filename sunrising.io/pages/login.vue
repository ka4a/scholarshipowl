<template>
  <div class="auth-form">
    <div class="logo">
      <nuxt-link to="/">
        <img src="~/assets/img/logo_wide_desktop.svg" alt="logo">
      </nuxt-link>
    </div>

    <h2 class="auth-title">Login to Sunrise</h2>
    <h4 class="auth-subtitle">
      Not a member yet?
      <nuxt-link
        to="/registration"
        class="red-link"
      >
        Register!
      </nuxt-link>
    </h4>

    <form novalidate @submit.prevent="onFormSubmit">
      <sun-input
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
        v-model="form.password"
        :errors="{
          required: 'Create password.',
          type: 'Wrong password. Try again.'
        }"
        :required="true"
        type="password"
        label="Password *"
        variant="dark"
      />

      <sun-checkbox
        v-model="form.remember"
        text="Remember me"
        type="dark"
      />

      <div class="submit-wrapper">
        <sun-button
          :thin="true"
          variant="red"
          class="submit-button"
          text="LOG IN"
        />

        <div class="problems-link-holder">
          <a
            class="red-link"
            href="javascript:;"
          >
            Problems with logging in?
          </a>
        </div>
      </div>
    </form>
  </div>
</template>

<script>
import { SunInput, SunCheckbox, SunButton } from '~/components';

export default {
  name: 'Login',

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
        remember: false,
      },
    };
  },

  methods: {
    onFormSubmit() {
      console.log('login submit: ', this.form);
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
