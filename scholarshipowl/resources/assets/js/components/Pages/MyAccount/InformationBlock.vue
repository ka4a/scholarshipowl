<template>
  <div :class="['my-account-info-blk',
    {'my-account-info-blk_horizontal-skin' : skin === 'horizontal'},
    {'my-account-info-blk_combine-skin' : skin === 'combine'}]">
    <div class="my-account-info-blk__img">
      <img class="" v-if="img" :src="img">
    </div>
    <slot v-if="skin === 'combine' && (l || xl || xxl)" name="count"></slot>
    <div>
      <h3 class="my-account-info-blk__title" v-if="title">{{ title }}</h3>
      <p class="my-account-info-blk__text" v-if="text">{{ text }}</p>
    </div>
    <slot v-if="skin === 'combine' && !(l || xl || xxl)" name="count"></slot>
  </div>
</template>

<script>
  import { mapGetters } from "vuex";

  export default {
    props: {
      title: {type: String, required: true},
      text: {type: String },
      img: {type: String, required: true},
      skin: {type: String, default: 'horizontal'}
    },
    computed: {
      ...mapGetters({
        l: "screen/l",
        xl: "screen/xl",
        xxl: "screen/xxl",
      })
    }
  }
</script>

<style lang="scss">

@import 'style-gide/breakpoints';

  $black: #2f2f2f;
  $white: #ffffff;
  $blue: #708fe7;
  $blue-more-light: #cdd7e8;

  $dark-grey-blue: #354c6d;
  $grey: #a8a8a8;
  $grey-lighter: #e1e5ea;
  $white: #ffffff;

  $open-sans: 'Open Sans';

  $my-account-info-blk: 'my-account-info-blk';

  .#{$my-account-info-blk} {
    box-sizing: border-box;
    border-radius: 2px;
    background-color: #ffffff;
    height: 84px;
    padding: 15px;

    &__img {
      > img {
        display: block;
        width: 100%;
      }

      width: 68px;
      margin-right: 15px;

      @include breakpoint($s) {
        width: 115px;
      }
    }

    &__title {
      font-family: $open-sans;
      font-size: 14px;
      font-weight: 600;
      line-height: 1.2em;
      color: $dark-grey-blue;

      @include breakpoint($s) {
        font-size: 16px;
      }
    }

    &__text {
      font-family: $open-sans;
      font-size: 12px;
      line-height: 1.2em;
      color: $grey;
      margin-top: 5px;

      @include breakpoint($m) {
        font-size: 14px;
      }
    }

    &_horizontal-skin {
      display: flex;
      align-items: center;
      border: 1px solid $grey-lighter;

      @include breakpoint($s) {
        padding: 20px 25px;
        height: 100px;
      }

      .#{$my-account-info-blk} {
        &__text {
          @include breakpoint($s) {
            font-size: 14px;
            margin-top: 6px;
          }
        }

        &__img {
          @include breakpoint($s) {
            width: 85px;
          }
        }
      }
    }

    &_combine-skin {
      @include breakpoint(max-width $m - 1px) {
        display: flex;
        align-items: center;
      }

      @include breakpoint($s) {
        padding: 12px 14px;
        height: 112px;
      }

      @include breakpoint($m) {
        height: 284px;
        min-width: 198px;
        padding-top: 30px;
        padding-bottom: 30px;
        text-align: center;
      }

      @include breakpoint($l) {
        height: 305px;
        padding-top: 40px;
      }

      .#{$my-account-info-blk} {
        &__img {
          @include breakpoint($m) {
            width: 140px; height: 110px;
            margin-left: auto;
            margin-right: auto;
            margin-bottom: 25px;
          }

          @include breakpoint($l) {
            width: 150px; height: 107px;
            margin-bottom: 30px;
          }
        }

        &__title {
          @include breakpoint($m) {
            margin-top: 19px;
          }
        }

        &__text {
          @include breakpoint($s $m - 1px) {
            margin-top: 2px;
          }
        }
      }
    }
  }

</style>