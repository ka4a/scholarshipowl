<template>
  <div class="field-switch clearfix">
    <div class="field-switch_header">
      <div class="titles">
        <label class="label">{{ title }}</label>
        <p class="sublabel">{{ subtitle }}</p>
      </div>
      <b-switch :value="value" @input="$emit('input', $event)"></b-switch>
    </div>
    <p v-if="message" class="help is-danger" v-html="message"></p>
    <transition name="fade">
      <div class="field-switch_body" v-if="value">
        <slot>Empty content.</slot>
      </div>
    </transition>
  </div>
</template>
<script>
export default {
  name: 'FieldSwitch',
  props: {
    title: String,
    subtitle: String,

    value: Boolean,
    message: String,
  },
}
</script>
<style lang="scss">
.field-switch {
  margin: 10px 0;
  &_header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
  }
}
</style>
