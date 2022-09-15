<template>
  <div :class="['item-status', classByStatus, {'filled': filled}]">
    <span class="item-status__text">{{ text }}</span>
  </div>
</template>

<script>
export default {
  props: {
    text: {type: String, default: ""},
    status: {type: String, default: "1"},
    filled: {type: Boolean, default: true}
  },
  computed: {
    classByStatus() {
      if(this.status.length === 1) {
        return "s" + this.status;
      }

      return this.status.replace(' ', '-').toLowerCase();
    }
  }
};
</script>

<style lang="scss">
  $white: #FFFFFF;
  $blue-lighter: #6FA5FF;
  $blue-light: #C6E0FF;
  $blue-grey: #8A9CB2;
  $blue-dark: #3B5998;
  $green: #6FCF97;
  $green-light: #53DD6C;
  $burgundy: #D21C1C;
  $yellow: #F5BB00;
  $purple: #992FA6;
  $dark: #171219;
  $orange: #EE5622;
  $orange-light: #F08424;
  $pink-dark: #C00982;
  $brown: #6C464E;

  $status-color: (
    s1: $blue-lighter,
    s2: $orange-light,
    s3: $green,
    received: $blue-light,
    under-review: $blue-grey,
    accepted: $green-light,
    sent: $blue-dark,
    declined: $burgundy,
    won: $yellow,
    awarded: $purple,
    missed: $dark,
    choosing-winner: $orange,
    winner-chosen: $pink-dark,
    draw-closed: $brown,
  );

  .item-status {
    width: 59px;
    height: 59px;
    border-radius: 50%;
    background-color: transparent;
    box-sizing: border-box;
    display: table;
    border: solid 1px transparent;

    &__text {
      font-size: 9px;
      text-align: center;
      text-transform: uppercase;
      font-weight: bold;
      display: table-cell;
      vertical-align: middle;
      line-height: 1.3em;
    }

    &.filled {
      .item-status__text {
        color: $white !important;
      }
    }

    &.s1,
    &.s2,
    &.s3 {
      .item-status__text {
        font-size: 14px;
      }
    }

    @each $status, $color in $status-color {
      &.#{$status} {
        border-color: $color;

        .item-status__text {
          color: $color;
        }
      }

      &.filled.#{$status} {
        background-color: $color;
      }
    }
  }
</style>