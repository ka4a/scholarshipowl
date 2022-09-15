<template>
  <a class="close-ctrl"></a>
</template>

<style lang="scss">
  .close-ctrl {
    @include css-icon('cross', (
      height: 26px,
      width: 26px,
      color: $mine-shaft,
      line-width: 2px));

    @include breakpoint($s) {
      @include css-icon('cross', (
        height: 30px,
        width: 30px,
        color: $mine-shaft,
        line-width: 2px));
    }

    // positioning
    position: absolute;
    cursor: pointer;
    right: 15px;
    top: 15px;

    @include breakpoint($s) {
      right: 25px;
      top: 25px;
    }

    @include breakpoint($m) {
      right: 30px;
    }
  }
</style>