<template>
  <span :style="{width: size + 'px', height: size + 'px', minHeight: size + 'px', minWidth: size + 'px'}" class="indicator-circle">
    <i :class="{'indicator-circle__down': !isActive, 'indicator-circle__up': isActive}"></i>
  </span>
</template>

<script>
  export default {
    props: {
      isActive: {type: Boolean, default: false},
      size: {type: Number, default: 22},
    }
  }
</script>

<style lang="scss">
  .indicator-circle {
    background-color: $solitude;
    box-sizing: border-box;
    display: flex;
    justify-content: center;
    align-items: center;
    border-radius: 50%;

    &__down {
      @include angle-bracket(bottom, 6px, 2px, $rock-blue);
      margin-bottom: 10%;
    }

    &__up {
      @include angle-bracket(up, 6px, 2px, $rock-blue);
      margin-top: 10%;
    }
  }
</style>