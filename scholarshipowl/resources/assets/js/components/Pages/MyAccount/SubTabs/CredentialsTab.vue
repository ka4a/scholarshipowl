<template>
  <div class="credentials-tab">
    <div class="my-account-form">
      <ValidationProvider name="email" ref="email"
        :rules="{required: !!email, email: true}" :events="['blur', 'validate']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item my-account-form__email"
          title="Email"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            @input="reset"
            :error="!!errors.length"
            name="email"
            placeholder="Email"
            autocomplete="email"
            v-model="data.email"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="password" ref="password"
        rules="min:6" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Password"
          :error-message="errors[0]">
          <input-text
            type="password"
            @focus="reset"
            :error="!!errors.length"
            name="password"
            placeholder="Password"
            autocomplete="new-password"
            v-model="data.password"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="repassword" ref="repassword"
        rules="min:6" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Confirm password"
          :error-message="errors[0]">
          <input-text
            type="password"
            @focus="reset"
            :error="!!errors.length"
            name="repassword"
            placeholder="Confirm password"
            autocomplete="new-password"
            v-model="data.repassword"/>
        </input-item>
      </ValidationProvider>

      <Button class="my-account-form__submit" @click.native="submit" theme="orange" size="l" label="SAVE CHANGES" />
    </div>
    <facebook-panel class="credentials-tab__facebook-panel" />
  </div>
</template>

<script>
  import { ValidationProvider } from "vee-validate";
  // validaetAll, showErrors
  import { validator } from "components/Pages/MyAccount/SubTabs/validator";
  import InputItem from "components/Common/Input/InputItem.vue";
  import InputText from "components/Common/Input/Text/InputTextBasic.vue";
  import FacebookPanel from "components/Pages/MyAccount/FacebookPanel.vue";
  import Button from "components/Common/Buttons/ButtonCustom.vue";

  export default {
    name: "credentials-sub-tab",
    mixins: [validator],
    components: {
      FacebookPanel,
      ValidationProvider,
      InputItem,
      InputText,
      Button
    },
    created() {
      if(this.email) {
        this.data.email = this.email;
      }

      window.v = this;
    },
    props: {
      email: {type: String, required: true},
      validationErrors: {type: Object, required: true},
      submiting: {type: Boolean, default: false},
    },
    data() {
      return {
        checked: true,
        disabled: true,
        data: {
          email: null,
          password: null,
          repassword: null,
        },
        submiting: false,
      }
    },
    methods: {
      addRePassError(errors) {
        if(errors && errors.password
          && typeof Array.isArray(errors.password)) {
          errors['repassword'] = errors['password'];
        }

        return errors;
      },
      submit() {
        if(this.submiting) return;

        this.validateAll().then(valid => {
          if(!valid) {
            this.scrollToError();
            return;
          }

          let data = {}

          if(this.data.email) data["email"] = this.data.email;
          if(this.data.password) data["password"] = this.data.password;
          if(this.data.repassword) data["password_confirmation"] = this.data.repassword;

          this.submiting = true;

          if(!Object.keys(data).length) return

          this.$http.post("/post-account", data)
            .then(response => {
              if(response.status === 200) {
                this.submiting = false;

                if(response.body.status === "ok") {
                  this.$emit('updated');
                }

                if(response.body.status === "error") {
                  this.showErrors(this.addRePassError(response.body.data));
                  this.scrollToError();
                }
              }
            })
            .catch((response) => {
              this.submiting = false;

              if (response.body && response.body.error) {
                this.showErrors(this.addRePassError(response.body.error));
              }
            });
        })
      }
    }
  }
</script>

<style lang="scss">
  .credentials-tab {
    width: 100%;
    max-width: 664px;
    margin-left: auto;
    margin-right: auto;

    &__title {
      margin-top: 20px;

      @include breakpoint($s) {
        margin-top: 25px;
      }

      @include breakpoint($m) {
        margin-top: 30px;
      }
    }

    &__text {
      margin-top: 10px;

      @include breakpoint($s) {
        margin-top: 17px;
      }
    }

    &__facebook-panel {
      margin-top: 25px;
    }

    .my-account-form {
      &__email {
        grid-column: 1 / -1;

        @include breakpoint($m) {
          width: 48%;
        }

        @include breakpoint($l) {
          width: 47%;
        }
      }
    }
  }
</style>