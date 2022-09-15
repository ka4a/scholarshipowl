import { scroll } from "lib/utils/dom.js";

export let validator = {
  methods: {
    /**
     * Walk through the all object properties and call validate method
     * wait untill all returned promissed will be resolved and
     * return validation state
     *
     * @param {Object} fields object with object of objects with validate
     * method which return promiss with boolean validation status in resolve
     *
     * @return {Boolean} true if all fields are valid / false if
     * one of resolved value is not valid
     */
    validateAll(fields) {
      return new Promise((resolve, reject) => {
        fields = fields || this.$refs;

        if(!fields) throw Error("Please provide fields for validation");

        let fieldPromises = [];

        for(let key in fields) {
          if(fields[key] && fields[key].validate) {
            fieldPromises.push(key);
          }
        }

        Promise.all(fieldPromises.map(name => {
          return fields[name].validate();
        }))
        .then(result => {
          let isValid = true;

          result.forEach(validResult => {
            if(validResult !== null && !validResult.valid) {
              isValid = false;
            }
          })

          resolve(isValid);
        })
        .catch(err => reject(err))
      })
    },
    showErrors(errors) {
      if(!errors || typeof errors !== "object")
        throw Error("Please provide correct errors object");

      Object.keys(errors).forEach(key => {
        if(this.$refs[key] && this.$refs[key].messages
          && Array.isArray(this.$refs[key].messages)) {
          this.$refs[key].messages.push(errors[key][0]);
        }
      })
    },
    scrollToError(fields, priorityList) {
      fields = fields || this.$refs;

      let refList = priorityList || Object.keys(fields);

      if(!refList.length) return;

      for(let i = 0; i < refList.length; i += 1) {
        let refName = refList[i];

        if(fields[refName] && fields[refName].messages
          && fields[refName].messages.length) {
          scroll(fields[refName].$el);
          break;
        }
      }
    }
  }
}