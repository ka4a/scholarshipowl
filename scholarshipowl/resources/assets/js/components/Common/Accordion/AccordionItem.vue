<template lang="html">
  <div class="fm-accordion-item" @click="$emit('click', $event)">
      <i class="fm-accordion-indicator" :class="{ 'rotated' : active }"></i>
      <h2 v-if="title" class="fm-accordion-title">{{ title }}</h2>
      <slot name="before"/>
      <div v-show="active">
        <slot />
      </div>
      <slot name="after" />
  </div>
</template>
<script>
export default {
  name: "AccordionItem",
  props: {
    accordionName:  { type: String, required: true },
    title:          { type: String, default: null },
  },
  data () {
    return {
      active: false,
    };
  }
};
</script>
