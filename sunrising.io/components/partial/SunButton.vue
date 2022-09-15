<template>
  <button
    :class="[variant, {'has-icon': icon, small: small, thin: thin}]"
    :disabled="disabled"
    class="sun-button"
    @click="$emit('click')"
  >
    {{ text }}

    <img
      v-show="icon"
      class="icon dusk"
      src="../../assets/img/button-icon-red.svg"
      alt="logo"
    >

    <img
      v-show="icon"
      class="icon bright"
      src="../../assets/img/button_icon_red_bright.svg"
      alt="logo"
    >
  </button>
</template>

<script>
export default {
  name: 'SunButton',

  props: {
    text: {
      type: String,
      required: true,
    },

    variant: {
      type: String,
      default: 'default',
    },

    thin: {
      type: Boolean,
      default: false,
    },

    small: {
      type: Boolean,
      default: false,
    },

    icon: {
      type: Boolean,
      default: false,
    },

    disabled: {
      type: Boolean,
      default: false,
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

.sun-button {
  text-transform: uppercase;
  border-radius: 90px;
  border: none;
  font-family: 'Catamaran-ExtraBold';
  padding: 0 35px;
  width: 285px;
  font-size: 17px;
  max-width: 100%;
  transition: all .25s ease;
  min-height: 62px;

  &.small {
    width: 250px;
    font-size: 15px;

    @include sun-widescreen {
      min-width: 329px;
    }
  }

  &.has-icon {
    padding: 0 16px 3px 24px;

    @include sun-tablet {
      padding: 0 16px 0 24px;
    }
  }

  .icon {
    transform: translateY(6px);
    margin-left: 20px;

    @include sun-widescreen {
      transform: translateY(5px);
    }

    &.bright {
      display: none;
    }
  }

  &.default {
    background: linear-gradient(83.45deg, #eaeeee 4.63%, #f0f5f6 95.01%);
    color: $gray;

    &:hover {
      background: linear-gradient(83.45deg, #dceaec 4.63%, #dce8ea 95.01%);
    }

    &:focus {
      background: linear-gradient(83.45deg, #cfdee0 4.63%, #cfdbdc 95.01%);
    }
  }

  &.white {
    background: #FFFFFF;
    box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.15);
    color: $red;

    @include sun-widescreen {
      padding-top: 5px;
    }

    &:hover {
      background: #F9F9F9;
      box-shadow: none;
    }

    &:focus {
      background: #EFEFEF;
      box-shadow: none;
    }

    .icon {
      &.dusk {
        display: none;
      }

      &.bright {
        display: inline-block;
      }
    }
  }

  &.white-grim {
    background: #FFFFFF;
    box-shadow: 0px 20px 25px rgba(0, 0, 0, 0.15);
    color: $gray;

    &:hover {
      background: #F9F9F9;
      box-shadow: none;
    }

    &:focus {
      background: #EFEFEF;
      box-shadow: none;
    }

    .icon {
      &.dusk {
        display: inline-block;
      }

      &.bright {
        display: none;
      }
    }
  }

  &.red {
    background: linear-gradient(254.14deg, #d73148 7.32%, #eb6668 92.68%);
    color: $white;

    .icon {
      filter: brightness(0) invert(1);
      opacity: 0.3;
    }

    &:hover {
      background: linear-gradient(254.14deg, #ec0e2d 7.32%, #ff5c60 92.68%);

      .icon {
        opacity: 0.6;
      }
    }

    &:focus {
      background: linear-gradient(254.14deg, #d61833 7.32%, #df5457 92.68%);

      .icon {
        opacity: 0.6;
      }
    }
  }

  @include sun-widescreen {
    min-width: 329px;
    min-height: 71px;
    font-size: 19px!important;
    padding-top: 5px;
  }

  @include sun-hd {
    min-width: 400px;
    min-height: 98px;
    font-size: 25px!important;
  }

  @include sun-hd {
    width: auto!important;
    height: 100px!important;

    .icon {
      width: 41px;
      height: 41px;
      transform: translateY(12px);
    }

    &.has-icon {
      padding: 15px 30px 20px 60px!important;
    }
  }

  @include sun-fullhd {
    font-size: 28px!important;
    min-width: 400px;
    min-height: 112px;
  }

  &.thin {
    @include sun-tablet {
      min-width: 172px;
      width: 172px;
    }

    @include sun-hd {
      min-width: 222px;
      width: 222px;
    }
  }

  &:disabled {
    background: linear-gradient(257.4deg, #9c9c9c 7.32%, #c0c0c0 92.68%),
      linear-gradient(83.45deg, #cfdee0 4.63%, #cfdbdc 95.01%)!important;
    color: $disabled-text!important;

    .icon {
      filter: brightness(0) invert(1)!important;
      opacity: 0.8!important;
    }
  }
}
</style>
