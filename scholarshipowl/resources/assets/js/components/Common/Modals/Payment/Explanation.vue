<template>
  <p class="payment-explanation">
    <span class="payment-explanation__text">ScholarshipOwl does not guarantee the receipt of any scholarship. Upgrading to premium membership does not increase your chances of receiving a specific scholarship per se, but may increase the number of scholarships you can apply for in less time.</span>
  </p>
</template>

<style lang="scss">
  .payment-explanation {
    background-color: $polar;
    padding-top: 12px;
    padding-bottom: 16px;

    &__text {
      font-size: 10px;
      line-height: 17px;
      color: $aluminium;
      display: block;
      margin-left: auto;
      margin-right: auto;
      text-align: center;

      @include breakpoint(max-width $s - 1px) {
        max-width: 284px;
      }

      @include breakpoint($m) {
        text-align: left;
        font-size: 12px;
        padding: 0 30px 0 40px;
      }

      @include breakpoint($xl) {
        padding: 0 62px 0 34px;
      }
    }
  }
</style>