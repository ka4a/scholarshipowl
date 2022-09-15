<template>
  <div class="base-container">
    <component :is="headerType" class="base-container__header" />
    <div class="base-container__main">
      <pre-loader v-show="loading" class="base-container__loader" />
      <router-view v-show="!loading" @loaded="loaded"></router-view>
    </div>
    <component class="base-container__footer" :is="footerType" />
    <slot name="login" />
    <modal />
  </div>
</template>

<script>
import { fetchWithDalay } from "lib/utils/utils";
import Header             from "components/Common/Header/Header.vue";
import MiniHeader         from "components/Common/Header/MiniHeader.vue"
import PreLoader          from "components/Pages/Own/PreLoader/PreLoader.vue";
import Footer             from "components/Common/Footer/Footer.vue";
import MiniFooter         from "components/Common/Footer/MiniFooter.vue";
import Modal              from "components/Common/Modals/Modal.vue";

export default {
  components: {
    Header,
    MiniHeader,
    PreLoader,
    Footer,
    MiniFooter,
    Modal
  },
  props: {
    headerType: {type: String, default: "Header"},
    footerType: {type: String, default: "Footer"}
  },
  data() {
    return {
      loading: true
    }
  },
  methods: {
    loaded() {
      this.loading = false
    }
  }
};
</script>

<style lang="scss">
  a {
    text-decoration: none;
  }

  // helpers
  .di {
    display: inline;
  }

  .inner-indent {
    padding-left: 15px;
    padding-right: 15px;

    @include breakpoint($s) {
      padding-left: 25px;
      padding-right: 25px;
    }

    @include breakpoint($m) {
      padding-left: 26px;
      padding-right: 26px;
    }

    @include breakpoint($xl) {
      padding-left: 80px;
      padding-right: 80px;
    }
  }

  .inner-tab-indent {
    @extend .inner-indent;
    padding-top: 20px;
    padding-bottom: 20px;
    max-width: 646px;
    margin-left: auto;
    margin-right: auto;

    @include breakpoint($s) {
      padding-top: 25px;
      padding-bottom: 25px;
    }

    @include breakpoint($m) {
      padding-top: 30px;
      padding-bottom: 30px;
    }
  }

  .base-container {
    font-family: 'Open Sans', sans-serif;
    min-height: 100%;
    flex-direction: column;
    color: #333333;
    display: flex;

    &__loader {
      min-height: 90vh;
    }

    &__header {
      flex: 1 0 auto;
      width: auto !important;
    }

    &__main {
      flex: 1 1 auto;
      position: relative;
      transition: all 300ms;
    }
  }

  ::-webkit-scrollbar {
      -webkit-appearance: none;
      width: 7px;
  }

  ::-webkit-scrollbar-thumb {
      border-radius: 4px;
      background-color: rgba(0,0,0,.5);
      -webkit-box-shadow: 0 0 1px rgba(255,255,255,.5);
  }
</style>