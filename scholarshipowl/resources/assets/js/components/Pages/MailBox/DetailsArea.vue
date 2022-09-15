<template>
  <div class="details-area">
    <slot name="banner" />
    <slot name="back" />
    <slot name="main" />
  </div>
</template>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  $font-family: 'Open Sans';
  $dark: #2F2F2F;
  $grey: #C2C2C2;
  $grey-light: #E4E4E4;
  $white: #ffffff;

  %main-font {
    font-size: 14px;
    font-family: $font-family;
    line-height: 1.4em;
    color: $dark;

    @include breakpoint($s) {
      font-size: 18px;
    }
  }

  .details-area {
    @include flexbox();
    @include flex-direction(column);
    @include justify-content(center);
    padding: 15px;
    border: 1px solid rgba(154, 180, 255, 0.29);
    box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.07);
    background-color: $white;
    height: 100%;

    @include breakpoint($s) {
      padding: 25px 20px;
    }

    @include breakpoint($m) {
      padding: 30px;
    }

    @include breakpoint($xl) {
      padding: 60px 20% 60px 20%;
    }
  }
</style>