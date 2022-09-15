<template lang="html">
  <ul class="winner-time-line">
    <li v-for="year in years" :key="year">
      <a :class="['ctrl-m winner-time-line__year', {'ctrl-active': selectedYear === year}]"
        href="#" @click.prevent="selectYear(year)">{{ year }}</a>
      <transition name="down">
        <ul v-if="year === selectedYear">
          <li v-for="month in months" :key="month">
            <a href="#" @click.prevent="selectMonth(month)" :class="['ctrl-s winner-time-line__month', {'ctrl-active': selectedMonth === month}]">{{ month }}</a>
          </li>
        </ul>
    </transition>
    </li>
  </ul>
</template>

<script>

export default {
  props: {
    years: {type: Array, required: true},
    months: {type: Array, required: true},
    selectedYear: {type: Number, required: true},
    selectedMonth: {type: String, required: true},
    selectYear: {type: Function, required: true},
    selectMonth: {type: Function, required: true}
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  $black: #2f2f2f;
  $warm-grey: #9b9b9b;
  $pinkish-grey: #c9c9c9;
  $iris: #506fc6;

  $open-sans: 'Open Sans';

  %hover-reset {
    &:hover {
      color: $iris;
      text-decoration: none !important;
    }
  }

  .winner-time-line {
    padding-bottom: 30px;
    border-right: 1px solid $pinkish-grey;
    display: inline-block;

    &__year {
      display: block;
      margin-top: 20px;
      position: relative;

      &:before {
        display: block;
        position: absolute;
        content: '';
        width: 9px;
        height: 9px;
        background-color: $warm-grey;
        border-radius: 50%;
        right: -5px; top: 0.45em;
      }
    }

    &__month {
      display: block;
      margin-top: 12px;
    }
  }

  .ctrl-s {
    font-family: $open-sans;
    font-size: 16px;
    line-height: 1.375em;
    font-weight: 300;
    letter-spacing: 0.1px;
    color: $black;

    @include breakpoint($m) {
      font-size: 18px;
    }

    @extend %hover-reset;
  }

  .ctrl-m {
    font-family: $open-sans;
    font-size: 20px;
    line-height: 1.35em;
    font-weight: 300;
    letter-spacing: 0.1px;
    color: $warm-grey;

    @include breakpoint($m) {
      font-size: 24px;
    }

    @include breakpoint($l) {
      font-size: 26px;
    }

    @extend %hover-reset;
  }

  .ctrl-active {
    color: $iris;
    font-size: 20px;

    @include breakpoint($m) {
      font-size: 24px;
    }

    @include breakpoint($l) {
      font-size: 26px;
    }

    &:focus {
      color: $iris;
      text-decoration: none  !important;
    }

    &:hover {
      text-decoration: none !important;
    }

    &:before {
      background-color: $iris;
    }
  }

  // animation
  .down-enter {
    max-height: 0;
  }

  .down-enter-active {
    overflow: hidden;
    transition: max-height 600ms;
  }

  .down-enter-to {
    max-height: 420px;
  }
</style>
