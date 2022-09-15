<template>
  <i class="btn-back-arrow"></i>
</template>

<style lang="scss">
  $color-arrow: #c1cee7;

  .btn-back-arrow {
    width: 31px;
    height: 20px;
    display: block;
    position: relative;
    overflow: hidden;
    cursor: pointer;

    &:before {
      @include angle-bracket(left, 20px, 3px, $color-arrow);
      position: absolute;
      left: 5px; top: -1.5px;
    }

    &:after {
      height: 3.5px;
      background-color: $color-arrow;
      width: 30px;
      margin: auto;
      top: 0; bottom: 0; left: 3px;
    }

    &:before,
    &:after {
      content: "";
      position: absolute;
    }
  }
</style>