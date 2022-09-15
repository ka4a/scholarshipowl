<template>
  <div class="my-account-form">
   <ValidationProvider name="phone" ref="phone"
    :rules="{required: !!profile.phone, min: isUSA ? 14 : 10}" :events="['blur']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      title="Phone"
      :error-message="errors[0]">
      <input-text v-if="isUSA"
        @focus="reset"
        :error="!!errors.length"
        :format="formatUSAPhoneNumber"
        type="tel"
        name="phone"
        placeholder="Phone"
        autocomplete="street-address address-line1"
        v-model="data.phone"/>
      <international-phone v-else
        id="phone"
        name="phone"
        :intlTelInput="intlTelInput"
        :error="!!errors.length"
        v-model="data.phone.value"
        @phone-data="({countryCode, countryName}) => {
          data.country.name = countryName;
          data.phone.countryCode = countryCode;
        }" />
    </input-item>
  </ValidationProvider>

  <ValidationProvider name="zip" ref="zip"
    :rules="isUSA ? {required: !!profile.zip, min: 5} : {required: !!profile.zip}"
    :events="['blur']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      :title="isUSA ? 'Zip code' : 'Zip/Postal code'"
      :error-message="errors[0]">
      <input-text v-if="isUSA"
        @focus="reset"
        :error="!!errors.length"
        :format="formatZip"
        :type="isUSA ? 'tel' : 'text'"
        name="zip"
        placeholder="Zip code"
        autocomplete="postal-code"
        v-model="data.zip"/>
      <input-text v-else
        @focus="reset"
        :format="alphaNumeric"
        :error="!!errors.length"
        name="zip"
        placeholder="Zip/Postal code"
        autocomplete="postal-code"
        v-model="data.zip"/>
    </input-item>
  </ValidationProvider>

  <ValidationProvider name="city" ref="city"
    :rules="{required: !!profile.city}"
    :events="['blur']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      title="City"
      :error-message="errors[0]">
      <input-text
        @focus="reset"
        :error="!!errors.length"
        name="city"
        placeholder="City"
        autocomplete="postal-code"
        v-model="data.city"/>
    </input-item>
  </ValidationProvider>

  <ValidationProvider v-if="isUSA" name="state"
    :rules="{required: !!profile.state}"
    ref="state" :events="['blur']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      title="State" :error-message="errors[0]">
      <input-select-basic
        @open="reset()"
        :error="!!errors.length"
        name="state"
        :options="states"
        v-model="data.state" />
    </input-item>
  </ValidationProvider>

  <ValidationProvider v-if="!isUSA" name="stateName" ref="stateName"
    :rules="{required: !!profile.stateName}"
    :events="['blur']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      title="State/Province/Region"
      :error-message="errors[0]">
      <input-text
        @focus="reset"
        :error="!!errors.length"
        name="stateName"
        placeholder="State/Province/Region"
        autocomplete="address-level3"
        v-model="data.stateName"/>
    </input-item>
  </ValidationProvider>

  <ValidationProvider name="address" ref="address"
    :rules="{required: !!profile.address}"
    :events="['blur']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      title="Address"
      :error-message="errors[0]">
      <input-text
        @focus="reset"
        :error="!!errors.length"
        :format="alphaNumeric"
        name="stateName"
        :placeholder="isUSA ? 'Address' : 'Street address, P.O box, company name'"
        autocomplete="address-level4"
        v-model="data.address"/>
    </input-item>
  </ValidationProvider>

  <ValidationProvider v-if="!isUSA" name="address2" ref="address2"
    :rules="{required: !!profile.address2}"
    :events="['blur']">
    <input-item slot-scope="{ errors, reset }"
      class="my-account-form__item"
      :error-message="errors[0]">
      <input-text
        @focus="reset"
        :error="!!errors.length"
        :format="alphaNumeric"
        name="address2"
        placeholder="Apartment, suite, unit, building, floor, etc."
        autocomplete="address-level4"
        v-model="data.address2"/>
    </input-item>
  </ValidationProvider>

  <input-item v-if="!isUSA"
    class="my-account-form__item" title="Country">
    <input-text
      :value="data.country.name"
      disabled="disabled"/>
  </input-item>

  <Button class="my-account-form__submit" @click.native="submit" theme="orange" size="l" label="SAVE CHANGES" />
  </div>
</template>

