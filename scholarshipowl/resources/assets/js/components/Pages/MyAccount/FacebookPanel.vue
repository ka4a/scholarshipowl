<template>
  <section class="facebook-panel">
    <h5 class="ma-title facebook-panel__title">Social accounts</h5>
    <div class="facebook-panel__paragraph">
      <logo-facebook class="ma-facebook-logged__logo" width="35px" height="35px" />
      <template style="margin-left: 20px; margin-right: 10px;">
        <p v-if="socialAccount" class="ma-text ma-facebook-logged__text">
          <span>Connected to Facebook</span>
          <a :href="socialAccount.link">{{ socialAccount.link }}</a>
        </p>
        <p v-else class="ma-text ma-facebook-logged__text">Not connected to Facebook</p>
      </template>
    </div>
    <p class="facebook-panel__paragraph">
      <switch-basic @change="facebookSwitchChange" :checked="socialAccount" />
      <span style="margin-left: 10px;" class="ma-text ma-facebook-logged__text">Use your Facebook account to&nbsp;log&nbsp;in</span>
    </p>
    <a ref="facebookLink" style="display: none" href="/rest/v1/account/link-facebook">link facebook</a>
  </section>
</template>

<script>
  import { mapGetters } from "vuex";
  import LogoFacebook from "components/Pages/MyAccount/LogoFacebook.vue";
  import SwitchBasic from "components/Common/Switches/SwitchBasic.vue";

  export default {
    components: {
      LogoFacebook,
      SwitchBasic
    },
    computed: {
      ...mapGetters({
        socialAccount: 'account/socialAccount'
      })
    },
    methods: {
      facebookSwitchChange(switched) {
        switched
          ? this.facebookLink()
          : this.facebookUnlink()
      },
      facebookLink(redirectPage) {
        if(!redirectPage || typeof redirectPage !== 'string') {
          redirectPage = 'my-account#credentials';
        }

        this.$refs.facebookLink.href = `/rest/v1/account/link-facebook?redirect=${encodeURIComponent(redirectPage)}`;
        this.$refs.facebookLink.click();
      },
      facebookUnlink() {
        this.$http.delete('/rest/v1/account/unlink-facebook')
          .then(response => {
            this.$store.dispatch('account/updateField', {
              fieldName: 'socialAccount',
              data: null
            });
          })
          .catch(err => {
            alert('Something went wrong. Please try late');
          })
      },
    }
  }
</script>

<style lang="scss">
  .ma-facebook-logged {
    margin-top: 17px;
    display: flex;
    flex-wrap: wrap;

    &__logo {
      margin-right: 19px;
      margin-left: 6px;
    }

    &__text {
      flex: 1 1 80%;
      min-width: 75%;

      span {
        display: block;
      }

      a {
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
      }
    }
  }

  .facebook-panel {
    max-width: 904px;
    margin-left: auto;
    margin-right: auto;

    &__paragraph {
      display: flex;
      align-items: center;
      margin-top: 17px;
    }
  }
</style>
