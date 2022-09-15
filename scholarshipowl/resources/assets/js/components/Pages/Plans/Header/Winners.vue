<template>
  <a class="winners-pp" href="/winners" target="_blank">
    <ul class="winners-pp__wrp">
      <li style="z-index: 3" class="winners-pp__winner"><img src="./Winners/winner-1.png"></li>
      <li style="z-index: 2" class="winners-pp__winner"><img src="./Winners/winner-2.png"></li>
      <li style="z-index: 1" class="winners-pp__winner"><img src="./Winners/winner-3.png"></li>
      <li class="winners-pp__winner"><img src="./Winners/winner-4.png"></li>
    </ul>
    <p class="winners-pp__text">Our latest scholarship winners.</p>
  </a>
</template>

<style lang="scss">
  .winners-pp {
    display: flex;
    align-items: center;
    justify-content: center;

    @include breakpoint($l) {
      justify-content: left;
    }

    &__wrp {
      display: flex;
      width: 80px;
      margin-right: 10px;

      @include breakpoint($m) {
        width: 136px;
      }
    }

    &__winner {
      position: relative;
      width: 26px;
      height: 26px;

      > img {
        display: block;
        width: 100%;
      }

      @include breakpoint($m) {
        width: 42px;
        height: 42px;
      }

      @include breakpoint($l) {
        &:hover {
          transform: translateY(-10%);
          z-index: 5 !important;
          transition: all 200ms;
        }
      }

      & + & {
        margin-left: -8px;

        @include breakpoint($m) {
          margin-left: -11px;
        }
      }
    }

    &__text {
      font-weight: 600;
      font-size: 12px;
      line-height: 127.5%;
      letter-spacing: -0.04em;
      color: $mine-shaft;

      @include breakpoint($m) {
        font-size: 16px;
      }
    }
  }
</style>