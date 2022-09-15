<template>
  <div class="carousel-element">
    <p class="carousel-element__name">{{ name }}</p>
    <p class="carousel-element__amount">{{ amount }}</p>
  </div>
</template>

<script>
  export default {
    props: {
      name: {type: String, required: true},
      amount: {type: String, required: true}
    }
  }
</script>

<style lang="scss">
  %base-font-mixin {
    font-size: 10px;
    color: $mine-shaft;
    line-height: 1.3em;

    @include breakpoint($m) {
      font-size: 14.7px;
    }
  }

  .carousel-element {
    width: 100%;
    padding-right: 9%;
    padding-left: 5%;
    background-color: $white;
    border-radius: 1.5px;
    box-sizing: border-box;
    display: flex;
    align-items: center;
    justify-content: space-between;
    box-shadow: 0px 4.01444px 20.0722px #D7DFE7;

    &__name {
      @extend %base-font-mixin;
      width: 354px;
      overflow: hidden;
      white-space: nowrap;
      text-overflow: ellipsis;
      margin-right: auto;
    }

    &__amount {
      @extend %base-font-mixin;
      font-weight: 700;
      margin-left: 8%;
      flex: 1 1 auto;
      text-align: right;
    }
  }
</style>