<script>
  import { mapGetters } from "vuex";
  import { formatNumberToSimple, formatUSAPhoneNumber, alphaNumeric, numeric, formatZip } from "lib/utils/format";
  import intlTelInput from "intl-tel-input";
  import { ValidationProvider } from "vee-validate";
  import InternationalPhone  from "components/Common/Interaction/PhoneInput.vue";
  // TODO check phone input on register1 step
  // TODO fix bugs on mobile registration flow
  // TODO check how save state for non-us users
  import InputSelectBasic from "components/Common/Input/Select/InputSelectBase.vue";
  import InputText from "components/Common/Input/Text/InputTextBasic.vue";
  import InputItem from "components/Common/Input/InputItem.vue";
  import Button from "components/Common/Buttons/ButtonCustom.vue";
  import { validator } from "components/Pages/MyAccount/SubTabs/validator";

  const prepareSendData = (fields, data) => {
    let dataFiltered = {}

    fields.forEach(key => {
      if(data[key] && typeof data[key] !== 'object') {
        dataFiltered[key] = data[key];
        return;
      }

      if(data[key] !== null) {
        if(data[key].value) {
          dataFiltered[key] = data[key].value;
          return;
        }

        if(data[key].text) {
          dataFiltered[key] = data[key].text;
        }
      }
    })

    return dataFiltered;
  }

  const initValuesFileds = {
    phone: '',
    zip: null,
    city: null,
    address: '',
  }

  export default {
    name: "contact-sub-tab",
    mixins: [validator],
    components: {
      InternationalPhone,

      ValidationProvider,
      InputSelectBasic,
      InputItem,
      InputText,
      Button
    },
    created() {
      this.$store.dispatch("options/load", ["states"]);

      this.data.zip =       this.profile.zip || null;
      this.data.city =      this.profile.city || null;
      this.data.address =   this.profile.address || '';
      this.data.address2 =  this.profile.address2 || '';
      this.data.stateName = this.profile.stateName || '';
      this.data.country =   this.profile.country || null;
      this.data.state =     this.profile.state && this.isUSA
        ? {label: this.profile.state.name, value: this.profile.state.id}
        : null;

      if(this.isUSA) {
        this.data.phone = this.profile.phone
          ? formatUSAPhoneNumber(numeric(this.profile.phone))
          : null
      } else {
        this.data.phone = this.profile.phone
          ? {value: `+${numeric(this.profile.phone)}`}
          : null;

        this.intlTelInput = intlTelInput;

        window.intlTelInputGlobals.loadUtils("assets/js/utils.js")
      }
    },
    props: {
      profile: {type: Object, required: true},
      validationErrors: {type: Object, required: true},
      submiting: {type: Boolean, default: false}
    },
    watch: {
      validationErrors(errors) {
        if(!errors) return;

        Object.keys(errors).forEach(key => this.errors.add({
          field: key,
          msg: errors[key][0]
        }));
      }
    },
    data() {
      return {
        data: {
          ...initValuesFileds,
        }
      }
    },
    computed: {
      ...mapGetters({
        isUSA: "account/isUSA",
        states: "options/states",
        isMember: "account/isMember",
      }),
    },
    methods: {
      formatNumberToSimple,
      formatUSAPhoneNumber,
      alphaNumeric,
      formatZip,
      select(value, fieldName, caller) {
        console.log(value, fieldName, caller);
        this.data[fieldName] = value;
      },
      formatInitValue(value) {
        if(!value) return null;

        let keys = Object.keys(value);

        return {label: value[keys[1]], value: value[keys[0]].toString()}
      },
      submit() {
        if(this.submiting) return;

        this.validateAll().then(valid => {
          if(!valid) {
            this.scrollToError();
            return;
          }

          let fields = Object.keys(initValuesFileds),
              data = prepareSendData(fields, this.data);

          if(this.isUSA) {
            data["phone"] = this.data.phone;

            if(this.data.state) {
              data["state"] = this.data.state.value;
            }

            if(this.data.phone) {
              data["phone"] = this.data.phone;
            }
          } else {
            data = {
              ...data,
              ...prepareSendData(
                ["stateName",
                "address2"],
                this.data)
            }
            if(this.data.phone && this.data.phone.value) {
              data["phone"] = formatNumberToSimple(this.data.phone.value);
            }

            data["countryCode"] = this.data.phone.countryCode;
          }

          if(!Object.keys(data).length) return;

          this.$emit('submit', data);
        })
      }
    }
  }
</script>

<style lang="scss">
  .my-account-form {
    position: relative;

    &__address2 {
      @include breakpoint($m) {
        margin-top: 19px;
      }
    }
  }
</style>