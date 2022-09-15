<template>
  <a click.prevent :class="['ctrl-arrow-round', {'ctrl-arrow-round_open': open}]" href="#">
    <i class="ctrl-arrow-round-icon"></i>
  </a>
</template>

<script>
  export default {
    props: {
      open: {type: Boolean, default: false}
    }
  }
</script>

<style lang="scss">
@import 'main/meta/reset';

@import 'style-gide/breakpoints';
@import 'style-gide/mixins';

  $light: #FFFFFF;
  $dark: #2F2F2F;
  $green: #2FD9B3;

  .ctrl-arrow-round {
    position: absolute;
    width: 38px;
    height: 38px;
    border-radius: 50%;
    cursor: pointer;

    right: 15px;
    top: 12px;

    background: $green;
    box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.07);

    @include breakpoint($m) {
      top: 23px;
    }

    @include breakpoint($l) {
      right: 25px;
    }

    .ctrl-arrow-round-icon {
      @include angle-bracket(up, 9px, 2px, $light);
      position: absolute;
      left:0; right: 0;
      top: 41%;
      margin: auto;
    }

    &_open {
      background-color: $light;

      .ctrl-arrow-round-icon {
        border-color: $dark;
        top: 31%;
        transform: rotate(135deg);
      }
    }
  }
</style>