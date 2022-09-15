<template>
  <div class="user-scholarship-avatar">
    <img class="user-scholarship-avatar__img" :src="path" :alt="alt">
  </div>
</template>

<script>
export default {
  props: {
    path: {type: String, required: true},
    alt: {type: String, required: true},
  }
};
</script>

<style lang="scss">
  .user-scholarship-avatar {
    max-width: 160px;
    max-height: 160px;
    border-radius: 50%;
    overflow: hidden;

    &__img {
      display: block;
      width: 100%;
    }
  }
</style>