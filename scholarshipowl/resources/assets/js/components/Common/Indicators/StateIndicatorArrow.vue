<template>
  <i :class="[
    'state-indicator-arrow',
    {'state-indicator-arrow_true': state},
    {'state-indicator-arrow_false': !state}]"></i>
</template>

<script>
  export default {
    props: {
      state: {type: Boolean, required: true}
    }
  }
</script>
<style lang="scss">
  .state-indicator-arrow {
    &_false {
      @include angle(bottom, 4px, black);
    }

    &_true {
      @include angle(top, 4px, black);
    }
  }
</style>