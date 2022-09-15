<template>
  <div class="filter-list">
    <ul class="filter-list__wrp">
      <li class="filter-list__item">
        <h5 class="filter-list__title">Progress: </h5>
        <progress-filter style="margin-right: auto"/>
      </li>
      <li class="filter-list__item filter-list__item_deadline">
        <h5 class="filter-list__title">Deadline: </h5>
        <deadline class="filter-list__filter"/>
      </li>
      <li class="filter-list__item filter-list__item_amount">
        <h5 class="filter-list__title">Amount: </h5>
        <range-filter class="filter-list__filter" />
      </li>
      <li class="filter-list__item">
        <h5 class="filter-list__title">Requirements needed:</h5>
        <essay-filter />
      </li>
      <li class="filter-list__item">
        <h5 class="filter-list__title">Recurring scholarships:</h5>
        <recurrent-filter />
      </li>
      <button @click="reset" class="reset-filters">clear filters</button>
    </ul>
    <button @click="apply" class="filter-list__label">apply filters</button>
  </div>
</template>

<script>
import { mapGetters, mapActions } from "vuex";

import Deadline from "components/Pages/Scholarships/Filters/Deadline.vue";
import ProgressFilter from "components/Pages/Scholarships/Filters/Progress.vue";
import RangeFilter from "components/Pages/Scholarships/Filters/RangeFilter.vue";
import EssayFilter from "components/Pages/Scholarships/Filters/EssayFilter.vue";
import RecurrentFilter from "components/Pages/Scholarships/Filters/RecurrentFilter.vue";

export default {
  components: {
    ProgressFilter,
    Deadline,
    RangeFilter,
    EssayFilter,
    RecurrentFilter
  },
  props: {
    nameSpace: {type:String, required: true}
  },
  data() {
    return {
      top: false
    };
  },
  computed: {
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
    }),
  },
  methods: {
    ...mapActions({
      applyFilter: "list/applyFilter",
      resetFilter: "list/resetFilter"
    }),
    reset() {
      this.resetFilter(this.nameSpace);
      this.$emit("filter", {ev: "filter-reset"});
    },
    apply() {
      this.applyFilter(this.nameSpace);
      this.$emit("filter", {ev: "filter-apply"});
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';

  // variables
  $dark: #333;
  $white: #fff;
  $grey: #797979;
  $grey-lighter: #7F7F7F;
  $grey-more-lighter: #D8D8D8;
  $blue: #4181ED;
  $blue-lighter: #ACCAF6;
  $blue-more-lighter: #B9D5FA;

  // new style gide colors
  $blue-sg: #708FE7;

  // filters list
  .filter-list {
    list-style: none;
    margin: 0;
    background-color: #fff;
    width: 100%;
    box-sizing: border-box;
    box-shadow: 2px 4px 12px rgba(53, 76, 109, 0.25);

    @include breakpoint($s + 1px) {
      max-width: 328px;
    }

    &__title {
      font-size: 13px;
      font-weight: 700;
      margin-right: 12px;
    }

    &__item {
      @include flexbox();
      @include justify-content(space-between);
      @include align-items(center);
      width: 100%;
      padding: 20px 15px 0 15px;
      box-sizing: border-box;

      @include breakpoint($s) {
        padding-top: 25px;
      }

      &_deadline {
        padding-top: 22px;
      }

      &_amount {
        margin-top: 18px;
      }
    }

    &__label {
      font-size: 13px;
      color: $white;
      text-align: center;
      text-transform: uppercase;
      font-weight: 700;
      line-height: 36px;
      margin-top: 20px;
      background-color: $blue-sg;
      width: 100%;
      height: 36px;

      @include breakpoint($s) {
        font-size: 16px;
        height: 50px;
        line-height: 50px;
      }

      &:hover {
        background-color: darken($blue-sg, 5);
      }
    }
  }

  // reset filters button
  .reset-filters {
    color: $blue-sg;
    text-transform: capitalize;
    text-align: center;
    font-size: 14px;
    line-height: 1.3em;
    margin-top: 20px;
    cursor: pointer;
    width: 100%;
  }

  // range slider
  .vue-slider-component {
    width: 100% !important;
  }

  // attachemnts
  .attachments {
    position: relative;
    &__select {
      border: 1px solid #d8d8d8;
      border-radius: 3px;
      width: 75px;
      height: 34px;
      padding: 5px 28px 5px 15px;
      font-size: 14px;
      outline: none;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      background: #fff;
      text-transform: capitalize;

      option {
        text-transform: capitalize;
      }
    }

    &:after {
      content: "";
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 5px 5px 0;
      border-color: #333 transparent transparent;
      display: block;
      position: absolute;
      right: 10px;
      top: 16px;
    }
  }
</style>