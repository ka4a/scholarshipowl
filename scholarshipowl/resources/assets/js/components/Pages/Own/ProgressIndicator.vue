<template>
  <aside class="progress-indicator">
    <span class="progress-indicator-line" :style="{ width : completeness + '%'}"></span>
  </aside>
</template>

<script>
  export default {
    props: {
      completeness: { type: Number, required: true },
    }
  }
</script>

<style lang="scss">
  .progress-indicator {
    width: 100%;
    position: relative;
    background-color: #CEE2FF;

    &-line {
      background-color: #2FD9B3;
      display: block;
    }
  }
</style>