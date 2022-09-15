<template>
  <article class="layout-reg">
    <Header />
    <pre-loader v-show="loading" class="layout-reg__loader" />
    <div class="layout-reg-wrp" v-show="!loading">
      <div style="background-color: white; padding-top: 1px">
        <progress-line class="layout-reg__line base-hor-indent"
        :step-count="steps.length" :current-step-number="currentStepNumber" />
      </div>
      <router-view @loaded="loaded"></router-view>
    </div>
    <Footer />
    <cookies-disclaimer :key="$route.path" />
    <Modal />
  </article>
</template>

<script>
import { ROUTES } from "router.js";
import Header from "components/Common/Header/MiniHeader.vue";
import ProgressLine from "components/Pages/Register/ProgressLine.vue";
import Footer from "components/Common/Footer/MiniFooter.vue";
import CookiesDisclaimer from "components/Common/CookiesDisclaimer.vue";
import Modal from "components/Common/Modals/Modal.vue";
import PreLoader from "components/Pages/Own/PreLoader/PreLoader.vue";

const steps = [
  "/register",
  "/register2",
  "/register3"
];

export default {
  name: "layout-register",
  components: {
    Header,
    ProgressLine,
    Footer,
    CookiesDisclaimer,
    Modal,
    PreLoader
  },
  data() {
    return {
      steps,
      loading: true
    }
  },
  methods: {
    loaded() {
      this.loading = false;
    }
  },
  computed: {
    currentStepNumber() {
      return steps.indexOf(this.$route.path) + 1;
    }
  }
};
</script>

<style lang="scss">
  // font sizes
  $font-size-title-1: 42.6px;
  $font-size-title-2: 32px;
  $font-size-title-3: 28.4px;

  $font-size-paragraph-1: 24px;
  $font-size-paragraph-2: 18px;
  $font-size-paragraph-3: 16px;

  $title-color: $mine-shaft;
  $paragraph-color: $mine-shaft;
  $amount-title-color: $pinkish-orange-two;

  .layout-reg {
    height: 100%;
    display: flex;
    flex-direction: column;

    &__line {
      margin-top: 20px;

      @include breakpoint($m) {
        margin-top: 25px;
      }

      @include breakpoint(1200px) {
        width: 100%;
      }
    }

    &__loader {
      min-height: 90vh;
    }
  }

  .layout-reg-wrp {
    flex: 1 0 auto;
    background-color: $alice-blue;
  }

  .title-reg {
    font-size: $font-size-title-3;
    letter-spacing: 0.1px;
    color: $title-color;
    font-weight: 700;
    line-height: 1.3em;

    @include breakpoint($m) {
      font-size: $font-size-title-2;
    }

    @include breakpoint($l) {
      font-size: $font-size-title-1;
      letter-spacing: 0.2px;
    }
  }

  .sub-title-reg {
    font-size: $font-size-paragraph-3;
    letter-spacing: 0.2px;
    color: $paragraph-color;
    line-height: 1.375em;

    @include breakpoint($m) {
      font-size: $font-size-paragraph-2;
      letter-spacing: 0.3px;
    }

    @include breakpoint($l) {
      font-size: $font-size-paragraph-1;
      letter-spacing: 0.4px;
    }
  }

  .amount-title-reg {
    font-size: $font-size-title-3;
    color: $amount-title-color;
    font-weight: 700;

    @include breakpoint($m) {
      font-size: $font-size-title-1;
    }
  }

  .base-hor-indent {
    $step: 10px;

    max-width: 1090px;

    margin-left: $step * 2;
    margin-right: $step * 2;

    @include breakpoint($m) {
      margin-left: $step * 3;
      margin-right: $step * 3;
    }

    @include breakpoint($l) {
      margin-left: $step * 6;
      margin-right: $step * 6;
    }

    @include breakpoint(1200px) {
      margin-left: auto;
      margin-right: auto;
    }
  }

  .text5 {
    font-size: 12px;
    line-height: 1.93em;
    color: #919daf;

    a {
      color: #495d7b;
    }
  }
</style>