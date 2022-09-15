<template>
  <div class="base-hor-indent form-wrp reg1-form">
    <div class="reg1-input-set">
      <ValidationProvider name="firstName" ref="firstName"
        :rules="{required: true}" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="First name"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :format="nameCapTwoFistLetters"
            :error="!!errors.length"
            name="firstName"
            placeholder="First name"
            autocomplete="name given-name"
            v-model="data.firstName"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="lastName" ref="lastName"
        :rules="{required: true}" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Last name"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :format="nameCapTwoFistLetters"
            :error="!!errors.length"
            name="lastName"
            placeholder="Last name"
            autocomplete="name given-name"
            v-model="data.lastName"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider name="email" ref="email"
        :rules="{required: true, email: true}" :events="['blur', 'validate']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item my-account-form__email"
          title="Email"
          :error-message="errors[0]">
          <input-text type="email"
            @focus="reset"
            @input="reset"
            :error="!!errors.length"
            name="email"
            placeholder="Email"
            autocomplete="new-password"
            v-model="data.email"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider v-if="isUSA" name="phone" ref="phone"
        :rules="{required: true, min: 16}" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Phone"
          :error-message="errors[0]">
          <input-text
            @focus="reset"
            :error="!!errors.length"
            :format="formatUSAPhoneNumber"
            type="tel"
            name="phone"
            placeholder="Phone"
            autocomplete="street-address address-line1"
            v-model="data.phone"/>
        </input-item>
      </ValidationProvider>

      <ValidationProvider v-else name="phone" ref="phone"
        :rules="{required: true, min: 10}" :events="['blur']">
        <input-item slot-scope="{ errors, reset }"
          class="my-account-form__item"
          title="Phone"
          :error-message="errors[0]">
          <international-phone
            :intlTelInput="intlTelInput"
            :error="!!errors.length"
            :init-country="data.initCountry"
            id="phone" name="phone"
            v-model="data.phone"
            @focus="reset"
            @phone-data="({countryCode}) => {
              data.countryCode = countryCode;
            }" />
        </input-item>
      </ValidationProvider>
    </div>

    <div class="reg1-form__sc-switcher sc-switcher" v-if="!isUSA">
      <h5 class="sc-switcher__title">Plan to study in the US?</h5>
      <p class="sc-switcher__wrp">
        <span class="sc-switcher__switch-label lft">No</span>
        <switch-basic v-model="data.studyInUSA" />
        <span class="sc-switcher__switch-label rght">Yes</span>
      </p>
    </div>

    <ValidationProvider v-if="!isUSA && !data.studyInUSA" name="studyCountries" ref="studyCountries"
      :events="['close', 'blur']" :rules="{required: true, array_max: 5}">
      <input-item slot-scope="{ errors, reset, validate }"
        class="reg1-form__sc-input"
        title="Where do you want to study?"
        :error-message="errors[0]">
        <input-multi-select
          @max-reached="() => {
            validate(); applyAfter(2000, reset);
          }"
          @open="reset"
          @close="validate"
          v-model="data.studyCountries"
          :error="!!errors.length"
          placeholder="Enter up to 5 countries"
          name="studyCountries"
          :min="1" :max="5"
          :options="studyCountries" />
      </input-item>
    </ValidationProvider>

    <div class="reg1-checkbox-set1">
      <check-box v-if="allowCallText" class="reg1-checkbox-set1__1" v-model="data.agreeCall" name="tcpa">
        <span slot="label" v-html="allowCallText"></span>
      </check-box>

      <p class="reg1-checkbox-set1__2">
        <ValidationProvider name="tac" ref="tac" rules="required:true">
          <check-box name="tac"
            v-model="data.agreeTerms"
            @input="showTACError = true"
            slot-scope="{ errors }"
            :error="!!errors.length">
            <terms-conditions slot="label" />
          </check-box>
        </ValidationProvider>

        <coreg-mount-point
          class="reg1-checkbox-set1__coregs"
          v-if="coregsData && 'above' in coregsData"
          @coreg="coregs => {
            setCoregs(coregs);
            saveToLocalStorage();
          }"
          :savedCoregs="data.coregs"
          :coregs="coregsData.above" />
      </p>
    </div>

    <p class="reg1-form__btn-wrp">
      <transition name="top-transform">
        <p class="reg1-form__agree-mess err-mess" v-if="this.$refs['tac'] && !this.$refs['tac'].isValid && showTACError">
          Please agree to terms and conditions to continue
        </p>
      </transition>

      <Button class="reg1-form__btn"
      :shouldHoldKeyPress="true" @click="register"
      @click.native="register" theme="orange" size="xl"
      :label="contentSet.textButton" :show-loader="isSubmitting" />
    </p>

    <coreg-mount-point
      class="reg1-checkbox-set2"
      v-if="coregsData.below && coregsData.below.length"
      :coregs="coregsData.below"
      :savedCoregs="data.coregs"
      @coreg="coregs => {
        setCoregs(coregs);
        saveToLocalStorage();
      }" />

    <p class="text5 reg1-form__disclaimer">* Details and qualifications for participation in this promotion may apply</p>
  </div>
