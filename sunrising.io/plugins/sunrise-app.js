import Vue from 'vue';

const { appURL } = process.env;

const parseJsonApiErrors = (raw) => {
  const errors = {};

  if (Array.isArray(raw)) {
    raw.forEach((error) => {
      if (!error.code || error.code !== 'validation') {
        return;
      }

      if (!error.source || !error.source.pointer) {
        return;
      }

      if (!Array.isArray(error.detail)) {
        return;
      }

      errors[error.source.pointer] = error.detail.join(' ');
    });
  }

  return errors;
};

Vue.mixin({
  computed: {

    /**
     * Sunrise application login URL.
     */
    loginURL: () => `${appURL}/login`,

    /**
     * Sunrise application login URL.
     */
    registrationURL: () => `${appURL}/registration`,

    /**
     * A URL to schedule a demo
     */
    demoURL: () => 'https://calendly.com/sowl/30min',

  },
  methods: {

    /**
     * Register user method.
     * On success registration user will be redirected to Sunrise Application.
     *
     * Errors should be resolved with `catch` method.
     * Will return object with errors where key will be the field with error.
     * Example:
     * {
     *   email: "The email has already been taken."
     *   password: "The password must be at least 6 characters."
     * }
     *
     * @param {Object} form
     * @param {String} form.email - User email
     * @param {String} form.password - User password
     */
    appRegistration(form) {
      return this.$axios.post('/auth/registration', form)
        .then(({ data }) => {
          if (!data || !data.access_token) {
            throw new Error('Ops something went wrong. Try later.');
          }

          window.location = `${appURL}/login?access_token=${data.access_token}`;
        })
        .catch((err) => {
          if (err.response.data && Array.isArray(err.response.data.errors)) {
            throw parseJsonApiErrors(err.response.data.errors);
          }

          throw err;
        });
    },

  },
});
