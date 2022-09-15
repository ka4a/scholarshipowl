<template>
  <div class="password-reset-page">
    <h1 class="title">
      <span>Create new password</span>
    </h1>
    <div class="reset-password-form">

      <form @submit.prevent="onSubmit">

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

          v-validate="'required|max:255'"
          data-vv-validate-on="blur"
          data-vv-as="password confirm"
          :error="errors.first('passwordConfirm')"

          v-model="form.password_confirmation"/>

        <p v-if="expired" class="error-expired has-text-danger">
          <span>Reset token was expired or used please request</span>
          <router-link :to="{ name: 'login' }">
            <strong>reset password</strong>
          </router-link>
          <span>one more time.</span>
        </p>

        <b-field>
          <brand-button type="submit" is-red>
            <span>SAVE NEW PASSWORD</span>
          </brand-button>
        </b-field>

      </form>

      <b-modal class="modal-success is-small" :canCancel="false" :active="successModal">
        <div class="box has-text-centered">
          <h3 class="modal-title">Your password have been changed!</h3>
          <router-link :to="{ name: 'login' }">
            <brand-button class="button--login" is-red>
              <span>LOGIN</span>
            </brand-button>
          </router-link>
        </div>
      </b-modal>

    </div>
  </div>
</template>
<script>
import BrandButton from 'components/brand/button';
import BrandInput from 'components/brand/input';

export default {
  components: {
    BrandButton,
    BrandInput,
  },
  data: function() {
    return {
      expired: false,
      successModal: false,
      form: {
        token: this.$route.params.token,
        password: null,
        password_confirmation: null,
      }
    }
  },
  methods: {
    onSubmit() {
      this.$validator.validateAll()
        .then((result) => {
          if (result) {
            this.$http.post('/auth/password/reset', this.form)
              .then(() => {
                this.successModal = true;
              })
              .catch(() => {
                this.expired = true;
              })
          }
        });
    }
  }
}
</script>
<style lang="scss">
.password-reset-page {
  .error-expired {
    margin-bottom: 30px;
  }
  .button--login {
    > span {
      width: 168px;
    }
  }
}
</style>
