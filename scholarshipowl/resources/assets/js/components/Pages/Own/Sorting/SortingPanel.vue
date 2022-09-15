<template lang="html">
  <section class="sorting-panel">
    <ul class="sorting-panel__list">
      <sorting-unit :name-space="nameSpace" v-for="unit in unitList" :key="unit.state" :unit="unit" />
    </ul>
    <a href="#" @click.prevent="reset" class="sorting-panel__reset">clear sorting</a>
  </section>
</template>

<script>
import SortingUnit from "components/Pages/Own/Sorting/SortingUnit.vue";

export default {
  components: {
    SortingUnit
  },
  props: {
    unitList: {type: Array, required: true},
    nameSpace: {type: String, required: true}
  },
  methods: {
    reset() {
      this.$store.dispatch('list/resetSort', this.nameSpace);
      this.$emit('reset');
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

  $blue-more-lighter: #B9D5FA;

  // new style gide colors
  $blue-sg: #708FE7;
  $color2: #E2E9FF;
  $black: #2f2f2f;
  $blue-lighter: #ACCAF6;

  // sorting panel
  .sorting-panel {
    position: absolute;
    z-index: 2;
    width: 100%;
    background-color: $white;
    padding: 20px 15px;
    box-sizing: border-box;
    box-shadow: 2px 4px 12px rgba(53, 76, 109, 0.25);

    @include breakpoint($s $m) {
      padding-left: 25px;
      padding-right: 25px;
    }

    @include breakpoint($s + 1px) {
      max-width: 328px;
    }

    &__list {
      li + li {
        margin-top: 10px;
      }
    }

    &__reset {
      color: $blue-sg;
      text-transform: capitalize;
      text-align: center;
      font-size: 14px;
      line-height: 1.3em;
      display: block;
      margin-top: 20px;
      cursor: pointer;
      width: 100%;

      @include breakpoint($s) {
        margin-top: 22px;
      }
    }
  }
</style>
