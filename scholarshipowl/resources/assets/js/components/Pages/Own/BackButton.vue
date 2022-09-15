<template>
  <button  class="to-list-btn">
    <span class="lt"></span>
  </button>
</template>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';

  // variables
  $blue: #708FE7;

  .to-list-btn {
    height: 24px;
    background-color: white;

    // back to list controller
    .lt {
      cursor: pointer;
      box-sizing: border-box;
      margin-left: 3px;
      display: block;
      height: 18px;
      width: 18px;
      border-top: 2px solid $blue;
      border-left: 2px solid $blue;
      -webkit-transform: rotate(-45deg);
      transform: rotate(-45deg);
    }
  }
</style>