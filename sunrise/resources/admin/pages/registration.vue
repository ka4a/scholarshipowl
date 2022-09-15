<template>
  <div class="registration-page">
    <h1 class="title">
      <span>Get started with Sunrise</span>
    </h1>
    <h2 class="subtitle">
      <span>or</span>
      <router-link :to="{ name: 'login'}">login</router-link>
      <span>to existing account</span>
    </h2>
    <div class="registration-form">

      <form @submit.prevent="onSubmit">

        <brand-input
          name="email"
          label="E-mail *"

          v-validate="'required|email|max:255'"
          data-vv-validate-on="blur"
          :error="errors.first('email')"

          v-model="form.email"/>

        <brand-input
          ref="password"
          type="password"
          name="password"
          label="Create password *"

          v-validate="'required|min:6|max:255'"
          data-vv-validate-on="blur"
          :error="errors.first('password')"

          v-model="form.password"/>

        <brand-input
          type="password"
          name="passwordConfirm"
          label="Confirm password *"

          v-validate="'required'"
          data-vv-validate-on="blur"
          data-vv-as="password confirm"
          :error="errors.first('passwordConfirm')"

          v-model="form.passwordConfirm"/>

        <b-field class="field--checkbox">
          <b-checkbox v-model="agreeTerms">
            <span>I agree with</span>
            <a class="link" @click.prevent="$store.state.termsModal = true">Terms&Conditions</a>
          </b-checkbox>
        </b-field>

        <b-field>
          <brand-button type="submit" class="button--login" is-red has-dot :disabled="!agreeTerms">
            <span>REGISTER AND START</span>
          </brand-button>
        </b-field>

      </form>

      <div class="social-signin">
        <google-signin label="Sign up with Google" />
      </div>

    </div>
  </div>
</template>
<script>
import BrandButton from 'components/brand/button';
import BrandInput from 'components/brand/input';
import GoogleSignin from 'components/auth/GoogleSignin';

export default {
  components: {
    BrandButton,
    BrandInput,
    GoogleSignin
  },
  data: function() {
    return {
      agreeTerms: false,
      form: {
        email: null,
        password: null,
        passwordConfirm: null,
      }
    }
  },
  methods: {
    onSubmit() {
      this.$validator.validateAll()
        .then((result) => {
          const confirmed = this.validatePasswordConfirmation();

          if (result && confirmed) {
            this.$store.dispatch('user/registration', this.form)
              .then(() => {
                this.$router.push({ name: 'dashboard' });
              })
              .catch((error) => {
                if (Array.isArray(error.response.data.errors)) {
                  error.response.data.errors.forEach((error) => {
                    if (error.code === 'validation') {
                      const name = error.source.pointer;
                      const field = this.$validator.fields.find({ name });

                      if (field) {
                        this.$validator.errors.add({
                          field: name, msg: error.detail.join('')
                        })
                      }
                    }
                  })
                }
              })
          }
        });
    },
    validatePasswordConfirmation() {
      if (this.form.password !== this.form.passwordConfirm) {
        this.$validator.errors.add({
          field: 'passwordConfirm',
          msg: 'Password confirmation dosn\'t match',
        });
        return false;
      }

      return true;
    }
  }
}
</script>
<style lang="scss">
@import 'node_modules/bulma/sass/utilities/initial-variables';
@import 'node_modules/bulma/sass/utilities/mixins';

.registration-page {

  .social-signin {
    margin-top: 40px;
  }

  .field {
    max-width: 610px;
    &--checkbox {
      margin-top: 39px;
      margin-bottom: 39px !important;
      text-align: left;
    }
  }

  @include until($desktop) {
    .field {
      max-width: none;
    }
    .checkbox {
      .control-label {
        font-size: 17px;
      }
    }
  }
}
</style>
