<template>
  <section class="plans-header-ctnt">
    <h2 v-if="headerText" class="plans-header-ctnt__title" v-html="headerText"></h2>
    <h4 class="plans-header-ctnt__sub-title" v-html="headerSubText"></h4>
    <slot />
  </section>
</template>

<script>
  export default {
    props: {
      headerText: {type: String, required: true},
      headerSubText: {type: String, required: true},
      isMobile: {type: Boolean}
    }
  }
</script>

<style lang="scss">
  .plans-header-ctnt {
    &__title {
      @extend %title-header-plans-page;

      @include breakpoint(max-width $l - 1px) {
        text-align: center;
      }

      @include breakpoint($m $l - 1px) {
        width: 476px;
        margin-left: auto;
        margin-right: auto;
      }

      @include breakpoint($l) {
        min-width: 360px;
      }
    }

    &__sub-title {
      @extend %sub-title-header-plans-page;
      text-align: center;
      margin-top: 10px;

      b {
        font-weight: 700;
      }

      @include breakpoint($m) {
        margin-top: 15px;
      }

      @include breakpoint($l) {
        margin-top: 25px;
        text-align: left;
      }
    }
  }
</style>