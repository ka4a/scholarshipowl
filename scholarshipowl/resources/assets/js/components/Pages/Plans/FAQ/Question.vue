<template>
  <span class="plans-faq-question">
    <span class="plans-faq-question__text">{{ question }}</span>
    <slot />
  </span>
</template>

<script>
  export default {
    props: {
      question: {type: String, required: true}
    }
  }
</script>

<style lang="scss">
  .plans-faq-question {
    font-weight: 600;
    font-size: 14px;
    line-height: 165.7%;
    letter-spacing: -0.02em;
    color: $mine-shaft;
    cursor: pointer;
    padding-top: 12px;
    padding-bottom: 12px;
    -webkit-tap-highlight-color: transparent;

    display: flex;
    justify-content: space-between;
    align-items: center;

    @include breakpoint($m) {
      font-size: 17px;
      padding-top: 14px;
      padding-bottom: 14px;
    }

    @include breakpoint($l) {
      padding-bottom: 10px;
      cursor: text;
    }
  }
</style>