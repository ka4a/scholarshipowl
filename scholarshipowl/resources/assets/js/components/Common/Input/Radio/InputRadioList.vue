<template>
  <div :class="['mdc-radio-list',
    {'mdc-radio-list_error': error},
    {'mdc-radio-list_horizontal': appear === 'horizontal'},
    {'mdc-radio-list_vertical': appear === 'vertical'}]">
    <input-radio class="mdc-radio-list__item"
      v-for="item in list"
      :key="item.value"
      :checked="value !== null && value.value === item.value"
      :name="name"
      :disabled="item.disabled"
      :label="item.label"
      :value="item.value"
      @change="value => $emit('input', {label: item.label, value})">
        <slot v-if="item.tooltip"/>
      </input-radio>
  </div>
</template>

<script>
  import InputRadio from "components/Common/Input/Radio/InputRadio.vue";

  export default {
    components: {
      InputRadio
    },
    props: {
      list: {type: Array, required: true},
      name: {type: String, required: true},
      value: {type: Object, required: true, default: null},
      error: {type: Boolean, default: false},
      appear: {type: String, default: 'horizontal'}
    }
  }
</script>

<style lang="scss">
  .mdc-radio-list {
    &__item {
      position: relative;
    }

    &_error {
      .mdc-radio__outer-circle {
        border-color: $carnation !important;
      }
    }

    // modificators
    &_horizontal {
      display: flex;

      .mdc-radio-list__item +
      .mdc-radio-list__item {
        margin-left: 23px;
      }
    }
  }
</style>