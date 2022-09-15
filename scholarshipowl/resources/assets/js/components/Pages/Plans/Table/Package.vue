<template>
  <div class="plans-package-wrp">
    <div :class="['plans-package', {'plans-package_popular': isPopular}]">
      <span v-if="isPopular" class="plans-package-popular plans-package__popular">most popular</span>
      <ImageHolder class="plans-package__img"
        :package-icon="package.icon"
        :package-id="package.package_id" />
      <p class="plans-package__name">{{ package.name }}</p>
      <p v-if="isShowReccurentPeriod" class="plans-package__period">{{ reccurentPeriod }}</p>
      <PeriodPrice class="plans-package__price" :price="price.price" :period="price.period"  />
      <Btn :class="['plans-package__btn', {'plans-btn_popular': isPopular}]"
        @click.native="$emit('click', { p: package, el: $el })"
        :showLoader="isLoaderShown"
        sizeLoader="s"
        :label="package.button_text" size="m" />
      <div class="plans-package__options">
        <Option class="plans-package__item"
          v-for="opt in optns" :key="opt.text" :opt="opt" />
      </div>
    </div>
  </div>
</template>

<script>
  import { mapGetters } from "vuex";
  import ImageHolder from "components/Pages/Plans/Table/Package/ImageHolder.vue"
  import PeriodPrice from "components/Pages/Plans/Table/PeriodPrice.vue";
  import Option from "components/Pages/Plans/Table/Package/Option.vue";
  import Btn from "components/Common/Buttons/ButtonCustom.vue";
  import { getRecurrentTypeMessage, formatPricePeriod } from "lib/utils/format";

  const EXPIRATION_TYPES = {
    RECURRENT: "recurrent"
  }

  export default {
    components: {
      ImageHolder,
      PeriodPrice,
      Option,
      Btn
    },
    props: {
      package: {type: Object, required: true},
      options: {type: Array, required: true},
      selectedPackage: {type: Object, required: true}
    },
    computed: {
      ...mapGetters("screen", ["xl", "xxl", "xs", "s", "m"]),
      ...mapGetters("payment", ["brainTreePMIsInitialized"]),
      optns() {
        if(this.xl || this.xxl) {
          return this.options.map(item => ({status : item.status}));
        }

        if(this.s || this.m) {
          return this.options.filter(item => !!item.status);
        }

        return this.options;
      },
      isPopular() {
        return this.package.is_marked;
      },
      // Logic is copyied from resources/views/includes/widget-package.blade.php
      // TODO define requirements from product side
      isShowReccurentPeriod() {
        const discountPrice = Number(this.package.discount_price);

        if(isNaN(discountPrice)) throw Error("Not possible value");

        return discountPrice === 0;
      },
      reccurentPeriod() {
        return getRecurrentTypeMessage(
          this.package.expiration_period_type,
          this.package.expiration_period_value
        )
      },
      price() {
        return formatPricePeriod(this.package, EXPIRATION_TYPES.RECURRENT)
      },
      isLoaderShown() {
        return !this.brainTreePMIsInitialized && this.selectedPackage
          && this.selectedPackage.package_id === this.package.package_id
      }
    }
  }
</script>

<style lang="scss">
  .plans-package-popular {
    font-weight: bold;
    font-size: 11px;
    line-height: 24px;
    color: $white;

    text-align: center;
    letter-spacing: 1px;
    text-transform: uppercase;

    background-color: $havelock-blue;
    height: 22px;
    width: 122px;

    background: $havelock-blue;
    border-radius: 5px 5px 0 0;

    @include breakpoint($m) {
      width: 250px;
      height: 25px;
      font-size: 13px;
    }

    @include breakpoint($l) {
      width: 128px;
      font-size: 11px;
      height: 23px;
    }
  }

  .plans-package-wrp {
    position: relative;

    @include breakpoint(560px) {
      margin-top: 24px;
    }
  }

  .plans-package {
    background: $white;
    box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.05);
    border-radius: 8px;
    position: relative;
    padding-top: 22px;
    padding-bottom: 24px;

    @include breakpoint($l) {
      padding: 22px 0 0;
    }

    @include breakpoint(max-width $l - 1px) {
      padding: 23px 17px;
    }

    &__img {
      text-align: center;
    }

    &__name {
      font-weight: bold;
      font-size: 18px;
      text-align: center;
      margin-top: 5px;

      @include breakpoint($m) {
        margin-top: 10px;
        font-size: 24px;
      }

      @include breakpoint($l) {
        font-size: 16px;
        margin-top: 5px;
      }
    }

    &__popular {
      position: absolute;
      top: -22px; left: 0; right: 0;
      margin-left: auto;
      margin-right: auto;
    }

    &__period {
      font-weight: 300;
      font-size: 13px;
      color: $gray-chateau;

      text-align: center;
      margin-top: 10px;

      @include breakpoint($m) {
        font-size: 17px;
      }

      @include breakpoint($l) {
        font-size: 12px;
      }
    }

    &__item {
      @include breakpoint(max-width $l - 1px) {
        margin-top: 15px;
      }

      @include breakpoint($l) {
        height: 89px;
        display: flex;
        align-items: center;
        justify-content: center;
        box-sizing: border-box;

        & + & {
          border-top: 1px solid $mystic;
        }
      }
    }

    &__price {
      margin-top: 10px;

      @include breakpoint($l) {
        margin-top: 5px;
      }
    }

    &__btn {
      &.button-custom {
        margin-top: 13px;
        border-radius: 1px;
        background: white;
        border: 1px solid $coral;
        padding-left: 5px;
        padding-right: 5px;

        @include breakpoint($m) {
          margin-top: 22px;
        }

        @include breakpoint($l) {
          margin-top: 17px;
          height: 47px;
          margin-left: 15px;
          margin-right: 15px;
        }

        > span {
          font-size: 14px;
          color: $coral;
        }
      }

      &.plans-btn_popular.button-custom {
        background: $turquoise;
        border-color: $turquoise;

        > span {
          color: white;
        }
      }
    }

    &__options {
      margin-top: 16px;
      padding-right: 10px;

      @include breakpoint($m) {
        margin-top: 30px;
        margin-right: 8px;
        margin-left: 2px;
        padding-right: 0;
      }

      @include breakpoint($l) {
        margin: 0 !important;
      }
    }

    &_popular {
      @include breakpoint(max-width 559px) {
        margin-top: 24px !important;
      }
    }
  }
</style>