import Vue from 'vue';
import VueAuthenticate from 'vue-authenticate';

export default function generateAuth() {
  return VueAuthenticate.factory(Vue.prototype.$http, {

    providers: {
      google: {
        clientId: GOOGLE_CLIENT_ID,
        redirectUri: `${window.location.origin}/auth/google`,
      },
    },

    loginUrl: '/auth/login',
    logoutUrl: '/auth/logout',
    registerUrl: '/auth/registration',
    tokenName: 'access_token',
    tokenType: 'Bearer',

    bindRequestInterceptor: function () {
      this.$http.interceptors.request.use((config) => {
        if (this.isAuthenticated()) {
          config.headers['Authorization'] = [
            this.options.tokenType, this.getToken()
          ].join(' ')
        } else {
          delete config.headers['Authorization']
        }
        return config
      })
    },

    bindResponseInterceptor: function () {
      this.$http.interceptors.response.use(
        (response) => {
          this.setToken(response)
          return response
        },
        (error) => {
          if (this.isAuthenticated()) {
            if (error.response && error.response.status === 401) {
              this.logout()//.then(() => { window.location = '/' })
              return;
            }
          }

          throw error;
        }
      )
    },
  });
};
