<template>
  <div>
    <a
      ref="panelHolderToggle"
      :class="['panels-holder-toggle', {'active': isOpen}, {'disable': isDisable}]"
      @click.prevent="clickHolder"
      href="#">
      <i class="icon icon-filter"></i>
    </a>
    <div
      class="panels-holder"
      v-if="isOpen"
      v-click-outside="{
        exclude: ['panelHolderToggle'],
        handler: 'closeHolder'
      }"
      >
      <div class="panels-holder__wrp">
        <i @click="() => {isOpen = false; selected = items[0]}" class="panels-holder__back"></i>
        <a v-for="item in items" v-if="item" :key="item.label" :style="{width: elementWidth(items.length)}"
           :class="['panels-holder__ctrl', {active: item.componentName === selected.componentName}]"
           @click.prevent="selected = item" href="#">
          <span>{{ item.label }}</span>
        </a>
      </div>
      <component v-if="selected" @reset="clickHolder" @filter="(ev) => { $emit('filter', ev); clickHolder(); }"
        :is="selected.componentName" v-bind="componentSettings" />
    </div>
  </div>
</template>

<script>
import { elementWidth } from 'lib/utils/utils';
import SortingPanel from "components/Pages/Own/Sorting/SortingPanel.vue"
import FilterPanel from "components/Pages/Own/Filters.vue";

export default {
  components: {
    SortingPanel,
    FilterPanel
  },
  props: {
    items: {type: Array, required: true},
    isDisable: {type: Boolean, default: false}
  },
  data() {
    return {
      selected: this.items[0],
      isOpen: false
    };
  },
  computed: {
    componentSettings() {
      return this.selected.options || null
    }
  },
  methods: {
    elementWidth,
    clickHolder() {
      if(this.isDisable) return;

      this.isOpen = !this.isOpen;
    },
    closeHolder() {
      this.isOpen = false;
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';

  $blue: #708FE7;
  $blue-light: #ACCAF6;
  $dark: #2F2F2F;
  $grey: #b9b9b9;

  .panels-holder-toggle {
    font-size: 12px;
    color: $dark;
    cursor: pointer;

    &.active {
      color: $blue;
    }

    &.disable {
      color: $grey;
    }
  }

  .panels-holder {
    position: fixed;
    width: 100%;
    z-index: 3;
    left: 0;
    top: 58px;
    bottom: 0;
    background-color: rgba(53, 76, 109, 0.25);

    &__wrp {
      font-size: 13px;
      text-transform: capitalize;
      background-color: $blue;
      overflow: hidden;
      padding-left: 15px;
      padding-right: 15px;
      position: relative;
    }

    &__back {
      @include angle-bracket(left, 12px, 2px, white);
      z-index: 10;
      top: 0;
      bottom: 0;
      margin: auto;
      position: absolute;
    }

    &__ctrl {
      color: $blue-light;
      display: block;
      float: left;
      text-align: center;
      height: 36px;
      line-height: 36px;
      position: relative;

      @include breakpoint($s) {
        height: 50px;
        line-height: 50px;
      }

      &.active {
        color: white;
      }
    }
  }

</style>