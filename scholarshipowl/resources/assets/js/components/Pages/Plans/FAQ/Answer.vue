<template>
  <p class="plans-faq-answer">{{ answer }}</p>
</template>

<script>
  export default {
    props: {
      answer: {type: String, required: true}
    }
  }
</script>

<style lang="scss">
  .plans-faq-answer {
    font-size: 14px;
    line-height: 170.7%;
    letter-spacing: -0.02em;
    color: $mine-shaft;

    padding:0 22px 10px 0;

    @include breakpoint($m) {
      padding-bottom: 27px;
      padding-right: 27px;
    }

    @include breakpoint($l) {
      width: auto;
      padding-bottom: 10px;
      border-bottom: 1px solid $link-water;
    }
  }

  .accordion-vue__repres {
    border-bottom: 1px solid $link-water;
  }
</style>