<template lang="html">
  <div class="notification">
    <div class="notification__wrp">
      <h4 class="title">note:</h4>
      <p class="message" v-html="message" />
      <a href="#" class="close" @click="close(name)">&times;</a>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    message: { type: String, required: true },
    close: { type: Function, required: true },
    name: {type: String, required: true }
  }
};
</script>
