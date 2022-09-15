<template lang="html">
  <p class="copyright-footer">
    <span class="copyright-footer-mini__copy">Copyright © {{ new Date().getFullYear() }}.</span>
    <span class="copyright-footer-mini__text">All Rights Reserved</span>
  </p>
</template>

<style lang="scss">

@import 'style-gide/_breakpoints.scss';

  .copyright-footer {
    padding: 0;
    margin: 0;

    color: #8094B0;
    font-size: 10px;
  }

  .copyright-footer-mini {
    .copyright-footer-mini__text {
      text-decoration: underline;
    }

    @include breakpoint(max-width $s - 1px) {
      .copyright-footer-mini__text {
        display: block;
      }
    }
  }
</style>