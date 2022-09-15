import VeeValidate from "vee-validate";
import en from "./dict/en.js";
import capitalize from "lodash/capitalize";

VeeValidate.Validator.localize({ en },"en");

VeeValidate.Validator.extend("checked", {
  getMessage: field => capitalize(`${field} must be checked.`),
  validate: value => !!value
});

VeeValidate.Validator.extend("array_max", {
  getMessage: (field,[size]) => `You can select maximum ${size} items`,
  validate: (value,[size]) => {
    if (!value || !value.length) {
      return true;
    }

    return value.length <= size;
  }
});

VeeValidate.Validator.extend("array_min", {
  getMessage: (field,[size]) => `Please select at least ${size} ${field}`,
  validate: (value,[size]) => {
    if (!value || !value.length) {
      return false;
    }

    return value.length >= size;
  }
});

VeeValidate.Validator.extend("true_values_array_min", {
  getMessage: (field,[size]) => { return `Please enter at least ${size} ${field} name`},
  validate: (value,[size]) => {
    console.log(value);

    if (!value || !value.length) {
      return false;
    }

    let values = value.filter(item => !!item.label);

    return values.length >= size;
  }
});

VeeValidate.Validator.extend("age", {
  getMessage: (field,[years]) => `You must be ${years} years old`,
  validate: (v, [years]) => {
    function _calculateAge(birthday) {
      var ageDate = new Date(Date.now() - birthday.getTime());
      return Math.abs(ageDate.getUTCFullYear() - 1970);
    }

    return _calculateAge(v) >= years;
  }
});

VeeValidate.Validator.extend("date", {
  getMessage: field => capitalize(`${field} must be valid date.`),
  validate: v => !!new Date(v)
});

VeeValidate.Validator.extend("select", {
  getMessage: field => `Please select ${field}`,
  validate (v) {
    const promise = new Promise((resolve) => {
      let valid = !!v;
      let value = v;

      if (v !== null && typeof v === "object" && v.hasOwnProperty("value")) {
        valid = !!v.value;
        value = v.value;
      }

      return resolve({ valid, value });
    });

    /* eslint-disable no-console */
    promise.catch(error => {
      console.log("error inside validte promise", error);
    });
    /* eslint-enable no-console */

    return promise;
  }
});

VeeValidate.Validator.extend("phone_input", {
  getMessage: "Please enter a valid phone number",
  validate: (value) => {
    if (!value) {
      return false;
    }

    let phone = value.phone || value.value;

    return !!(
      phone &&
      value.countryCode &&
      phone.length >= 10 &&
      phone.length <= 16
    );
  }
});

export default VeeValidate;