export default {
  props: {
    serverFieldErrors: {type: Object, default: null},
  },
  watch: {
    serverFieldErrors(errors) {
      let errorFieldNames = Object.keys(errors);

      if(errorFieldNames.indexOf("password") > -1) {
        errors["repassword"] = errors["password"];
        errorFieldNames = Object.keys(errors);
      }

      errorFieldNames.forEach(errorFieldName => {
        if(!this.$refs[errorFieldName]) return;

        setTimeout(() => {
          this.$refs[errorFieldName].applyResult({
            errors: errors[errorFieldName],
            valid: false,
            failedRules: {}
          });

          this.scrollToError(this.$refs);
        })
      })
    },
  }
}