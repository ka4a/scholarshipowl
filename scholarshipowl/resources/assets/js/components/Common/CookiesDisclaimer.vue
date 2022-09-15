<template lang="html">
  <div v-if="show" class="cookie-modal">
      <span class="ctrl ctrl_cookie" @click="hideNotification">+</span>
      <p class="cookie-modal__text">
          We use cookies to personalize content and ads, to provide social media features
          and to analyze our traffic. We also share information about your use of our site
          with our social media, advertising and analytics partners.
      </p>
      <a class="link" href="/privacy" target="_blank">Learn more</a>
  </div>
</template>

<script>
export default {
  created() {
    this.$store.dispatch("cookieDisclaimer/notify");
  },
  computed: {
    show() {
      return this.$store.state.cookieDisclaimer.show
    }
  },
  methods: {
    hideNotification() {
      this.$store.commit("cookieDisclaimer/hide");
    }
  }
};
</script>

<style lang="scss">

@import 'main/meta/variables';
@import 'main/meta/palette';
@import 'main/meta/helpers';
@import 'main/meta/typography';
@import 'main/controls-elements';

$primary: #4a4a4a;
$secondary: #4988f7;

.cookie-modal {
  position: fixed;
  bottom: -2px;
  z-index: 10000;
  left: 0;
  display: block;
  box-sizing: border-box;
  background-color: #f3f3f3;
  margin: 0 1%;
  width: 98%;
  box-shadow: 0 -3px 11px 0 rgba(0, 0, 0, 0.26);
  border-radius: 17px 17px 0 0;
  padding: 10px 43px 12px 16px;
  font-size: 10px;

  @include breakpoint($tablet) {
    margin: 0 5%;
    width: 90%;
  }

  &__text {
    display: inline;
    color: $primary;

    //reset for legacy
    @extend %padding-reset;
  }

  .ctrl {
    position: absolute;
    right: 14px;
    line-height: 0.5em;
    transform: rotate(45deg);
    -webkit-tap-highlight-color: rgba(0, 0, 0, 0);

    &_cookie {
      color: $secondary;
      font-size: 30px;
      cursor: pointer;

      &:hover {
        text-decoration: none;
        color:darken($secondary, 5);
      }
    }
  }
}
</style>
