<template>
  <div class="progress-line">
    <span v-for="i in stepCount"
    :class="[
      'progress-line__item',
      {'filled': i < currentStepNumber},
      {'current': i === currentStepNumber},
      {'first': i === 1},
      {'last': i === stepCount}]"></span>
  </div>
</template>

<script>
  export default {
    props: {
      stepCount: {type: Number, required: true},
      currentStepNumber: {type: Number, required: true}
    }
  }
</script>

<style lang="scss">
  $color: #dde3e7;
  $color-filled: #597ce1;
  $background-color: white;

  %tail-body {
    content: "";
    position: absolute;
    width: 0;
    height: 0;
    border-style: solid;
  }

  %left-tail {
    border-width: 4px 0 4px 4px;
    border-color: transparent transparent transparent $background-color;

    @include breakpoint($m) {
      border-width: 5px 0 5px 5px;
    }
  }

  %right-tail {
    border-width: 4px 0 4px 4px;
    border-color: $background-color transparent $background-color transparent;

    @include breakpoint($m) {
      border-width: 5px 0 5px 5px;
    }
  }

  .progress-line {
    display: flex;
    background-color: $white;
    min-height: 8px;

    &__item {
      background-color: $color;
      display: block;
      flex: 1 1 auto;
      height: 8px;
      position: relative;

      @include breakpoint($m) {
        height: 10px;
      }

      & + & {
        margin-left: 2px;
      }

      &:before,
      &:after {
        @extend %tail-body;
      }

      &:before {
        left: 0;
        @extend %left-tail;
      }

      &:after {
        right: 0;
        @extend %right-tail;
      }

      &.current,
      &.filled {
        background-color: $color-filled;
      }

      // first item
      &.first:before,
      &.last:after {
        content: none;
      }
    }
  }
</style>