<template lang="html">
  <aside class="left-container">
    <div class="left-container__first">
      <slot name="first"></slot>
    </div>
    <div class="left-container__second inner-hor-indent">
      <slot name="second"></slot>
    </div>
    <div class="left-container__third inner-hor-indent">
      <slot name="third"></slot>
    </div>
    <div class="left-container__fourth">
      <slot name="fourth"></slot>
    </div>
  </aside>
</template>

<script>
export default {

};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  // variables
  $blue: #708FE7;
  $blue-light: #ACCAF6;
  $dark: #2F2F2F;
  $grey: #E4E4E4;

  .inner-hor-indent {
    padding-left: 15px;
    padding-right: 15px;

    @include breakpoint($m $l - 1px) {
      padding-left: 25px;
      padding-right: 25px;
    }
  }

  .left-container {
    @include flexbox();
    @include flex-direction(column);
    overflow: hidden;
    width: 100%;

    &__second {
      margin-top: 10px;
    }

    &__third {
      min-height: 40px;
      border-bottom: 1px solid $grey;
      @include flexbox();
      @include align-items(center);

      > div {
        width: 33%;
      }
    }

    &__fourth {
      @include flex(1 1 auto);
      overflow: auto;
    }
  }
</style>
