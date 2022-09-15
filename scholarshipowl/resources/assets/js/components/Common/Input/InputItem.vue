<template>
  <div class="input-item">
    <h4 class="input-item__title" v-if="!$slots.title">{{ title }}</h4>
    <slot name="title" v-if="$slots.title" />
    <slot />
    <p class="input-item__error" v-if="errorMessage">{{ errorMessage }}</p>
  </div>
</template>

<script>
  export default {
    props: {
      title: {type: String, required: true},
      errorMessage: {type: String},
    }
  }
</script>

<style lang="scss">
  .input-item {
    &__title {
      @extend %input-title;
      margin-bottom: 8px;
    }

    &__error {
      @extend %input-text-error;
      margin-top: 5px;
    }
  }
</style>