<template>
  <i :class="['icon-css-round',
    `icon-css-round_${name}`,
    {'icon-css-round_filled': isFilled}]"></i>
</template>

<script>
  export default {
    props: {
      name: {type: String},
      isFilled: {type: Boolean, default: false},
    },
  }
</script>

<style lang="scss">
  // global variables
  // assets/sass/style-gide/palette/_variables.scss
  // $carnation
  // $turquoise

  .icon-css-round {
    box-sizing: border-box;
    width: 36px;
    height: 36px;
    min-width: 36px;
    min-height: 36px;
    border-radius: 50%;
    border-style: solid;
    border-width: 2px;
    display: block;

    &_fail {
      border-color: $carnation;
      text-align: center;

      &:before {
        content: "!";
        color: $carnation;
        font-size: 28px; //78
        font-weight: 700;
      }

      &.icon-css-round_filled {
        background-color: $carnation;

        &:before {
          color: $white;
        }
      }
    }

    &_success {
      border-color: $turquoise;

      &:before {
        @include check-mark($turquoise, 9px, 16px);

        margin: auto;
        margin-top: 4px;
      }

      &.icon-css-round_filled {
        background-color: $turquoise;

        &:before {
          border-color: $white;
        }
      }
    }

    &_none {
      border-color: $mercury;
      text-align: center;

      &:before {
        content: "—";
        color: $mercury;
        font-size: 26px; // 72
        font-weight: 700;
        margin-left: -4px;
      }

      &.icon-css-round_filled {
        background-color: $mercury;

        &:before {
          color: $white;
        }
      }
    }
  }
</style>