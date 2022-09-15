<template>
  <p class="item-attribute">
    <span class="item-attribute__title">{{ title }}</span>
    <span class="item-attribute__text">{{ text }}</span>
  </p>
</template>

<script>
export default {
  props: {
    title: {type: String, required: true},
    text: {type: String, required: true},
  }
};
</script>

<style lang="scss">
  $black: #000;

  .item-attribute {

    &__title {
      color: $black;
      font-size: 12px;
      font-weight: 600;
      text-transform: capitalize;
    }

    &__text {
      font-size: 12px;
      font-weight: 400;
      line-height: 1.3em;
    }
  }
</style>