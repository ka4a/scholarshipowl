<template lang="html">
  <section class="notification-freemium">
    <slot name="counter"></slot>
    <div class="notification-freemium__btn-wrp">
      <button @click="upgrade()" class="notification-freemium__btn">{{ upgradeButtonText }}</button>
      <button @click="getVip()" class="notification-freemium__btn btn-vip">{{ vipButtonText }}</button>
    </div>
    <div class="notification-freemium__banner-wrp">
      <banner1 />
      <banner2 class="notification-freemium__banner-2" />
    </div>
    <div class="notification-freemium__adds">
      <p>Want to submit unlimited applications and remove ads?</p>
      <p><span @click="upgrade()">Upgrade</span> to one of our memberships today!</p>
    </div>
  </section>
</template>

<script>
import mixpanel from "lib/mixpanel";
import Banner1 from "banners/FreemiumScholarshipsApplicationSentBanner1.vue";
import Banner2 from "banners/FreemiumScholarshipsApplicationSentBanner2.vue";

export default {
  name: "FreemiumNotification",
  components: {
    Banner1,
    Banner2,
  },
  props: {
    upgradeButtonText: { type: String, required: true },
    vipButtonText: { type: String, required: true },
    upgradeBlockText: { type: String, required: true }
  },
  methods: {
    upgrade() {
      mixpanel.track("Application Sent - Upgrade click", null, () => {
        window.location = "/secure-upgrade";
      });
    },
    getVip() {
      mixpanel.track("Application Sent - Get VIP click", null, () => {
        window.location = "/secure-upgrade";
      });
    },
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/functions';

$dark: #2F2F2F;
$white: #FFFFFF;
$orange: #FF6633;
$green: #2FD9B3;
$blue: #B9D7FA;

.notification-freemium {
  &__btn-wrp {
    margin-top: 30px;
    max-width: 380px;
    margin-left: auto;
    margin-right: auto;

    @include breakpoint($s) {
      @include flexbox();
      @include justify-content(space-between);
    }

    @include breakpoint($m) {
      max-width: 460px;
    }

    @include breakpoint($l $xl - 1px) {
      max-width: 447px;
    }
  }

  &__btn {
    color: $white;
    font-size: 14px;
    text-transform: uppercase;
    text-align: center;
    line-height: 50px;
    font-weight: 700;

    height: 50px;
    width: 250px;
    background-color: $orange;
    border-radius: 2px;
    box-sizing: border-box;

    &.btn-vip {
      background-color: $green;
      @include breakpoint(max-width $s - 1px) {
        margin-top: 17px;
      }
    }

    @include breakpoint($s) {
      width: 180px;
    }

    @include breakpoint($m) {
      width: 220px;
    }
  }

  &__banner-wrp {
    margin-top: 30px;

    iframe {
      background-color: $blue;
    }

    @include breakpoint($m) {
      @include flexbox();
      @include justify-content(space-between);
      max-width: 620px;
      margin-left: auto;
      margin-right: auto;
    }

    @include breakpoint($l $xl - 1px) {
      max-width: 607px;

      @at-root {
        .right-container__inner.freemium {
          padding-left: 14px;
          padding-right: 14px;
        }
      }
    }
  }

  &__banner-2 {
    @include breakpoint(max-width $m - 1px) {
      margin-top: 17px;
    }
  }

  &__adds {
    margin-top: 20px;
    margin-bottom: 10px;
    padding-left: 10px;
    padding-right: 10px;

    font-size: 14px;
    color: $dark;
    line-height: 1.25em;

    span {
      color: $blue;
      text-decoration: underline;
      text-transform: capitalize;
      cursor: pointer;
      &:hover {
        color: darken($blue, 5);
      }
    }

    @include breakpoint($s) {
      max-width: 360px;
      margin-left: auto;
      margin-right: auto;
      margin-top: 28px;
      font-size: 16px;
    }

    @include breakpoint($m) {
      max-width: 420px;
      margin-top: 30px;
      margin-bottom: 21px;
    }
  }
}
</style>
