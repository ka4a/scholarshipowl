
<template lang="html">
  <div class="custom">
    <input type="text" ref="input" :value="value" @input="updateValue($event.target.value)">
    <div class="appearing">{{ value }}</div>
  </div>
</template>

<script>
export default {
  props: ['value'],
  methods: {
    updateValue(value) {
      this.$refs.input.value = value;
      this.$emit('value', value);
    }
  }

}
</script>

<style lang="css">
</style>