</template>

<script>
  import Vue                          from "vue";
  import { mapGetters, mapActions,
    mapState }                        from "vuex";
  import { ValidationProvider }       from "vee-validate";
  import intlTelInput                 from "intl-tel-input";
  import { name, formatUSAPhoneNumber,
    formatNumberToSimple }            from "lib/utils/format";
  import mixpanel                     from "lib/mixpanel";
  import { REGISTER_BUTTON_CLICK }    from "lib/mixpanel";
  import { SettingsResource }         from "resource";
  import { validator }                from "components/Pages/MyAccount/SubTabs/validator";
  import registerMixin                from "components/Pages/Register/mixins";
  import InputItem                    from "components/Common/Input/InputItem.vue";
  import InputSelectBasic             from "components/Common/Input/Select/InputSelectBase.vue";
  import Button                       from "components/Common/Buttons/ButtonCustom.vue";
  import InputText                    from "components/Common/Input/Text/InputTextBasic.vue";
  import InternationalPhone           from "components/Common/Interaction/PhoneInput.vue";
  import InputMultiSelect             from "components/Common/Input/Select/InputMultiSelect.vue";
  import SwitchBasic                  from "components/Common/Switches/SwitchBasic.vue";
  import CheckBox                     from "components/Common/CheckBoxes/CheckBoxBasic.vue";
  import TermsConditions              from "components/Common/Text/TermsConditions.vue";
  import CoregMountPoint              from "components/Common/Coregs/CoregMountPoint.vue";


  const nameCapFistLetter = name(1),
        nameCapTwoFistLetters = name(2);

  const SETTINGS_REG = {
    CBOX_CALL_IS_VISIBLE: "register.checkbox.call_visible",
    CBOX_CALL_IS_CHECKED: "register.checkbox.call",
    CBOX_CALL_TEXT:       "register.checkbox.call_text"
  }

  export default {
    mixins: [validator, registerMixin],
    components: {
      ValidationProvider,
      InputItem,
      InputText,
      Button,
      InternationalPhone,
      SwitchBasic,
      InputMultiSelect,
      CheckBox,
      TermsConditions,
      CoregMountPoint,
    },
    created() {
      this.intlTelInput = intlTelInput;

      this.unloadStore.walkThroughSavedData(
        "register",
        (name, value) => Vue.set(this.data, name, value)
      )

      const requests = [
        this.getCoregs({path: "register"}),
        SettingsResource.public({fields: [
          SETTINGS_REG.CBOX_CALL_IS_VISIBLE,
          SETTINGS_REG.CBOX_CALL_IS_CHECKED,
          SETTINGS_REG.CBOX_CALL_TEXT
        ]})
      ];

      if (!this.isUSA) {
        requests.push(this.$store.dispatch("options/load", ["studyCountries"]))
        requests.push(window.intlTelInputGlobals.loadUtils("assets/js/utils.js"))
      }

      Promise.all(requests).then(results => {
        const response = results[1];

        if(response.body && response.body.status === 200) {
          const data = response.body.data;

          if(data[SETTINGS_REG.CBOX_CALL_IS_VISIBLE] === "yes") {
            if(!this.unloadStore.isIdentificatorStored('register')) {
              this.data.agreeCall = data[SETTINGS_REG.CBOX_CALL_IS_CHECKED] === 'yes';
            }

            this.allowCallText = data[SETTINGS_REG.CBOX_CALL_TEXT];
          }
        }

        this.$emit("loaded");
      })
    },
    props: {
      isSubmitting: {type: Boolean, default: false},
      contentSet: {type: Object, default: {}}
    },
    data() {
      return {
        submitting: false,
        showTACError: false,
        allowCallText: "",
        intlTelInput: null,
        data: {
          firstName: "",
          lastName: "",
          phone: "",
          email: "",
          countryCode: "",
          agreeCall: false,
          agreeTerms: false,
          studyInUSA: true,
          studyCountries: null,
          initCountry: window.SOWLStorage.settings.uc || "ca" // TODO get it from API https://scholarshipowl.atlassian.net/browse/SOWL-4004
        }
      }
    },
    watch: {
      data: {
        handler() {
          this.saveToLocalStorage();
        },
        deep: true
      }
    },
    computed: {
      ...mapGetters({
        isUSA: "account/isUSA",
      }),
      ...mapState({
        coregsData: state => state.coregs.coregsData
      }),
      ...mapGetters({
        studyCountries: "options/studyCountries"
      }),
    },
    methods: {
      nameCapFistLetter,
      nameCapTwoFistLetters,
      formatUSAPhoneNumber,
      ...mapActions({
        getCoregs: "coregs/getCoregs"
      }),
      saveToLocalStorage() {
        this.unloadStore.saveData("register", this.data);
      },
      select(value, fieldName, caller) {
        this.data[fieldName] = value;
      },
      applyAfter(time, callback) {
        if(!time || !callback) return;

        setTimeout(callback, time);
      },
      setCoregs(coregs) {
        if(!Object.keys(coregs).length) {
          delete this.data.coregs
          return;
        }

        this.data.coregs = coregs;
      },
      USAtoInternationalPhone(phoneNumber) {
        return `+1${formatNumberToSimple(phoneNumber)}`
      },
      validateAndPrepare() {
        this.showTACError = true;

        this.validateAll(this.$refs).then(valid => {
          if(!valid) {
            this.scrollToError(this.$refs, [
              "firstName",
              "lastName",
              "phone",
              "email",
              "studyCountries",
              "tac",
            ]);

            return;
          }

          let data = {
            firstName: this.data.firstName,
            lastName: this.data.lastName,
            email: this.data.email,
          }

          if(this.data.agreeCall) {
            data["agreeCall"] = this.data.agreeCall;
          }

          if(this.isUSA) {
            data["phone"] = this.USAtoInternationalPhone(this.data.phone);
            data["countryCode"] = "US";
          } else {
            data["phone"] = this.data.phone;
            data["countryCode"] = this.data.countryCode;
          }

          if(!this.data.studyInUSA && this.data.studyCountries
            && this.data.studyCountries.length) {
            data["studyCountry"] = this.data.studyCountries.map(c => c.value)
          }

          if(this.data.coregs && Object.keys(this.data.coregs).length) {
            data["coregs"] = this.data.coregs;
          }

          this.$emit("submit", data)
        })
      },
      register() {
        mixpanel.track(REGISTER_BUTTON_CLICK);

        this.validateAndPrepare();
      }
    }
  }
