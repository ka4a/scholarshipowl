<template lang="html">
  <footer class="mini-footer-layout">
    <logo-footer :isHPTransitionDisabled="true" class="mini-footer-layout__logo" />
    <copyright-footer class="copyright-footer-mini" />
  </footer>
</template>

<script>
import LogoFooter       from "./LogoFooter.vue";
import CopyrightFooter  from "./CopyrightFooter.vue";

export default {
  components: {
    LogoFooter,
    CopyrightFooter,
  }
}
</script>

<style lang="scss">
  .mini-footer-layout {
    height: 50px;
    max-height: 50px;
    min-height: 50px;
    background-color: #354C6D;
    padding: 15px;
    box-sizing: border-box;
    display: flex;
    justify-content: space-between;
    align-items: center;

    @include breakpoint($s) {
      padding: 15px 25px;
    }
  }
</style>