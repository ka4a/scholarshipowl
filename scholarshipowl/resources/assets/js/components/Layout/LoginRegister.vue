<template>
  <transition name="top-transform">
    <div class="vue-modal-cover" v-if="isOpen">
      <div class="vue-modal-frame">
        <article class="vue-modal">
        <a class="vue-modal__ctrl icon icon-clear" @click="closeModal('login')"></a>
        <section class="vue-modal__wrp vue-modal_login" v-show="state === 'login'">
          <header class="vue-modal__header">
            <h2 class="vue-modal__title display_3 tu">login</h2>
            <h2 class="vue-modal__sub-title title-serif-22">Welcome back!</h2>
          </header>
          <main class="vue-modal__main">
            <div class="vue-modal__interaction">
              <p class="relative">
                <label class="error-label" v-if="error.email">{{ error.email }}</label>
                <input @keyup.enter="loginAttempt" v-model="credentials.email" @focus="errors.email = null" class="vue-modal__field" :class="{ 'field_error':errors.email }" type="email" placeholder="Email">
              </p>
              <p class="relative top-20">
                <label class="error-label" v-if="error.password">{{ error.password }}</label>
                <input @keyup.enter="loginAttempt" v-model="credentials.password" @focus="errors.password = null" class="vue-modal__field" :class="{ 'field_error':errors.password }" type="password" placeholder="Password">
              </p>
              <div class="vue-modal__checkbox vue-checkbox">
                <input v-model="credentials.remember" id="remember" checked ref="remember" type="checkbox">
                <label for="remember">
                  <span class="vue-checkbox__item"></span>
                  <span class="vue-checkbox__text">Keep me signed in</span>
                </label>
              </div>
              <button @click="loginAttempt" class="vue-modal__btn btn-blue-m-fluid">login</button>
            </div>
            <a @click="state = 'forgot-password'" class="vue-modal__reset-pass ctrl-reset-pass">Forgot / Reset Password</a>
            <div class="vue-modal__delimeter text-delimeter"><span>or</span></div>
            <div class="vue-modal__facebook">
            <FacebookLoginButton />
            </div>
          </main>
          <footer class="vue-modal__footer">
            <p class="vue-modal__wrp dib"><span>Don’t have an account. </span><a href="/register">SIGN UP!</a></p>
          </footer>
        </section>

        <section class="vue-modal_reset relative" v-show="state === 'forgot-password'">
          <div class="text-center">
            <h2 class="title-serif-24 bottom-25">Forgot your password?</h2>
            <p class="paragraph_2"><span class="break-from-xs"><span class="break-xs">Enter your email address below and</span> we’ll send you instructions</span> <span class="break-xs">on how to reset your password.</span></p>
          </div>
          <div class="vue-modal__wrp top-25">
            <p class="relative bottom-20">
              <label class="error-label" v-if="error.emailReset">{{ error.emailReset }}</label>
              <input v-model="emailReset" @keyup.enter="resetPassword" @focus="error.emailReset = null" autofocus :class="{ 'vue-modal__field':true, 'field_error':error.emailReset }" type="text" placeholder="Your email address">
            </p>
            <button @click="resetPassword" class="btn-blue-m-fluid">submit</button>
          </div>
        </section>

        <section class="vue-modal__wrp vue-modal_reset-confirm" v-show="state === 'forgot-success'">
          <h2 class="title-serif-24 bottom-25">Dear Student,</h2>
          <p class="paragraph_2">Your password has been successfully reset.</p>
          <p class="paragraph_2">Check your mail for details.</p>
        </section>
      </article>
      </div>
    </div>
  </transition>
</template>

<style>
</style>

<script type="javascript">
import Vue from "vue";
import { mapState, mapActions } from "vuex";

import FacebookLoginButton from "../Common/Buttons/FacebookLogin.vue";

export default {
  components: {
    FacebookLoginButton
  },
  data () {
    return {
      state: "login",
      error: {
        email: null,
        password: null,
        emailReset: null,
      },
      credentials: {
        password: null,
        email: null,
        remember: null,
      },
      emailReset: null,
      facebookStatus: null,
    };
  },
  methods: {
    resetPassword() {
      this.error.emailReset = null;

      Vue.http.post("/post-forgot-password", { email: this.emailReset })
        .then((response) => {
          if (response.data && response.data.status === "ok") {
            this.state = "forgot-success";
          } else if (response.data && response.data.status === "error") {
            this.error.emailReset = response.data.data.email;
          }
        });
    },
    loginAttempt () {
      this.error.email = null;
      this.error.password = null;

      this.$store.dispatch("account/loginAttempt", this.credentials)
        .catch((response) => {
          if (response.data && response.data.error) {
            if (typeof response.data.error === "object") {
              Object.keys(response.data.error).forEach(field => {
                if (this.error.hasOwnProperty(field)) {
                  this.error[field] = response.data.error[field].join(" ");
                }
              });
            }
          }
        });
    },
    ...mapActions("modal", [
      "closeModal"
    ])
  },
  watch: {
    isOpen (isOpen, oldIsOpen) {
      if (!oldIsOpen && isOpen) {
        this.state = "login";
      }
    }
  },
  computed: {
    ...mapState("modal", {
      isOpen: state => state.modals.login
    })
  },
};
</script>

<style lang="scss">

