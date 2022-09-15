<template>
  <div :class="$bem.block('interaction-item')">
    <label :class="$bem.element('interaction-item', 'title')" :for="forInput">{{ label }}</label>
    <p :class="$bem.element('interaction-item', 'wrp')">
      <span v-if="error" :class="$bem.element('interaction-item', 'error')">{{ error }}</span>
      <slot></slot>
    </p>
  </div>
</template>
<style>
</style>
<script type="javascript">
export default {
  name: "CommonInteractionItem",
  props: {
    label: {
      type: String,
      required: true,
    },
    error: {
      type: String,
      default: null,
    },
    forInput: {
      type: String,
      default: null,
    }
  },
};
</script>
