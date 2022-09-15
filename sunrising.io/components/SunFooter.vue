<template>
  <footer id="footer" class="footer">
    <div class="top-text">
      <h3>Get started <span>now</span></h3>
      <h4 class="feature-title">WHAT ARE YOU WAITING FOR?</h4>
    </div>

    <div class="container">
      <form
        class="footer-form"
        novalidate
        @submit.prevent="onFormSubmit"
      >
        <sun-input
          ref="email"
          v-model="form.email"
          :errors="{
            required: 'Type your e-mail',
            type: 'Wrong e-mail. Try again.'
          }"
          :required="true"
          type="email"
          label="Email *"
        />

        <div class="wrapper">
          <div class="half-tablet">
            <sun-input
              ref="pass"
              v-model="form.password"
              :errors="{
                required: 'Create password.',
                type: 'Wrong password. Try again.'
              }"
              :required="true"
              type="password"
              label="Create password *"
            />
          </div>

          <div class="half-tablet">
            <sun-input
              ref="repeatPass"
              v-model="form.repeatPassword"
              :errors="{
                required: 'Repeat password.',
                match: 'Passwords don\'t match',
              }"
              :required="true"
              :match="form.password"
              type="password"
              label="Repeat password *"
            />
          </div>
        </div>

        <div>
          <sun-checkbox
            v-model="form.terms"
            :terms="true"
            text="I agree with "
            @termsOpen="$emit('openTerms')"
          />
        </div>

        <sun-button
          :icon="true"
          class="submit-btn"
          variant="white"
          text="REGISTER AND START"
        />

        <div class="bottom-text">
          <div>
            © sunrise
          </div>

          <div>
            <a
              class="terms"
              href="javascript:;"
              @click="$emit('openTerms')"
            >
              TERMS &amp; CoNDITIONS
            </a>
          </div>
        </div>
      </form>
    </div>
  </footer>
</template>

<script>
import SunInput from './inputs/SunInput';
import SunCheckbox from './inputs/SunCheckbox';
import SunButton from './partial/SunButton';

export default {
  name: 'SunFooter',

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

  methods: {
    onFormSubmit() {
      if (this.validateForm()) {
        this.appRegistration(this.form)
          .catch((errors) => {
            console.log('registration error', errors);
          });
      }
    },

    validateForm() {
      const email = this.$refs.email.validate();
      const pass = this.$refs.pass.validate();
      const repeat = this.$refs.repeatPass.validate();
      return email && pass && repeat && this.form.terms;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

.footer {
  background-color: $white;
  font-family: 'Catamaran-Light';
  background-image: url('~assets/img/footer_background_mobile.svg');
  background-repeat: no-repeat;
  background-size: cover;
  background-position: center top;
  color: $black;
  margin-top: 147px;
  padding-top: 84px;
  padding-bottom: 25px;
  text-align: center;

  @include sun-tablet {
    background-image: url('~assets/img/footer_background_tablet.svg');
  }

  @include sun-desktop {
    background-image: url('~assets/img/footer_background_desktop.svg');
  }

  @include sun-widescreen {
    background-image: url('~assets/img/footer_background_widescreen.svg');
    padding-top: 124px;
  }

  @include sun-hd {
    background-image: url('~assets/img/footer_background_hd.svg');
    padding-top: 124px;
  }

  .top-text {
    line-height: 80px;
    font-size: 32px;
    transform: translateY(-191px);

    @include sun-tablet {
      transform: translateY(calc(-107px + 20%));
      font-size: 48px;
    }

    @include sun-desktop {
      transform: translateY(0) translateX(-25px);
      font-size: 63px;
    }

    @include sun-widescreen {
      transform: none;
      font-size: 78px;
    }

    span {
      font-family: 'Catamaran-ExtraBold';
    }
  }

  .feature-title {
    font-size: 26px;
    text-transform: lowercase;

    @include sun-tablet {
      text-transform: uppercase;
    }

    @include sun-desktop {
      font-size: 32px;
    }

    @include sun-widescreen {
      font-size: 35px;
      line-height: 80px;
    }
  }

  .container {
    margin-top: -80px;

    @include sun-tablet {
      margin-top: 2%;
    }

    @include sun-desktop {
      margin-top: 7%;
    }

    .footer-form {
      color: $white;

      @include sun-tablet {
        margin: 0 auto;
        max-width: 522px;
      }

      @include sun-widescreen {
        max-width: 685px;
      }

      @include sun-hd {
        max-width: 1092px;
      }
    }
  }

  .wrapper {
    @include sun-tablet {
      display: flex;
    }
  }

  .half-tablet {
    @include sun-tablet {
      flex: none;
      width: 50%;

      &:first-child {
        padding-right: 17px;
      }

      &:last-child {
        padding-left: 17px;
      }
    }
  }

  .submit-btn {
    margin-top: 36px;
    margin-bottom: 34px;

    @include sun-tablet {
      margin-top: 59px;
      margin-bottom: 80px;
    }

    @include sun-hd {
      margin-top: 80px;
      margin-bottom: 100px;
    }
  }

  .bottom-text {
    display: flex;
    width: 100%;
    justify-content: space-between;
    font-size: 24px;
    font-family: 'Catamaran-Bold';
    opacity: 0.4;
    max-width: 285px;
    margin: auto;

    .terms {
      color: $white;
      font-size: 15px;
      text-decoration-line: underline;
      text-transform: uppercase;
    }
  }
}
</style>
