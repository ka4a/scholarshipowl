<template>
  <h3 class="table-price-period">{{ price }}
    <span v-if="period">/{{ period }}</span>
  </h3>
</template>

<script>
  export default {
    props: {
      price: {type: Number, required: true},
      period: {type: String, required: true}
    },
  }
</script>

<style lang="scss">
  .table-price-period {
    font-weight: 600;
    font-size: 19px;
    text-align: center;
    color: $gray-chateau;
    display: flex;
    justify-content: center;

    @include breakpoint($m) {
      font-size: 26px;
      align-items: center;
    }

    @include breakpoint($l) {
      font-size: 18px;
    }

    span {
      font-size: 11px;
      margin-left: 3px;
      font-weight: 500;

      @include breakpoint($m) {
        font-size: 15px;
      }

      @include breakpoint($l) {
        font-size: 10.5px;
      }
    }
  }
</style>