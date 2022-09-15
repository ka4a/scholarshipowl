<template>
  <button class="button is-white add-button has-text-primary" @click="$emit('click', $event)">
    <b-icon icon="plus-circle-outline" />
    <span class="bottom-dotted">{{ label }}</span>
  </button>
</template>
<script>
export default {
  props: {
    label: {
      type: String,
      required: true
    }
  }
}
</script>
<style lang="scss" scoped>
@import "~scss/variables.scss";

.add-button {
  cursor: pointer;
  display: flex;
  border-radius: 50px;
  .bottom-dotted {
    border-bottom: 2px dotted $primary;
  }
  .icon {
    margin-right: 7px;
  }
}
</style>
