<template lang="html">
  <div :class="$bem.block(className, 'new-line-indent')">
    <input :checked="value" @change="change" class="coregBox" type="checkbox" :name="name" :id="name" >
    <label :for="name" :class="className + '__item'"></label>
    <slot>
      <span :class="className + '__text'"></span>
    </slot>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      required: true,
    },
    className: {
      type: String,
      default: "vue-checkbox"
    },
    name: {
      type: String,
      required: true
    }
  },
  methods: {
    change (event) {
      this.$emit("input", event.target.checked);
    }
  }
};
</script>

<style lang="css">
</style>
