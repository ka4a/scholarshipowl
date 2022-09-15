<template>
  <div class="input-password">
    <input-text
    :type="isShowedValue ? 'text' : 'password'"
    @focus="ev => $emit('focus', ev)"
    @input="ev => $emit('input', ev)"
    :error="error"
    :name="name"
    :placeholder="placeholder"
    autocomplete="new-password"
    :value="value" />
    <i v-if="value && value.length"
       :class="['input-password__icon', 'icon', { 'icon-eye': isShowedValue, 'icon-no-eye': !isShowedValue }]"
       @click="changeValueAppearance"></i>
  </div>
</template>

<script>
  import InputText from "components/Common/Input/Text/InputTextBasic.vue";

  export default {
    components: {
      InputText
    },
    props: {
      name: {type: String, required: true},
      error: {type: Boolean, default: false},
      placeholder: {type: String},
      value: {type: String},
      isShowedValue: {type: Boolean, default: false}
    },
    methods: {
      changeValueAppearance() {
        this.isShowedValue = !this.isShowedValue;
      }
    }
  }
</script>

<style lang="scss">
  .input-password {
    position: relative;

    &__icon {
      position: absolute;
      cursor: pointer;
      color: #3b476e;
      font-size: 26px;
      right: 15px;
      top: 12px;
    }

    input {
      padding-right: 50px;
    }
  }
</style>