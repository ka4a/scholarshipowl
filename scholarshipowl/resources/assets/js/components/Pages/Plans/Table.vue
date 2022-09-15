<template>
  <section class="plans-table">
    <div class="plans-table__list">
      <h3 v-if="xl || xxl" class="plans-table__title" v-html="title"></h3>
      <p v-if="xl || xxl" class="plans-table__list-item" v-for="(val, id) in options">
        <span class="plans-table__list-item-text" v-html="options[id].text"></span>
      </p>
    </div>
    <div class="plans-table-grid plans-table__grid">
      <Package v-if="processedPackages && optionsPerPackage"
        v-for="pac in processedPackages"
        :class="['plans-table__package', {'active': activePackageId === pac.package_id}]"
        :key="pac.package_id"
        :package="pac"
        :options="optionsPerPackage[pac.package_id]"
        :selectedPackage="selectedPackage"
        @click="pack => $emit('package', pack)" />
    </div>
    <ShowMore v-if="isOnePackageIntoViewPort && isShowOnePackage"
      class="plans-table__more"
      @click.native="showMoreHandler" />
  </section>
</template>

<script>
  import { mapState, mapGetters } from "vuex";
  import { PACKAGES } from "store/paymentSet";
  import mixpanel from "lib/mixpanel";
  import {SEE_MORE_MEMBERSHIP_OPT_CLK, PACKAGE_BTN_CLK} from "lib/mixpanel";
  import Package from "components/Pages/Plans/Table/Package.vue";
  import ShowMore from "components/Pages/Plans/Table/ShowMore.vue";

  export default {
    components: {
      Package,
      ShowMore
    },
    props: {
      selectedPackage: {type: Object, required: true}
    },
    data() {
      return {
        isShowOnePackage: false,
        activePackageId: undefined
      }
    },
    watch: {
      mobileSpecialOfferOnly(val) {
        this.isShowOnePackage = val;
      },
      processedPackages() {
        this.activePackageId = this.popularPackageId;
      }
    },
    computed: {
      ...mapGetters("screen", ["resolution", "xl", "xxl"]),
      ...mapGetters("paymentSet", ["options", "optionsPerPackage", "title", "mobileSpecialOfferOnly"]),
      ...mapState({packages: state => state.paymentSet[PACKAGES]}),
      isOnePackageIntoViewPort() {
        return this.resolution < 560;
      },
      popularPackageId() {
        return this.processedPackages.find(pack => !!pack.is_marked).package_id
      },
      processedPackages() {
        if(!this.packages) return null;

        if(this.isOnePackageIntoViewPort
          && this.isShowOnePackage) {
          return [this.packages[0]]
        }

        return this.packages;
      }
    },
    methods: {
      showMoreHandler() {
        this.isShowOnePackage = false;

        mixpanel.track(SEE_MORE_MEMBERSHIP_OPT_CLK)
      }
    }
  }
</script>

<style lang="scss">
  .plans-table {
    padding-bottom: 14px;

    @include breakpoint($l) {
      padding-bottom: 70px;
    }

    @include breakpoint($l) {
      margin-top: 69px;
      padding-top: 15px;
      padding-bottom: 27px;
      display: flex;
    }

    &__list {
      width: 340px;
      margin-top: auto;

      @include breakpoint($xl) {
        min-width: 340px;
      }
    }

    &__title {
      font-weight: bold;
      font-size: 32px;
      line-height: 1.125em;

      @include breakpoint($l) {
        margin-bottom: 15px;
      }

      @include breakpoint($l $xl - 1px) {
        width: 66%;
      }

      @include breakpoint($xl) {
        margin-bottom: 33px;
      }
    }

    &__grid {
      display: grid;
      grid-template-columns: 1fr;
      box-sizing: border-box;

      @include breakpoint(max-width $l - 1px) {
        margin-top: 26px;
      }

      @include breakpoint(max-width 559px) {
        grid-row-gap: 16px;
      }

      @include breakpoint(560px) {
        grid-template-columns: 48% 48%;
        grid-column-gap: 30px;
        grid-row-gap: 14px;
      }

      @include breakpoint($l) {
        grid-template-columns: 24% 24% 24% 24%;
        grid-column-gap: 8px;
        min-width: 580px;
        position: relative;
        margin-top: auto;
      }

      @include breakpoint($xl) {
        min-width: 746px;
      }
    }

    &__list-item {
      @extend %style-formating-tags;

      @extend %plans-basic-paragraph-text;
      font-size: 17px;
      line-height: 24px;
      height: 72px;
      display: flex;
      align-items: center;

      @include breakpoint($l) {
        height: 89px;
        box-sizing: border-box;
        padding-right: 15px;
      }

      @include breakpoint($xl) {
        padding-right: 40px;
      }

      & + & {
        border-top: 1px solid $mystic;
      }
    }

    &__list-item-text {
      display: block;
      max-width: 307px;

      @include breakpoint($xl) {
        max-width: 375px;
      }
    }

    &__package {
      @include breakpoint(max-width 599px) {
        max-width: 280px;
        margin-left: auto;
        margin-right: auto;
      }

      @include breakpoint($l) {
        margin-top: 27px;
      }
    }

    &__more {
      text-align: center;
      margin-top: 20px;
    }
  }
</style>