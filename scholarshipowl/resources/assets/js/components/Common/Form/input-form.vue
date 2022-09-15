<template lang="html">
  <p :class="classNamePrefix + '__wrp'">
    <span>{{ value }}</span>
    <span :class="classNamePrefix + '__error'"></span>
    <input ref="input"
           :name="name"
           :id="name"
           :type="type || 'text'"
           :class="classNamePrefix + '__input'"
           :value="value"
           :placeholder="placeholder"
           @input="updateValue($event.target.value)">
  </p>
</template>

<script>
export default {
  props: {
    name: {
      type: String,
      required: true
    },
    className: {
      type: String
    },
    type: {
      type: String,
    },
    placeholder: {
      type: String,
    },
    value: {
      required: true
    }
  },
  computed: {
    classNamePrefix: function() {
      return this.className || 'interaction-item'
    }
  },
  methods: {
    updateValue(value) {
      this.$emit('input', value);
    }
  }
}
</script>

<style lang="css">
</style>
