<template>
  <div class="login-page">
    <h1 class="title">
      Login to Sunrise
    </h1>
    <h2 class="subtitle">
      <span>Not a member yet?</span>
      <router-link :to="{ name: 'registration'}">
        Register!
      </router-link>
    </h2>
    <div class="login-form">

      <form @submit.prevent="onSubmit">

        <brand-input
          name="email"
          label="E-mail *"

          v-validate.disable="'required|email'"
          data-vv-validate-on="blur"
          :error="errors.first('email')"

          v-model="form.email" />

        <brand-input
          name="password"
          type="password"
          label="Password *"

          v-validate.disable="'required'"
          data-vv-validate-on="blur"
          :error="errors.first('password')"

          v-model="form.password" />

        <b-field class="field--remember-me">
          <b-checkbox>Remember me</b-checkbox>
        </b-field>

        <div class="login-controls">
          <brand-button class="button--login" type="submit" is-red>
            <span>LOG IN</span>
          </brand-button>
          <a class="link" @click.prevent="forgotPasswordModal = true">Problems with logging in?</a>

          <b-modal class="modal-forgot-password is-small" :active.sync="forgotPasswordModal" :canCancel="['escape', 'outside', 'button']">
            <div class="box">
              <i class="boxclose" @click="forgotPasswordModal = false" />
              <h3 class="modal-title">Forgot password?</h3>
              <p class="description">Don't worry, we got this. Enter e-mail, used for registration and we will send changing password link</p>
              <form @submit.prevent="onResetPassword" data-vv-scope="resetPassword">
                <brand-input
                  name="resetPasswordEmail"
                  label="E-mail *"
                  class="field--password-email"

                  v-validate.disable="'required|email'"
                  data-vv-scope="resetPassword"
                  data-vv-as="email"
                  :error="errors.first('resetPasswordEmail', 'resetPassword')"

                  v-model="resetPasswordEmail" />
                <b-field>
                  <brand-button type="submit" is-red>
                    <span>CHANGE PASSWORD</span>
                  </brand-button>
                </b-field>
              </form>
            </div>
          </b-modal>

          <b-modal class="modal-forgot-password is-small modal-forgot-password__success"
            :canCancel="['escape', 'outside', 'button']"
            :active.sync="forgotPasswordModalSuccess">
            <div class="box">
              <i class="boxclose" @click="forgotPasswordModalSuccess = false" />
              <svg width="71" height="71" viewBox="0 0 71 71" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M69.1051 2.63489L41.6218 42.3093L38.7571 46.4448V60.3314L49.2004 50.1248L43.8669 40.1688" fill="#F9C1C6"/>
                <path d="M29.0524 43.4758L8.875 37.3028L69.6132 1.38684V55.8848L39.2441 46.5938" fill="#CE818C"/>
                <path d="M38.7565 60.3314L29.5857 43.6392L69.1045 2.63489L39.6087 45.2143L38.7565 60.3314Z" fill="#D73148"/>
                <path d="M70.3004 0.182257C69.8679 -0.064579 69.3359 -0.0604189 68.9075 0.193073L43.4953 15.2201C42.8361 15.6099 42.6176 16.4602 43.0075 17.1195C43.3971 17.7786 44.2475 17.997 44.9068 17.6073L63.1397 6.82574L29.1781 42.064L12.2868 36.8963L34.3308 23.8612C34.99 23.4712 35.2084 22.6209 34.8186 21.9616C34.4289 21.3025 33.5785 21.0838 32.9192 21.4738L8.16923 36.1091C7.69636 36.3888 7.43302 36.9211 7.49806 37.4668C7.5631 38.0125 7.94403 38.4681 8.46932 38.6289L28.6418 44.8003L37.5413 60.999C37.5506 61.0158 37.5602 61.0299 37.57 61.0425C37.7185 61.2902 37.9411 61.4925 38.2187 61.6093C38.3925 61.6824 38.5751 61.718 38.7565 61.718C39.1119 61.718 39.4618 61.5813 39.7259 61.3229L49.9591 51.3219L69.2075 57.2107C69.3407 57.2514 69.4773 57.2715 69.6132 57.2715C69.9066 57.2715 70.1964 57.1783 70.438 56.9995C70.7914 56.738 71 56.3243 71 55.8848V1.38676C71 0.888789 70.7329 0.428954 70.3004 0.182257ZM58.1845 15.9639L37.6169 45.6549C37.4563 45.8869 37.3702 46.1623 37.3702 46.4445V54.9272L31.2932 43.8661L58.1845 15.9639ZM40.1436 57.037V48.3191L46.9371 50.3975L40.1436 57.037ZM40.9777 45.6739L68.2266 6.3379V54.0103L40.9777 45.6739Z" fill="black"/>
                <path d="M22.8009 48.1991C22.2593 47.6576 21.3815 47.6576 20.8397 48.1991L7.89442 61.1444C7.3529 61.6859 7.3529 62.564 7.89442 63.1056C8.16538 63.3763 8.52024 63.5117 8.8751 63.5117C9.22996 63.5117 9.58482 63.3763 9.85565 63.1055L22.8009 50.1602C23.3424 49.6188 23.3424 48.7408 22.8009 48.1991Z" fill="black"/>
                <path d="M3.59559 65.4433L0.406135 68.6327C-0.135378 69.1742 -0.135378 70.0523 0.406135 70.594C0.676961 70.8647 1.03182 71 1.38668 71C1.74154 71 2.0964 70.8645 2.36723 70.5938L5.55668 67.4044C6.09819 66.8629 6.09819 65.9848 5.55668 65.4431C5.01503 64.9019 4.13724 64.9019 3.59559 65.4433Z" fill="black"/>
                <path d="M18.8013 68.1448C18.435 68.1448 18.0788 68.2932 17.8209 68.5511C17.5629 68.809 17.4146 69.1654 17.4146 69.5315C17.4146 69.8962 17.5628 70.254 17.8209 70.5119C18.0788 70.7698 18.4366 70.9182 18.8013 70.9182C19.166 70.9182 19.5237 70.7698 19.7815 70.5119C20.0395 70.254 20.188 69.8962 20.188 69.5315C20.188 69.1654 20.0396 68.809 19.7815 68.5511C19.5237 68.2932 19.166 68.1448 18.8013 68.1448Z" fill="black"/>
                <path d="M28.1381 58.2332L21.2814 65.0898C20.7399 65.6313 20.7399 66.5094 21.2814 67.0509C21.5522 67.3217 21.9071 67.4571 22.2619 67.4571C22.6168 67.4571 22.9716 67.3216 23.2425 67.0509L30.0992 60.1943C30.6408 59.6527 30.6408 58.7747 30.0992 58.2332C29.558 57.6916 28.6802 57.6916 28.1381 58.2332Z" fill="black"/>
                <path d="M51.8025 57.7067L44.9271 64.582C44.3856 65.1235 44.3856 66.0016 44.9271 66.5433C45.198 66.814 45.5528 66.9494 45.9077 66.9494C46.2625 66.9494 46.6174 66.814 46.8882 66.5433L53.7636 59.6679C54.3051 59.1264 54.3051 58.2483 53.7636 57.7067C53.2219 57.1653 52.3441 57.1653 51.8025 57.7067Z" fill="black"/>
                <path d="M38.3328 21.2695C38.6975 21.2695 39.0552 21.1225 39.3132 20.8632C39.571 20.6053 39.7195 20.2489 39.7195 19.8842C39.7195 19.5181 39.5711 19.1603 39.3132 18.9024C39.0552 18.6445 38.6975 18.4961 38.3328 18.4961C37.9681 18.4961 37.6103 18.6445 37.3524 18.9024C37.0946 19.1603 36.946 19.5181 36.946 19.8842C36.946 20.2489 37.0944 20.6053 37.3524 20.8632C37.6103 21.1225 37.9681 21.2695 38.3328 21.2695Z" fill="black"/>
              </svg>
              <h3 class="modal-title">Check your e-mail!</h3>
              <p class="description">We have sent you an e-mail with a link, which can help you to change password</p>
            </div>
          </b-modal>
        </div>

      </form>

      <div class="social-signin">
        <google-signin />
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
    GoogleSignin,
  },
  data: function() {
    return {
      forgotPasswordModal: false,
      forgotPasswordModalSuccess: false,
      resetPasswordEmail: null,
      form: {
        email: null,
        password: null,
      }
    }
  },
  methods: {
    onSubmit() {
      this.$validator.validateAll()
        .then((result) => {
          if (result) {
            this.$store.dispatch('user/login', this.form)
              .then(() => {
                this.$router.push({ name: 'dashboard' });
              })
              .catch(({ response }) => {
                if (response.data.error === 'invalid_credentials') {
                  this.$validator.errors.add({
                    field: 'email',
                    msg: response.data.message
                  })
                }
              })
          }
        });
    },
    onResetPassword() {
      this.$validator.validateAll('resetPassword')
        .then(result => {
          const email = this.resetPasswordEmail;

          if (result) {
            this.$http.post('/auth/password/email', { email })
              .then(() => {
                this.forgotPasswordModalSuccess = true;
                this.forgotPasswordModal = false;
              })
              .catch(() => {
                this.$validator.errors.add({
                  field: 'resetPasswordEmail',
                  msg: 'Such email not found in registered users.',
                  scope: 'resetPassword'
                });
              })
          }
        })
    }
  }
}
</script>
<style lang="scss">
@import 'node_modules/bulma/sass/utilities/initial-variables';
@import 'node_modules/bulma/sass/utilities/mixins';

.login-page {
  .button--login {
    > span {
      width: 105px;
    }
  }
  .field--remember-me {
    margin-top: 39px;
    margin-bottom: 39px !important;
    text-align: left;
  }
  .login-controls {
    > .button {
      margin-right: 20px;
    }
    display: flex;
    align-items: center;
  }
  .login-form {
    .field {
      max-width: 610px;
    }
  }
  .modal-forgot-password {
    p.description {
      margin-bottom: 32px;
    }
    .field--password-email {
      margin-bottom: 14px !important;
    }
    &__success {
      text-align: center;
    }
  }

  .social-signin {
    margin-top: 40px;
  }

  @include until($desktop) {
    .login-controls {
      flex-direction: column;

      > .button {
        margin: 0;
      }

      .link {
        margin-top: 10px;
      }
    }
    .login-form{
      .field {
        max-width: none;
      }
    }
  }
}
</style>
