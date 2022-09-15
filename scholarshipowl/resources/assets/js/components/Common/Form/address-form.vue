<template lang="html">
  <div class="address-fields">
    <input-errorable :model="value" rules="required" name="address">
        <input-form :value="value" @input="handleChanges" placeholder="some good placeholder" name="address" type="input" />
    </input-errorable>
  </div>
</template>

<script>
import inputForm from './input-form.vue'
import inputErrorable from './inputErrorable.vue'

export default {
  props: {
    value: String
  },
  components: {
    inputErrorable,
    inputForm
  },
  methods: {
    handleChanges(value) {
      this.$emit('input', value)
    }
  }
}
</script>

<style lang="css">
</style>