</script>

<style lang="scss">
  $blue-darker: #919daf;

  .reg1-form {
    &__btn-wrp {
      margin-top: 25px;

      @include breakpoint($m) {
        margin-top: 28px;
      }

      @include breakpoint($m) {
        margin-top: 28px;
      }

      @include breakpoint($l) {
        margin-top: 35px;
      }
    }

    &__btn {
      @include breakpoint($m) {
        max-width: 424px;
        margin-left: auto;
        margin-right: auto;
      }
    }

    &__disclaimer {
      margin-top: 24px;
      line-height: 1.2em;
      text-align: center;
    }

    &__messager-cb {
      margin-top: 23px;
    }

    &__agree-mess {
      text-align: center;
      width: 100%;

      margin-bottom: 25px;

      @include breakpoint($s) {
        margin-bottom: 20px;
      }

      @include breakpoint($m) {
        margin-bottom: 15px;
      }
    }

    &__sc-switcher {
      margin-top: 25px;
    }

    &__sc-input {
      margin-top: 25px;

      @include breakpoint($m) {
        margin-top: 15px;
      }
    }
  }

  .sc-switcher {
    display: flex;
    justify-content: center;
    align-items: center;
    flex-direction: column;

    &__title {
      font-size: 16px;
      font-weight: bold;
      text-align: center;
      color: $blue-darker;

      margin-bottom: 12px;

      @include breakpoint($m) {
        font-size: 18px;
        margin-bottom: 14px;
      }
    }

    &__wrp {
      display: flex;
      align-items: center;
    }

    &__switch-label {
      font-size: 16px;
      font-weight: 600;
      color: $blue-bayoux;

      @include breakpoint($m) {
        font-size: 18px;
      }

      &.lft {
        margin-right: 13px;

        @include breakpoint($m) {
          margin-right: 16px;
        }
      }

      &.rght {
        margin-left: 13px;

        @include breakpoint($m) {
          margin-left: 16px;
        }
      }
    }
  }

  .reg1-input-set {
    display: grid;
    grid-template-columns: 1fr;
    grid-column-gap: 28px;
    grid-row-gap: 14px;
    box-sizing: border-box;
    width: 100%;
    margin-right: auto;
    margin-left: auto;

    @include breakpoint($m) {
      grid-row-gap: 20px;
      grid-template-columns: 1fr 1fr;
    }

    @include breakpoint($l) {
      grid-template-columns: 1fr 1fr 1fr 1fr;
    }
  }

  .reg1-checkbox-set1 {
    position: relative;
    margin-top: 25px;

    @include breakpoint($m $l - 1px) {
      margin-top: 23px;
    }

    @include breakpoint($l) {
      display: flex;
    }

    &__1 {
      @include breakpoint($l) {
        width: 48.5%;
      }
    }

    &__2 {
      @include breakpoint(max-width $l - 1px) {
        margin-top: 14px;
      }

      @include breakpoint($l) {
        width: 48.5%;
        margin-left: 3%;
      }

      .coreg-basic +
      .coreg-basic {
        margin-top: 14px;
      }
    }

    &__coregs {
      margin-top: 14px;
    }
  }

  .reg1-checkbox-set2 {
    display: grid;
    grid-template-columns: 1fr;
    grid-row-gap: 14px;

    margin-top: 45px;

    @include breakpoint($m $l - 1px) {
      margin-top: 23px;
    }

    @include breakpoint($l) {
      grid-column-gap: 20px;
      grid-template-columns: 1fr 1fr;
    }
  }
</style>