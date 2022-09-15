<template>
  <div class="badge-recurrent">
    <span class="recurrent-shape"></span>
    <span class="icon icon-sync" @click.stop v-tooltip.bottom="{ content: `This is renewable scholarship. Apply to it automaticaly multiple times by updating Reccurence settings on My Profile area.`, classes: ['v-tooltip'], offset: 5, trigger: 'click hover' }"></span>
  </div>
</template>

<script>
import Vue from "vue";
import { VTooltip } from "v-tooltip";

Vue.directive("v-tooltip", VTooltip);

export default {

};
</script>

<style lang="scss">
  $white: white;

  .badge-recurrent {
    width: 38px;
    height: 38px;
    position: absolute;
    left: 0; top: 0;

    .recurrent-shape {
      position: absolute;
      left: 0; top: 0;
      width: 0;
      height: 0;
      border-style: solid;
      border-width: 38px 38px 0 0;
      border-color: #354c6d transparent transparent transparent;
    }

    .icon-sync {
      position: absolute;
      color: $white;
      top: 5px;
      left: 5px;
      font-size: 15px;
      cursor: pointer;
    }
  }

  .v-tooltip {
      $tooltip-background: #354c6d;

      font-size: 9px;
      font-family: 'Open Sans', sans-serif;
      line-height: 1.5;
      color: #fff;
      text-align: left;
      text-decoration: none;
      margin-top: -20px;
      position: absolute;
      left: 175px !important;
      box-sizing: border-box;
      padding: 6px 8px;
      z-index: 1070;
      max-width: 157px;
      background-color: $tooltip-background;
      border-radius: 2px;
      box-shadow: 0 0 4px rgba(0, 0, 0, 0.17);

      &:before {
        position: absolute;
        content: '';

        left: -28px;
        top: 15%;

        width: 0;
        height: 0;
        border-style: solid;
        border-width: 0 28px 19px 0;
        border-color: transparent $tooltip-background transparent transparent;
      }
    }
</style>