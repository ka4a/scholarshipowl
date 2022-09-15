<template>
  <p class="package-item">
    <i :class="['package-item__icon', {'package-item_check': opt.status, 'package-item_cross': !opt.status}]" />
    <span v-if="opt.text" class="package-item__text" v-html="opt.text"></span>
  </p>
</template>

<script>
  export default {
    props: {
      opt: {type: Object, required: true}
    }
  }
</script>

<style lang="scss">
  .package-item {
    display: flex;

    @include breakpoint($s) {
      align-items: center;
    }

    @extend %style-formating-tags;

    &__icon {
      @include breakpoint($l) {
        margin: 0 !important;
      }
    }

    &_check {
      @include check-mark($mine-shaft, 4px, 9px, 3px);
      min-width: 4px;
      margin-left: 3px;
      margin-top: 3px;
      margin-right: 10px;

      @include breakpoint($s) {
        @include check-mark($mine-shaft, 5px, 10px, 3px);
        margin-right: 20px;
        margin-top: 0;
      }

      @include breakpoint($l) {
        @include check-mark($mine-shaft, 6px, 12px, 3.5px);
      }
    }

    &_cross {
      @include cross-mark($silver, 16px, 3px);
      margin-top: 5px;
      margin-right: 14px;

      @include breakpoint($l) {
        @include cross-mark($silver, 20px, 3px);
      }
    }

    &__text {
      font-size: 14px;
      line-height: 1.5em;

      b {
        font-weight: 700;
      }
    }
  }
</style>