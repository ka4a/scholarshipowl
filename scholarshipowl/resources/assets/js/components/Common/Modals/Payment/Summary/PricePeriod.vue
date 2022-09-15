<template>
  <p class="price-period">
    <span>{{price}}</span>
    <span v-if="period" class="price-period__period">/{{period}}</span>
  </p>
</template>

<script>
  export default {
    props: {
      price: {type: String, required: true},
      period: {type: String, required: true}
    }
  }
</script>

<style lang="scss">
  .price-period {
    font-size: 20px;
    font-weight: 700;
    display: flex;
    justify-content: center;
    align-items: center;

    @include breakpoint($l) {
      font-size: 21px;
      align-items: flex-start;
    }

    &__period {
      font-size: 60%;
    }
  }
</style>

