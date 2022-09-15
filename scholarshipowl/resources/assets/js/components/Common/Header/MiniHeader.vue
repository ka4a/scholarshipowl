<template>
  <header class="mini-header-layout">
      <logo :clickable="false" :isShort="false" class="mini-header-layout__logo" />
  </header>
</template>

<script>
import Logo from "./Logo.vue";

export default {
  components: {
    Logo
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  .mini-header-layout {
    min-height: 58px;
    height: 58px;
    background-color: #fff;
    position: relative;
    z-index: 17;
    box-shadow: 0px 2px 3px rgba(0, 0, 0, 0.25);
    @include flexbox();
    @include justify-content(flex-end);
    @include align-items(center);

    padding-left: 15px;
    padding-right: 15px;

    &__logo {
      margin: 0 auto !important;
      width: 100%;
      a {
        cursor: default;
        margin-left: auto;
        margin-right: auto;
      }
    }
  }
</style>