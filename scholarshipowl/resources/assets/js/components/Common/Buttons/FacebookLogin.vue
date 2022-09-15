<template lang="html">
  <button @click="facebookClick" class="vue-modal__button btn_facebook">Continue with FACEBOOK<i class="icon icon-facebook"></i></button>
</template>

<script>
export default {
  props: {
    redirect: {
      default: null
    }
  },
  methods: {
    facebookClick() {
      window.fbAsyncInit && window.fbAsyncInit();

      window.FB.login(
        (response) => {
          if (response.status === "connected") {
            window.location = "/fb-connect/" +
              response.authResponse.accessToken +
              ( this.redirect ? "?_return=" + this.redirect : "");
          }
        },
        {
          scope: "public_profile,email"
        }
      );
    }
  }
};
</script>

<style lang="css">
</style>
