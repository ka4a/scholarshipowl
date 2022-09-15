<template>
  <div class="mdc-form-field">
    <input-radio-basic @change="val => $emit('change', val)" :disabled="disabled"
    :name="name" :value="value" :id="id || value" :checked="checked" />
    <label class="mdc-form-field__label" v-if="label" :for="id || value">{{ label }}</label>
    <slot />
  </div>
</template>

<script>
  import InputRadioBasic from "components/Common/Input/Radio/InputRadioBasic.vue";

  export default {
    components: {
      InputRadioBasic
    },
    props: {
      id: {type: String},
      label: {type: String},
      name: {type: String, required: true},
      value: {type: String, required: true},
      checked: {type: Boolean},
      disabled: {type: Boolean, default: false},
    }
  }
</script>

<style lang="scss">
  .mdc-form-field {
    display: flex;
    align-items: center;

    &__label {
      margin-left: 7px;
      cursor: pointer;
      @extend %tap-reset;
    }
  }
</style>