@import 'style-gide/assets';
@import 'main/meta/variables';
@import 'main/meta/palette';
@import 'main/meta/helpers';
@import 'main/controls-elements';

$primary: #4a4a4a;
$secondary: #4988f7;

.vue-modal {
  // variables
  $width: 295px;
  $height: 318px;

  $secondary-font: $raleway;

  display: block;
  max-width: $width * 2;
  @extend %bc-modal;
  @extend .center-margin;

  @include breakpoint(max-width $mobile) {
    padding-left: 15px;
    padding-right: 15px;
    height: 100%;
  }

  @include breakpoint($mobile) {
    box-shadow: 0 0 4px 2px rgba(0,0,0,.2);
  }

  // transiton animation effects
  @at-root {
    .top-transform-leave-to,
    .top-transform-enter {
      opacity: 0;
      .vue-modal-frame {
        transform: translate(0, -25%);
      }
    }

    .top-transform-leave-active,
    .top-transform-enter-active {
      @extend %transition;
      .vue-modal-frame {
        @extend %transition;
      }
    }
  }

  // overflow html body
  // remove vertical scroll
  @at-root {
    .modal-open,
    .modal-open body {
      // IOs fix
      @include breakpoint(max-width $mobile) {
        height: 100%;
        position: relative;
      }

      overflow: hidden;

      .navbar {
        top: 0 !important;
      }
    }
  }

  // block
  &-cover {
    @extend %center-fixed;
    background-color: rgba(0,0,0,.6);
    z-index: 4999;
    @include breakpoint($mobile) {
      z-index: 5001;
    }
  }

  &-frame {
    @extend %center-absolute;
    max-height: 628px;
    overflow-y: auto;

    @include breakpoint($mobile) {
      border-radius: 5px;
    }

    @include breakpoint(max-width $mobile) {
      margin-top: 58px;
      background-color: $white;

      // modification for landing pages
      @at-root {
        .landing-page .vue-modal-frame {
          margin-top: 0;
        }
      }
    }
  }

  // modificators
  &_login {
    padding-top: 20px;
    padding-bottom: 85px;
  }

  &_reset {
    padding-top: 20px;
    padding-bottom: 50px;
  }

  &_reset-confirm {
    padding-top: 20px;
    padding-bottom: 46px;
  }

  &__wrp {
    max-width: 195px * 2;
    @extend .center-margin;
    @extend .text-center;
    margin-bottom: 0;
  }

  &__ctrl {
    right: 15px; top: 15px;
    cursor: pointer;
    z-index: 1;

    &:before,
    &:after {
      @extend %transition;
    }

    @include css-icon('cross', (
      line-width: 3px,
      width: 33px,
      height: 33px,
      color: $blue-lighter
    ))

    &:hover {
      &:before,
      &:after {
        background-color: darken($blue-lighter, 10%);
      }
    }

    @include breakpoint(max-width $mobile) {
      right: 5px; top: 5px;
    }
  }

  &__title {
    @extend %margin-reset;

    @include breakpoint($mobile) {
      margin-bottom: 15px !important;
    }
  }

  &__interaction {
    margin-top: 25px;

    @include breakpoint(max-width $mobile) {
      margin-top: 15px;
    }
  }

  &__field {
    @include input($dark, $blue-lighter, $blue-lighter, $open-sans);
    @extend %transition;

    padding: 0 15px;
    font: 400 14px/20px 'Open Sans';
    border-width: 1px;
    border-style: solid;
    border-color: #a3c3f1;
    background-color: #fff;
    box-shadow: none;
    -moz-box-shadow: none;
    -webkit-box-shadow: none;
    color: #000;
    margin-bottom: 0;

    & + & {
      margin-top: 20px;

      @include breakpoint(max-width $mobile) {
        margin-top: 15px;
      }
    }
  }

  &__checkbox {
    margin-top: 15px;
  }

  &__btn {
    margin-top: 20px;
  }

  &__sub-title { // posible independent text unit(block) @block-text
    @include breakpoint($mobile) {
      margin-bottom: 26px !important;
      font-weight: 200;
    }
  }

  &__reset-pass {
    padding-top: 22px;
    display: block;

    @include breakpoint(max-width $mobile) {
      padding-top: 10px;
    }
  }

  &__delimeter {
    margin-top: 15px;
  }

  &__facebook {
    margin-top: 25px;
    margin-bottom: 25px;

    @include breakpoint(max-width $mobile) {
      margin-top: 15px;
    }
  }

  @at-root {
    .ctrl-reset-pass {
      font-family: Helvetica;
      font-size: 6px * 2;
      text-align: center;
      color: $blue-darker;
      cursor: pointer;
      line-height: 1.4em;
      &:hover {
        text-decoration: none;
        color: darken($blue-darker, 10);
      }
    }
  }

  &__footer {
    @extend .text-center;
    border-radius: 0 0 5px 5px;

    @include breakpoint(max-width $mobile) {
      margin-top: 10px;
    }

    @include breakpoint($mobile) {
      height: 36px * 2;
      line-height: 72px;
      background-color: #f5f9ff;
      position: absolute;
      bottom: 0; left: 0;
      margin: 0;
      width: 100%;
    }

    span, a {
      font-family: $open-sans;
      font-size: 13px;
      font-weight: bold;
      text-align: center;
      color: #7f7f7f;
    }

    span {
      @extend %uppercase;
    }

    a {
      color: #7990e7;
    }
  }
}
</style>
