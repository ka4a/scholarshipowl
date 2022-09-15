<template>
  <div class="filtered-counter">
    <span>{{ filtered }}</span>
    of
    <span>{{ original }}</span>
  </div>
</template>

<script>
  export default {
    props: {
      filtered: { type: Number, required: true },
      original: { type: Number, required: true }
    }
  }
</script>

<style lang="scss">
  $blue-light: #ACCAF6;

  .filtered-counter {
    text-align: center;
    color: $blue-light;
    font-size: 14px;
    font-family: 'Open Sans';
  }
</style>