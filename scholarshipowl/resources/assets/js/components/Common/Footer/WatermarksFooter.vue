<template lang="html">
  <ul class="watermarks-footer">
    <li class="watermarks-footer__item watermarks-footer__ssl"></li>
    <li class="watermarks-footer__item watermarks-footer__dmca"></li>
    <li v-if="shouldShowNSPA" class="watermarks-footer__item watermarks-footer__nspa"></li>
  </ul>
</template>

<script>
export default {
  computed: {
    shouldShowNSPA() {
      return ["/about-us", "/register", "/register2", "/contact", "/my-account"].includes(window.document.location.pathname);
    }
  }
};
</script>

<style lang="scss">
@import 'main/meta/flex-box';

  .watermarks-footer {
    &__item {
      display: inline-block;

      & + & {
        margin-left: 20px;
      }

      &.watermarks-footer__nspa {
        margin-left: 30px;
      }
    }

    &__ssl {
      background: url('./svg/logo-ssl.svg') center center no-repeat;
      width: 87px; height: 43px;
    }

    &__dmca {
      background: url('./svg/logo-dmca.svg') center center no-repeat;
      width: 65px; height: 43px;
    }

    &__nspa {
      background: url('./svg/logo-nspa.svg') center center no-repeat;
      width: 71px; height: 43px;
      margin-left: 30px;
    }
  }
</style>
