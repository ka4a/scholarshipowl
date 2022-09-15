<template lang="html">
  <h3 :class="['footer-logo', {'footer-logo_disabled': isHPTransitionDisabled}]">
    <span v-if="isHPTransitionDisabled" class="footer-logo__link"></span>
    <a v-else href="/" @click="clickHolder" class="footer-logo__link"></a>
  </h3>
</template>

<script>
  export default {
    props: {
      isHPTransitionDisabled: {type: Boolean, default: false}
    },
  }
</script>

<style lang="scss">
  .footer-logo {
    // reset h3
    margin: 0;
    padding: 0;

    display: inline;
    font-size: 100%;

    &__link {
      background: url('./svg/logo-scholarshipowl-footer.svg') center center no-repeat;
      width: 131px; height: 17px;
      display: inline-block;
    }
  }
</style>
