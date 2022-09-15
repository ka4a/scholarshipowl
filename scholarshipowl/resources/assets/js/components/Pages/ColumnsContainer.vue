<template lang="html">
  <section class="columns-container">
    <aside class="columns-container__first">
      <slot name="first" />
    </aside>
    <section :class="['columns-container__second', {'columns-container__second_closed': showRightColumn}]">
      <slot name="second" />
    </section>
  </section>
</template>

<script>
export default {
  props: {
    showRightColumn: { type: Boolean, default: false }
  }
};
</script>

<style lang="scss">
@import 'main/meta/reset';

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';
@import 'main/meta/helpers';
@import 'scholarships/shapes-icons';

  // variables
  $blue: #708FE7;
  $blue-light: #ACCAF6;
  $dark: #2F2F2F;

// layout
.columns-container {
  @include flexbox();
  width: 100%;

  &__first {
    width: 328px;
    border-right: 1px solid #d8d8d8;
    @include flexbox();

    @include breakpoint(max-width $l - 1px) {
      width: 100%;
      box-sizing: border-box;
    }
  }

  &__second {
    @include flexbox();
    @include flex(1);
    width: 100%;
    @include flex-direction(column);
    @include flexbox();
    margin-left: auto;
    margin-right: auto;
    background-color: white;
    overflow: auto;

    @include breakpoint($s) {
      padding: 25px;
      background-color: #F3F8FF;
    }

    @include breakpoint(max-width $l - 1px) {
      height: 100%;
      position: absolute;
      top: 0; left: 0; bottom: 0;
      z-index: 1;
      box-sizing: border-box;
      -webkit-transition: 0.3s ease-in-out;
      transition: 0.3s ease-in-out;

      &_closed {
        -webkit-transform: translateX(200%);
        transform: translateX(200%);
      }
    }
  }
}
</style>
