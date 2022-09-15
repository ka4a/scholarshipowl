<template lang="html">
  <span @click="toggle" :class="['icon', { 'icon-eye': isHidden, 'icon-no-eye': !isHidden }]"></span>
</template>

<script>
export default {
  props: { holder: { type: Object, required: true }},
  data: function() {
    return {
      isHidden: true
    };
  },
  methods: {
    toggle() {
      this.holder.type = (this.isHidden ? "text" : "password");
      this.isHidden = !this.isHidden;
    }
  }
};
</script>
