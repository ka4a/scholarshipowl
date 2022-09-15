<template lang="html">
  <button class="btn-orange-arrow-fm" @click="$emit('click', $event)">
    <span v-if="arrow" class="arrow-button">
      <span class="arrow-button__wrp">
        <!-- eslint-disable-next-line vue/require-v-for-key -->
        <span v-for="n in 11" class="pixel" :class="'pixel_' + n"></span>
      </span>
    </span>
    <slot>register for free</slot>
  </button>
</template>

<script>
export default {
  name: "RegisterButton",
  props: {
    arrow: {type: Boolean, default: false}
  },
};
</script>
