<template>
  <div v-if="me" class="card">
    <header class="card-header">
      <h1 class="card-header-title">
        <zapier-logo class="logo" />
      </h1>
    </header>
    <section class="card-content">
      <div class="content">
        <h6 class="title is-6 has-text-centered">Allow access to applications and scholarships</h6>
        <p class="has-text-centered">Zapier is asking for an access to your Sunrise data. Do you want to allow connection?</p>
        <div class="columns">
          <div class="column has-text-right">
            <form action="/oauth/authorize" method="post">
              <input type="hidden" name="_token" :value="csrfToken" />
              <input type="hidden" name="access_token" :value="$auth.getToken()" />
              <input type="hidden" name="state" :value="$route.query.state" />
              <input type="hidden" name="client_id" :value="$route.query.client_id" />
              <button type="submit" class="button is-success is-rounded">
                <span>Allow</span>
              </button>
            </form>
          </div>
          <div class="column has-text-left">
            <form action="/oauth/authorize" method="post">
              <input type="hidden" name="access_token" :value="$auth.getToken()" />
              <input type="hidden" name="_method" value="DELETE" />
              <input type="hidden" name="_token" :value="csrfToken" />
              <input type="hidden" name="state" :value="$route.query.state" />
              <input type="hidden" name="client_id" :value="$route.query.client_id" />
              <button type="submit" class="button is-danger is-rounded">
                <span>Deny</span>
              </button>
            </form>
          </div>
        </div>
      </div>
    </section>
    <footer class="card-footer">
      <div class="card-footer-item">
        <logo class="logo" />
      </div>
      <div class="card-footer-item">
        <p><c-icon icon="lock" />Your data will be safe</p>
      </div>
    </footer>
  </div>
  <div v-else class="card">
    <div class="card-header">
      <h1 class="card-header-title">
        <logo class="logo" />
      </h1>
    </div>
    <div class="card-content">
      <h3 class="title is-3 has-text-centered">
        <span>Login</span>
      </h3>
      <b-field
        :message="errors.first('email')"
        :type="errors.has('email') ? 'is-danger' : null">
        <b-input
          type="text"
          name="email"
          placeholder="E-mail"
          v-model="form.email"
          v-validate.disable="'required|email'"
        />
      </b-field>
      <b-field
        :message="errors.first('password')"
        :type="errors.has('password') ? 'is-danger' : null">
        <b-input
          type="password"
          name="password"
          placeholder="Password"
          v-model="form.password"
          v-validate.disable="'required'"
        />
      </b-field>
      <b-field>
        <button class="button is-primary is-rounded is-fullwidth" @click="onLogin">Login</button>
      </b-field>
      <div class="is-divider" data-content="OR"></div>
      <b-field class="has-text-centered">
        <google-signin :redirect="false" />
      </b-field>
    </div>
  </div>
</template>
<script>
import GoogleSignin from 'components/auth/GoogleSignin';
import ZapierLogo from 'components/auth/ZapierLogo';
import Logo from 'components/logo.vue';

export default {
  components: {
    GoogleSignin,
    ZapierLogo,
    Logo
  },
  data() {
    return {
      form: {
        email: null,
        password: null,
      },
    };
  },
  computed: {
    provider() {
      // TODO: Implement different providers.
      return 'zapier';
    },
    csrfToken() {
      return document.head.querySelector('meta[name=csrf-token]').getAttribute('content');
    }
  },
  methods: {
    onLogin() {
      this.$validator.validateAll()
        .then((result) => {
          if (result) {
            this.$store.dispatch('user/login', this.form)
              .catch(({ response }) => {
                if (response.data.error === 'invalid_credentials') {
                  this.$validator.errors.add({
                    field: 'email',
                    msg: response.data.message
                  })
                }
              });
          }
        });
    },
  },
};
</script>
<style lang="scss" scoped>
.card {
  border: none;
  border-radius: 10px;
  background: white;
  width: 340px;
  max-width: 340px;

  > .card-header {
    padding-top: 34px;
    border: none;
    > .card-header-title {
      .logo {
        margin: 0 auto;
      }
    }
  }

  > .card-content {
    border: none;
    padding: 15px 19px;
    color: #61676F;
    font-size: 15px;
    .title {
      color: #172135;
      font-size: 17px;
      font-weight: bold;
    }
    .is-divider {
      border-top: 1px solid #DCE0E5;
      &::after {
        color: #DCE0E5;
      }
    }
    &:last-child {
      padding-bottom: 34px;
    }
  }

  > .card-footer {
    border-top: 1px solid #ECECEC;
    > .card-footer-item {
      padding: 15px 30px;
      &:not(:last-child) {
        border-right: none;
      }
      &:first-child {
        justify-content: flex-start;
        .logo {
          width: 90px;
        }
      }
      &:last-child {
        font-size: 11px;
        color: #B6BECA;
        padding-left: 0;
      }
    }
  }
}
</style>
