<template>
  <header class="header-register">
    <div class="header-register__wrp base-hor-indent">
      <div class="header-register__text-wrp">
        <h3 class="header-register__title title-reg" v-html="title"></h3>
        <h4 class="header-register__sub-title sub-title-reg" v-html="subTitle"></h4>
        <slot />
      </div>
      <div class="header-register__img-wrp">
        <img class="header-register__img" :src="imageSrc">
      </div>
    </div>
  </header>
</template>

<script>
  export default {
    props: {
      imageSrc: {type: String, required: true},
      title: {type: String, required: true},
      subTitle: {type: String, required: true},
    },
  }
</script>

<style lang="scss">
  .header-register {
    display: flex;
    align-items: center;
    padding-top: 20px;
    padding-bottom: 20px;
    overflow: hidden;
    background-color: $white;

    @include breakpoint($s) {
      padding-top: 25px;
      padding-bottom: 25px;
    }

    @include breakpoint($m) {
      padding-top: 30px;
      padding-bottom: 30px;
    }

    @include breakpoint($l) {
      padding-top: 35px;
      padding-bottom: 30px;
    }

    &__wrp {
      position: relative;
      width: 100%;

      @include breakpoint($m) {
        display: flex;
      }
    }

    &__text-wrp {
      @include breakpoint(max-width $m - 1px) {
        position: relative;
        z-index: 1;
      }

      @include breakpoint($s $m - 1px) {
        display: flex;
        flex-direction: column;
        min-height: 130px;
      }

      @include breakpoint($m) {
        flex: 1 1 auto;
        display: flex;
        flex-direction: column;
        justify-content: center;
      }
    }

    &__sub-title {
      margin-top: 12px;

      @include breakpoint($m) {
        margin-top: 8.5px;
      }
    }

    &__img-wrp {
      @include breakpoint(max-width $m - 1px) {
        position: absolute;
      }

      @include breakpoint($m) {
        width: auto;
        right: 0;
        top: 0; bottom: 0;
        margin-top: auto;
        margin-bottom: auto;
      }

      &:before {
        @include breakpoint(max-width $m - 1px) {
          content: "";
          position: absolute;
          left: 0; right: 0;
          top: 0; bottom: 0;
        }
      }
    }

    &__img {
      display: block;
      width: 100%;
    }

    &.register {
      .header-register__img-wrp {
        @include breakpoint(max-width $s - 1px) {
          right: -80px;
          bottom: -61px;
          width: 360px;
        }

        @include breakpoint($s $m - 1px) {
          right: -65px;
          bottom: -66px;
          width: 427px;
        }

        @include breakpoint($m) {
          max-width: 404px;
        }
        @include breakpoint($l) {
          max-width: 468px;
        }

        @include breakpoint($xl) {
          max-width: 488px;
        }

        &:before {
          @include breakpoint(max-width $m - 1px) {
            background-image: radial-gradient(
              400px,
              white,
              transparent
            );
          }

          @include breakpoint($s $m - 1px) {
            background-image: radial-gradient(
              circle 600px at 90px 0,
              white 15%,
              transparent
            )
          }
        }
      }
    }

    &.register2 {
      .header-register__text-wrp {
        @include breakpoint(max-width $m - 1px) {
          display: flex;
          flex-direction: column;
          min-height: 96px;
          justify-content: space-around;
        }

        @include breakpoint($s $m - 1px) {
          min-height: 100px;
        }

        @include breakpoint($l) {
          min-height: 204px;
        }
      }

      .header-register__title {
        @include breakpoint($xl) {
          font-size: 42.6px;
        }
      }

      .header-register__sub-title {
        @include breakpoint($s $m - 1px) {
          font-size: 18px;
        }

        @include breakpoint($xl) {
          font-size: 32px;
        }
      }

      .header-register__img-wrp {
        @include breakpoint(max-width $m - 1px) {
          right: -30px;
          bottom: -57px;
          width: 284px;
          height: 156px;

          > img {
            height: 100%;
          }
        }

        @include breakpoint($s $m - 1px) {
          bottom: -45px;
          right: -36px;
        }

        @include breakpoint($m) {
          max-width: 320px;
        }

        @include breakpoint($l) {
          max-width: 380px;
        }

        @include breakpoint($xl) {
          max-width: 467px;
          width: 467px;
        }

        &:before {
          @include breakpoint(max-width $s - 1px) {
            background-image: radial-gradient(
              circle 450px at 140px -10px,
              white,
              transparent
            )
          }

          @include breakpoint($s $m - 1px) {
            background-image: radial-gradient(
              circle 500px at 20px 0,
              white 10%,
              transparent
            )
          }
        }
      }
    }

    &.register3 {
      .header-register__title {
        @include breakpoint(max-width $s - 1px) {
          width: 75%;
        }
      }

      .header-register__text-wrp {
        min-height: 128px;

        @include breakpoint($s $m - 1px) {
          min-height: 99px;
        }
      }

      .header-register__sub-title {
        @include breakpoint($s $m - 1px) {
          font-size: 18px;
          margin-top: 15px;
        }

        @include breakpoint($l) {
          font-size: 24px;
          margin-top: 18px;
        }

        @include breakpoint($xl) {
          font-size: 32px;
          margin-top: 15px;
        }
      }

      .header-register__img-wrp {
        @include breakpoint(max-width $m - 1px) {
          right: -140px;
          bottom: -26px;
          width: 314px;
        }

        @include breakpoint($s $m - 1px) {
          right: -89px;
          bottom: -29px;
          width: 308px;
        }

        @include breakpoint($m) {
          max-width: 320px;

          > img {
            min-height: 176px;
          }
        }

        @include breakpoint($l) {
          max-width: 380px;

        }

        @include breakpoint($xl) {
          max-width: 500px;
        }

        &:before {
          @include breakpoint(max-width $s - 1px) {
            background-image: radial-gradient(
              circle 250px at 100px 0px,
              white,
              transparent
            )
          }

          @include breakpoint($s $m - 1px) {
            background-image: radial-gradient(
              circle 300px at 150px 0px,
              white,
              transparent
            )
          }
        }
      }
    }
  }
</style>