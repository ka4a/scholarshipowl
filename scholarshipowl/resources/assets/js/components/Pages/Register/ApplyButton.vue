<template lang="html">
  <button @click="$emit('click', $event)">
    <span class="btn__text"><slot>register for free</slot></span>
    <span v-if="applying" class="btn__loader"><i></i></span>
    <span v-else-if="arrow" class="btn__arrow"><i></i></span>
  </button>
</template>

<script>
export default {
  name: "RegisterButton",
  props: {
    arrow: { type: Boolean, default: true },
    applying: { type: Boolean, default: false }
  },
};
</script>
