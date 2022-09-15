<template>
  <div class="contact-us-form">
    <div class="contact-us-form__input-wrp">
      <ValidationProvider name="name" ref="name"
        rules="required" :events="['none']">
        <input-item slot-scope="{ errors, reset }"
          class="contact-us-form__item"
          title="Your name"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :error="!!errors.length"
            name="name"
            autocomplete="name given-name"
            v-model="name"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="email" ref="email"
        :rules="{required: true, email: true}" :events="['none']">
        <input-item slot-scope="{ errors, reset }"
          class="contact-us-form__item"
          title="Your email"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :error="!!errors.length"
            name="email"
            type="email"
            autocomplete="email"
            v-model="email"/>
        </input-item>
      </ValidationProvider>
    </div>

    <ValidationProvider name="question" ref="question"
      rules="required" :events="['none']">
      <input-item slot-scope="{ errors, reset }"
        class="contact-us-form__item"
        title="Your question"
        :error-message="errors[0]">
        <input-text-area
          ref="textarea"
          :class="['contact-us-form__textarea']"
          :error="!!errors.length"
          @focus="reset"
          name="question"
          v-model="question"/>
      </input-item>
    </ValidationProvider>
    <Btn @click.native="send" :isLoading="isLoading"
      class="contact-us-form__btn" text="send" />
  </div>
</template>

<script>
  import { mapActions } from "vuex";
  import { SENT_MESSAGE } from "store/modal";
  import { validator } from "components/Pages/MyAccount/SubTabs/validator"; // TODO Move it to common mixins
  import { ValidationProvider } from "vee-validate";
  import InputText from "components/Common/Input/Text/InputTextBasic.vue";
  import InputTextArea from "components/Common/Input/InputTextArea.vue";
  import InputItem from "components/Common/Input/InputItem.vue"
  import Btn from "components/Common/Contact/Btn.vue";

  const contentFabrica = (title, text) => ({title, text});

  const success = contentFabrica(
    "Message has been sent!",
    ["We'll get back to you within 24hr"]
  )

  const fail = contentFabrica(
    "Something went wrong.",
    ["Please try again later."]
  )

  export default {
    mixins: [validator],
    components: {
      ValidationProvider,
      InputText,
      InputItem,
      InputTextArea,
      Btn
    },
    created() {
      this.setInitialData();
    },
    props: {
      pageName: {type: String, required: true},
      profile: {type: Object},
      account: {type: Object}
    },
    data() {
      return {
        name: '',
        phone: '',
        email: '',
        question: '',
        isLoading: false
      }
    },
    methods: {
      ...mapActions("modal", ["showModal"]),
      setInitialData() {
        this.name = this.profile && this.profile.fullName;
        this.phone = this.profile && this.profile.phone;
        this.email = this.account && this.account.email;
      },
      send() {
        this.validateAll(this.$refs).then(valid => {
          if(!valid || this.isLoading) return;

          let data = {
            name: this.name,
            email: this.email,
            content: this.question,
            phone: this.phone
          }

          this.$http.post(`/rest/v1/contact-form/${this.pageName}`, data)
            .then(response => {
              this.isLoading = false;

              let that = this;

              this.showModal({
                modalName: SENT_MESSAGE,
                content: success,
                hooks: {
                  after: () => {
                    this.question = "";
                    this.$refs['textarea'].$el.value = "";
                    this.$refs['question'].reset();

                    this.setInitialData();
                  }
                }
              })
            })
            .catch(response => {
              this.isLoading = false;

              this.showModal({ modalName: SENT_MESSAGE, content: fail })
            })

          this.isLoading = true;
        })
      }
    }
  }
</script>

<style lang="scss">
  .contact-us-form {
    &__item {
      position: relative;

      @include breakpoint(max-width $m - 1px) {
        h4 {
          font-size: 15px;
        }

        & + & {
          margin-top: 25px;
        }
      }

      @include breakpoint($m) {
        flex: 1 1 auto;

        & + & {
          margin-left: 25px;
        }
      }

      .input-item__error {
        position: absolute;
        bottom: -15px;
      }
    }

    &__input-wrp {
      margin-bottom: 25px;

      @include breakpoint($m) {
        display: flex;
        align-items: center;
      }
    }

    &__textarea {
      min-height: 104px;
      resize: none;

      @include breakpoint($m) {
        min-height: 96px;
      }
    }

    &__btn {
      margin-top: 20px;

      // @include breakpoint($m) {
      //   margin-top: 20px;
      // }

      @include breakpoint($m $l - 1px) {
        margin-left: auto;
        margin-right: auto;
        display: block;
      }
    }
  }
</style>