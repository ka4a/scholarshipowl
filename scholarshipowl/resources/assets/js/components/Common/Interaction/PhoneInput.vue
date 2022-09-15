<template lang="html">
  <div :class="['international-phone-input', {'international-phone-input_error': error}]">
    <input ref="input"
      autocomplete="tel"
      @input="emitValues"
      @blur="ev => { emitValues(ev); $emit('blur') }"
      @focus="ev => { emitValues(ev); $emit('focus') }"
      :name="name"
      :id="id"
      type="tel" />
  </div>
</template>

<script>
import { numeric } from "lib/utils/format";

const SOWLStorage = window.SOWLStorage || window.SOWLStorageOptimized;

let options = {
  nationalMode: false,
  initialCountry: SOWLStorage && SOWLStorage.settings
    ? SOWLStorage.settings.uc : "ca",
  preferredCountries: ["ca", "us"],
}

const limitPhoneLength = (input, phoneNumber) => {
  let placeholder = input.getAttribute("placeholder");

  if(placeholder && phoneNumber.length >= placeholder.length) {
    return phoneNumber.substring(0, phoneNumber.length - 1);
  }

  return phoneNumber;
}

export default {
  props: {
    intlTelInput: { type: Object, required: true },
    value:        { type: Object, required: true },
    name:         { type: String, required: true },
    id:           { type: String, required: true },
    error:        { type: Boolean, required: false },
    initCountry:  { type: String }
  },
  mounted: function() {
    options["dropdownContainer"] = this.$el;
    options["initialCountry"] = this.initCountry || "ca";

    this.iti = this.intlTelInput(this.$refs["input"], options);

    if(this.value) {
      this.iti.setNumber(this.value);
    }
  },
  data() {
    return {
      iti: null
    }
  },
  watch: {
    value(newVal) {
      this.iti.setNumber(newVal);
    }
  },
  methods: {
    emitValues(ev) {
      const { dialCode } = this.iti.getSelectedCountryData();

      let number = this.iti.getNumber();

      number = limitPhoneLength(this.$refs["input"], number);

      if(ev.type === "focus" && !number) {
        number = "+" + dialCode;
      }

      if(ev.type === "input") {
        number = !number ? "+" : number.replace(/[^\+\d+]/g, "")
      }

      if(ev.type === "blur") {
        let formDialCode = `+${dialCode}`;

        if(number.indexOf(formDialCode) === -1
          || number.length <= formDialCode.length) {
          number = "";
        }
      }

      this.iti.setNumber(number);

      this.$emit("input", number);

      this.emitPhoneData();
    },
    emitPhoneData() {
      const { name, iso2, dialCode } = this.iti.getSelectedCountryData();

      const data = {
        countryName: name,
        countryCode: iso2 ? iso2.toUpperCase() : iso2,
        dialCode,
        isValid: this.iti.isValidNumber()
      }

      this.$emit("phone-data", data);
    }
  }
};
</script>

<style lang="scss">
  @import './scss/intlTelInput';

  $white: #fff;
  $red: #ed5858;
  $blue: #cee2ff;
  $blue-darker: #708fe7;


  %input-text {
    font-family: $font-family-basic;
    font-size: 16px;
    line-height: 1.3em;
    color: $mine-shaft;
  }

  .international-phone-input {
    position: relative;

    .intl-tel-input {
      width: 100%;

      .selected-flag {
        width: 56px;
        padding-left: 14px;
      }
    }

    input {
      // text
      @extend %input-text;

      // appearing
      border-radius: 1px;
      padding: 14px 15px;
      border: 1px solid #e8e8e8;
      background: $white;
      width: 100%;
      box-sizing: border-box;

      &:focus {
        outline: none;
        border-color: $blue-darker;
      }
    }

    &_error {
      input {
        border-color: $red;
      }
    }

    .intl-tel-input.allow-dropdown input {
      padding-left: 63px;
    }

    .iti-container {
      top: 50px !important;
      left: 0 !important;
      width: 100% !important;
      position: absolute !important;
    }

    .flag-container {
      width: 100%;
    }

   .country-list {
      position: static;
      box-shadow: none;
      border: 1px solid #e8e8e8;
      border-top: none;
      max-height: 201px !important;
      border-radius: 0 0 2px 2px;
      overflow-x: hidden;

      .divider {
        padding: 0;
        margin: 0;
      }

      .active {
        background-color: $blue !important;
      }

      .country {
        @extend %input-text;
        padding: 14px 15px !important;
        width: 100%;
        box-sizing: border-box;
      }

      .hightlight {
        background: none;
      }
    }
  }
</style>
