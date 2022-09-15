<template>
  <a :href="href" @click="handleClick" :class="[
    'button-custom',
    {[`button-custom_${size}`]: size},
    {[`button-custom_${theme}`]: theme},
    {'button-custom_loader': showLoader}]">
    <loader v-if="showLoader" :size="sizeLoader" class="button-custom__loader" />
    <Icon v-if="icon && icon.position === 'start'"
      class="button-custom__icon-start" :name="icon.name" />
    <span v-if="label || $slots.label" class="button-custom__label">
      <slot v-if="!label && $slots.label" name="label"/>
      <template v-if="label">{{ label }}</template>
    </span>
    <Icon v-if="icon && icon.position === 'end'"
      class="button-custom__icon-end" :name="icon.name" />
  </a>
</template>

<script>
  import { MDCRipple } from "@material/ripple";
  import { enterKeyPressHolder } from "lib/utils/event";
  import Icon from "components/Pages/Own/SvgIcons/SvgIcon.vue";
  import Loader from "components/Common/Buttons/BtnLoader.vue"

  export default {
    components: {
      Icon,
      Loader
    },
    props: {
      label:      {type: String},
      size:       {type: String, default: 'l'},
      theme:      {type: String, default: 'orange'},
      sizeLoader:  {type: String, default: 'm'},
      href:       {type: String, default: ''},
      showLoader: {type: Boolean, default: false},
      shouldHoldKeyPress: {type: Boolean, default: false},
      icon: {type: Object}
    },
    created() {
      if(this.shouldHoldKeyPress) {
        this.enterHold = enterKeyPressHolder();
      }
    },
    mounted() {
      if(!this.enterHold) return;

      this.enterHold.bind(this.emitClickEvent)
    },
    beforeDestroy() {
      if(!this.enterHold) return;

      this.enterHold.unbind();
    },
    data() {
      return {
        enterHold: null
      }
    },
    methods: {
      handleClick(ev) {
        if(!this.href) {
          ev.preventDefault();
        }
      },
      emitClickEvent(ev) {
        this.$emit('click', ev);
      },
    }
  }
</script>

<style lang="scss">
  .button-custom {
    transition-property: background, box-shadow;
    transition-duration: 300ms;
    border-radius: 1px;
    display: flex;
    justify-content: center;
    align-items: center;

    &__icon-start {
      margin-right: 10px;
    }

    &__icon-end {
      margin-left: 10px;
    }

    &__label {
      font-family: "Open Sans";
      font-weight: bold;
      text-align: center;
      text-transform: uppercase;
    }

    &__loader {
      margin-right: 10px;
    }

    &_xl {
      height: 65px;

      .button-custom__label {
        font-size: 24px;
      }
    }

    // size modificators
    &_l {
      height: 56px;

      .button-custom__label {
        font-size: 24px;
      }
    }

    &_m {
      height: 50px;
      padding-left: 25px;
      padding-right: 25px;
      box-sizing: border-box;

      .button-custom__label {
        font-size: 15px;
      }

      @include breakpoint($l) {
        height: 53px;

        .button-custom__label {
          font-size: 18px;
        }
      }
    }

    &_s {
      height: 40px;
      padding-left: 25px;
      padding-right: 25px;
      box-sizing: border-box;

      .button-custom__label {
        font-size: 16px;
      }

      .button-custom__loader {
        margin-right: 6px;
      }

      &.button-custom_loader {
        padding-left: 10px;
        padding-right: 10px;
      }
    }

    // appearing modificators
    &_orange {
      background:
        linear-gradient(to bottom, #ff863f 0%, #fb6e3f);

      &:hover {
        box-shadow: 0 2px 4px 0 #a8a8a8;
      }

      &:active {
        background: linear-gradient(#f76931, #f76931);
        box-shadow: none;
      }

      .button-custom__label {
        color: $white;
      }
    }

    &_white {
      // No in stylegide
      background: #f7f7f7;

      .button-custom__label {
        color: #f87b4a;
      }

      &:hover {
        box-shadow: 0 2px 4px 0 #a8a8a8;
        background: $white;
      }

      &:active {
        background: #ec4909;
        box-shadow: none;

        .button-custom__label {
          color: $white;
        }
      }
    }

    &_grey {
      // no in stylegide
      border-color: #a9aeb2;
      background-color: #c1c7cc;

      .button-custom__label {
        color: $white;
      }

      &:hover {
        box-shadow: 0 2px 4px 0 #a8a8a8;
      }

      &:active {
        background-color: #a9aeb2;
        border-color: #919599;
        box-shadow: none;
      }
    }

    &_loader {
      cursor: wait;
      // cursor: not-allowed;
    }
  }
</style>