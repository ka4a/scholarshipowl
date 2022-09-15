<template>
  <div :class="['mdc-radio', {'mdc-radio--disabled': disabled}]">
    <input class="mdc-radio__native-control"
      :id="id"
      :name="name"
      :value="value"
      type="radio"
      @change="ev => $emit('change', value)"
      :disabled="disabled"
      :checked="checked">
    <div class="mdc-radio__background">
      <div class="mdc-radio__outer-circle"></div>
      <div class="mdc-radio__inner-circle"></div>
    </div>
  </div>
</template>

<script>
  import {MDCRadio} from '@material/radio';

  export default {
    props: {
      name: {type: String, required: true},
      value: {type: String, required: true},
      checked: {type: Boolean, default: false},
      disabled: {type: Boolean, default: false},
      id: {type: String, required: true}
    },
    mounted() {
      new MDCRadio(this.$el);
    }
  }
</script>

<style lang="scss">
  $green: #2fd9b3;
  $grey: #9f9f9f;
  $white: #fff;

  $mdc-radio-touch-area: 39.5px;
  $mdc-radio-ui-size: 25px;

  @import "@material/radio/mdc-radio";

  .mdc-radio {
    @include mdc-radio-unchecked-stroke-color($grey);
    @include mdc-radio-checked-stroke-color($grey);
    @include mdc-radio-ink-color($green);
    @include mdc-radio-focus-indicator-color($grey);

    &__outer-circle {
      border-width: 1px;
      background-color: $white;
    }

    &__inner-circle {
      border-width: 12.5px;
    }

    .mdc-radio__native-control:checked +
    .mdc-radio__background .mdc-radio__inner-circle {
      transform: scale(0.6);
    }

    &--disabled {
      .mdc-radio__outer-circle {
        background-color: $silver;
      }
    }
  }
</style>