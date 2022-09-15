<template>
  <li @click="setFilter(unit.state)">
    <a href="#" :class="['sorting-unit', {'selected': sorted && activeItemName === unit.state}]">
      <span><i :class="unit.iconClass"></i>{{ unit.state.toLowerCase() }}</span>
      <span>
        <i :class="['sorting-unit__state-arrow-bottom',
          {'active' : sorted && unit.state === activeItemName && order === ORDER_ASC}]"></i>
        <i :class="['sorting-unit__state-arrow-top',
          {'active' : sorted && unit.state === activeItemName && order === ORDER_DESC}]"></i>
      </span>
    </a>
  </li>
</template>

<script>
import {ORDER_ASC, ORDER_DESC} from "lib/utils/sort";

export default {
  props: {
    unit: {type: Object, required: true},
    nameSpace: {type: String, required: true}
  },
  data() {
    return {
      ORDER_ASC,
      ORDER_DESC
    }
  },
  computed: {
    order() {
      return this.$store.state.list[this.nameSpace].sort.order;
    },
    activeItemName() {
      return this.$store.state.list[this.nameSpace].sort.sortBy;
    },
    sorted() {
      return this.$store.state.list[this.nameSpace].sorted;
    }
  },
  methods: {
    setFilter(sortBy) {
      let order = this.order,
          nameSpace = this.nameSpace;

      if(this.sorted && this.activeItemName === this.unit.state) {
        order = this.order === ORDER_ASC ? ORDER_DESC : ORDER_ASC;
      }

      this.$store.dispatch('list/sortList', {
        nameSpace,
        sortBy,
        order
      });
    }
  }
}
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
  $blue-darker: #6FA5FF;

  $blue-more-lighter: #B9D5FA;

  // new style gide colors
  $blue-sg: #708FE7;
  $color2: #E2E9FF;
  $black: #2f2f2f;
  $blue-lighter: #ACCAF6;

  .sorting-unit {
    // text
    color: $black;
    font-size: 12px;
    text-transform: capitalize;

    @include flexbox();
    @include justify-content(space-between);
    @include align-items(center);

    height: 40px;
    border: 0.5px solid $color2;
    border-radius: 2px;
    box-sizing: border-box;
    padding-left: 15px;
    padding-right: 15px;

    @include breakpoint($s) {
      font-size: 13px;
      height: 54px;
    }

    &:focus {
      text-decoration: none;
      color: $black;
    }

    &.selected {
      background-color: #F2F7FE;

      .icon {
        color: $blue-darker;
      }
    }

    .s1,
    .icon {
      margin-right: 10px;
      color: $blue-lighter;
      font-size: 14px;
    }

    .s1 {
      vertical-align: bottom;
    }

    .icon {
      vertical-align: middle;
    }

    // TODO remvoe it after standartiszation icon sizes
    .icon-cursor {
      font-size: 19px;
      margin-right: 5px;
    }

    &__state-arrow-bottom {
      @include angle(bottom, 5px, $black);

      &.active {
        @include angle(bottom, 5px, $blue-sg);
      }
    }

    &__state-arrow-top {
      @include angle(top, 5px, $black);
      margin-left: 10px;

      &.active {
        @include angle(top, 5px, $blue-sg);
      }
    }
  }
</style>