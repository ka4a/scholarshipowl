<template>
  <div>
    <ul class="my-account-tabs"><li :class="['my-account-tabs__item', {'active': currentTab === tab}]" v-for="tab in tabs">
    <a :class="['my-account-tabs__link', {'active': currentTab === tab}]"
      @click.prevent="currentTab = tab; $emit('change-tab', tab)" href="#">{{ tab }}</a>
  </li></ul>
  </div>
</template>

<script>
  export default {
    props: {
      tabs: {type: Array, required: true},
      currentTab: {type: String, required: true}
    },
  }
</script>

<style lang="scss">

@import 'style-gide/breakpoints';

  $black: #2f2f2f;
  $white: #ffffff;
  $blue: $havelock-blue;
  $blue-more-light: #cdd7e8;
  $open-sans: 'Open Sans';

  $item-height: 50px;

  .my-account-tabs {
    border-radius: 1px 1px 0 0;
    display: flex;
    justify-content: center;
    overflow: hidden;

    &__item {
      flex: 1 1 auto;
      border-top: 1px solid $blue-more-light;
      border-left: 1px solid $blue-more-light;
      border-right: 1px solid $blue-more-light;
      width: 33%;

      &.active {
        border: 1px solid $blue;
      }

      & + & {
        margin-left: -1px;
      }
    }

    &__link {
      font-family: $open-sans;
      font-size: 14px;
      font-weight: bold;
      text-align: center;
      color: $black;
      display: block;
      height: 45px;
      line-height: 45px;
      text-transform: capitalize;

      @include breakpoint($s) {
        height: 50px;
        line-height: 50px;
        font-size: 16px;
      }

      @include breakpoint($l) {
        height: 60px;
        line-height: 60px;
      }

      &.active {
        color: $white;
        background-color: $blue;
      }
    }
  }
</style>