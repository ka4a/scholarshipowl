<template>
  <section class="summary-pay">
    <h5 class="summary-pay__title summary-pay-title">You've selected</h5>
    <h3 class="summary-pay__name summary-pack-name">{{ package.name }}</h3>
    <PricePeriod class="summary-pay__price" :price="price.price" :period="price.period" />
    <p class="summary-pay-period summary-pay__period">{{ recurrentMessage }}</p>
    <p class="summary-pay__delimiter one"></p>
    <p class="summary-pay-expl summary-pay__expl"
      v-if="package.summary_description_full_text"
      v-html="package.summary_description_full_text"></p>
    <p class="summary-pay__delimiter two"></p>
  </section>
</template>

<script>
  import PricePeriod from "components/Common/Modals/Payment/Summary/PricePeriod.vue";
  import { getRecurrentTypeMessage, formatPricePeriod } from "lib/utils/format";

  export default {
    components: {
      PricePeriod,
    },
    props: {
      package: {type: Object, required: true}
    },
    computed: {
      price() {
        return formatPricePeriod(this.package, "recurrent")
      },
      recurrentMessage() {
        return getRecurrentTypeMessage(
          this.package.expiration_period_type,
          this.package.expiration_period_value
        )
      }
    }
  }
</script>

<style lang="scss">
  .summary-pay-title {
    color: $gray-chateau;
    font-size: 15px;
    font-weight: 600;
  }

  .summary-pack-name {
    font-size: 21px;
    font-weight: 700;

    @include breakpoint($l) {
      font-size: 24px;
    }
  }

  .summary-pay-period {
    font-size: 14px;
    color: $gray-chateau;

    @include breakpoint($m) {
      font-size: 16px;
    }

    @include breakpoint($m) {
      font-size: 17px;
    }
  }

  .summary-pay-expl {
    font-size: 12px;
    line-height: 20px;
    max-width: 278px;

    b, strong {
      font-weight: 600;
    }

    em, i {
      font-style: italic;
    }

    u {
      text-decoration: underline;
    }
  }

  .summary-pay {
    text-align: center;
    padding: 35px 15px 15px;

    @include breakpoint($m) {
      padding: 55px 0 0 30px;
    }

    @include breakpoint($l) {
      padding: 40px 0 0 45px;
    }

    &__name {
      margin-top: 10px;

      @include breakpoint($l) {
        margin-top: 15px;
      }
    }

    &__price {
      margin-top: 10px;

      @include breakpoint($l) {
        margin-top: 15px;
      }
    }

    &__period {
      margin-top: 5px;

      @include breakpoint($l) {
        margin-top: 10px;
      }
    }

    &__delimiter {
      height: 1px;
      background-color: $hawkes-blue;

      &.one {
        margin-top: 15px;

        @include breakpoint($l) {
          margin-top: 20px;
        }
      }

      &.two {
        display: none;

        @include breakpoint($m) {
          display: block;
          margin-top: 10px;
        }

        @include breakpoint($l) {
          margin-top: 20px;
        }
      }
    }

    &__expl {
      margin-top: 10px;
      margin-left: auto;
      margin-right: auto;

      @include breakpoint($l) {
        margin-top: 15px;
      }
    }
  }
</style>