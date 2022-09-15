<template lang="html">
  <div :class="$bem.block(className, { 'enabled': value, 'disabled': !value })"
    @click.prevent="$emit('input', !$refs.input.checked)">
    <label>
     <input ref="input" type="checkbox" :checked="value" :name="name">
     <span :class="$bem.element(className, 'lever')"></span>
    </label>
    <span :class="$bem.element(className, 'state', 'true')">{{ yesLabel }}</span>
    <span :class="$bem.element(className, 'state', 'false')">{{ noLabel }}</span>
 </div>
</template>

<script>
export default {
  name: "InteractionSwitcher",
  props: {
    value:      {required: true},
    name:       {type: String, required: true},
    className:  {type: String, default: "switch"},
    yesLabel:   {type: String, default: "Yes"},
    noLabel:    {type: String, default: "No"},
  }
};
</script>

<style lang="scss">

</style>
