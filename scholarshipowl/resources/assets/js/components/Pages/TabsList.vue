<template>
  <div :class="['list-tabs', `list-tabs_${currentState}`]">
    <div class="list-tabs__wrp">
      <p class="list-tabs__item" v-for="state in states"
        @click="dispatch(state)" :key="state"
        :style="{width: elementWidth(states.length)}">
        {{ state.toLowerCase() }}
      </p>
    </div>
    <p class="list-tabs__marker" :style="Object.assign({}, {width: elementWidth(states.length)}, markerIndent)"></p>
  </div>
</template>

<script>
import { elementWidth } from "lib/utils/utils";
import { INBOX, SENT } from "store/mailbox";
import { NEW_SCHOLARSHIPS, SENT_SCHOLARSHIPS } from "store/scholarships";

const SENT_TAB_NAME = "SENT";

function dispatch(state) {
  if(!state) throw Error("Please provide tab name");

  const {moduleName, actionName} = this.action;

  if(!moduleName || !actionName) {
    throw Error("Please provide correct data");
  }

  let currentState = this.currentState;

  this.$store.dispatch(`${moduleName}/${actionName}`, state)
    .then(resolve => {
      if(currentState !== state) {
        this.$emit('state-changed', state);
      }
    })
}

export default {
  created() {
    const {moduleName} = this.action;

    if(moduleName === 'mailbox') dispatch.call(this, INBOX);
  },
  props: {
    states: { required: true, type: Array },
    action: { required: true, type: Object },
    state: { required: true, type: String }
  },
  computed: {
    markerIndent() {
      return {transform: `translateX(${(this.states.indexOf(this.currentState)) * 100}%)`};
    },
    currentState() {
      return this.$store.state[this.action.moduleName][this.state];
    },
  },
  methods: {
    dispatch(tabName) {
      dispatch.call(this, tabName);
    },
    elementWidth
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/functions';

  $bg-new: #4181ED;
  $bg-favorite: #B1D4FF;
  $bg-sent: #495D7B;
  $bg-marker: #2FD9B3;

  $white: white;

  .list-tabs {
    background-color: $bg-new;
    height: 58px;
    padding-left: 15px;
    padding-right: 15px;
    transition: background-color .2s ease;

    @include breakpoint($s) {
      height: 66px;
    }

    &_FAVORITES {
      background-color: $bg-favorite;
    }

    &_SENT {
      background-color: $bg-sent;
    }

    &__wrp {
      @include flexbox();
      @include justify-content(center);
      @include align-items(center);
      height: 100%;
    }

    &__marker {
      height: 6px;
      background-color: $bg-marker;
      position: relative;
      top: -6px;
      // transition: transform .2s ease;
    }

    &__item {
      font-size: 14px;
      line-height: lhem(19, 14);
      font-weight: 700;
      color: $white;
      text-align: center;
      text-transform: uppercase;
      cursor: pointer;
    }
  }
</style>