<template>
  <div class="item-wrapper">
    <div class="has-text-centered">
      <div class="outer-circle">
        <div class="logo">
          <img
            v-show="image === 'monitor'"
            class="monitor-icon"
            src="~/assets/img/monitor_icon.svg"
            alt="icon"
          >

          <img
            v-show="image === 'user'"
            class="user-icon"
            src="~/assets/img/user_icon.svg"
            alt="icon"
          >

          <img
            v-show="image === 'lock'"
            class="lock-icon"
            src="~/assets/img/lock_icon.svg"
            alt="icon"
          >

          <img
            v-show="image === 'free'"
            class="free-icon"
            src="~/assets/img/free_icon.svg"
            alt="icon"
          >
        </div>
      </div>
    </div>

    <div class="item-text">
      <h5 class="feature-title">
        {{ title }}
      </h5>

      <div class="description">
        {{ desc }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'SunNeedItem',

  props: {
    image: {
      type: String,
      required: true,
    },

    title: {
      type: String,
      default: '',
    },

    desc: {
      type: String,
      default: '',
    },
  },
};
</script>

<style lang="scss" scoped>
@import '../../assets/css/_variables.scss';
@import '../../assets/css/_mixins.scss';

$circle-mobile: 85px;
$circle-tablet: 100px;
$circle-widescreen: 125px;
$circle-hd: 160px;
$circle-fullhd: 184px;

.item-wrapper {
  border: 2px solid $gray-border;
  background-color: $white;
  border-radius: 15px;
  padding: 30px;
  height: 100%;
  cursor: pointer;
  transition: box-shadow 0.2s ease;

  @include sun-tablet {
    padding: 40px;
  }

  @include sun-desktop {
    padding: 32px 42px;
  }

  @include sun-widescreen {
    padding: 38px 56px;
  }

  @include sun-hd {
    padding: 49px 73px;
  }

  &:hover {
    box-shadow: 0px 10px 40px rgba(0, 0, 0, 0.15);
  }

  .outer-circle {
    position: relative;
    z-index: 1;
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 6% auto auto auto;
    background-color: rgba(229, 140, 140, 0.5);
    border-radius: 50%;
    height: calc(#{$circle-mobile} + 25px);
    width: calc(#{$circle-mobile} + 25px);

    &:before,
    &:after {
      content: '';
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      width: 115%;
      height: 115%;
      border-radius: 50%;
      background-color: rgba(246, 225, 225, 0.4);
      z-index: -1;
    }

    &:after {
      background-color: transparent;
      border: 1px solid $light;
      width: 130%;
      height: 130%;
    }

    @include sun-tablet {
      height: calc(#{$circle-tablet} + 15px);
      width: calc(#{$circle-tablet} + 15px);
    }

    @include sun-widescreen {
      height: calc(#{$circle-widescreen} + 18px);
      width: calc(#{$circle-widescreen} + 18px);
    }

    @include sun-hd {
      height: calc(#{$circle-hd} + 50px);
      width: calc(#{$circle-hd} + 50px);
    }

    @include sun-fullhd {
      height: calc(#{$circle-hd} + 50px);
      width: calc(#{$circle-hd} + 50px);
    }
  }

  .logo {
    background: linear-gradient(33.25deg, #fe7f56 12.17%, #f7455e 85.53%),
      linear-gradient(180deg, #eb6568 0%, #d73148 100%);
    width: $circle-mobile;
    height: $circle-mobile;
    border-radius: 50%;
    display: flex;
    justify-content: center;
    align-items: center;

    @include sun-tablet {
      width: $circle-tablet;
      height: $circle-tablet;
    }

    @include sun-widescreen {
      width: $circle-widescreen;
      height: $circle-widescreen;
    }

    @include sun-hd {
      width: $circle-hd;
      height: $circle-hd;
    }

    @include sun-hd {
      width: $circle-fullhd;
      height: $circle-fullhd;
    }

    .monitor-icon {
      width: 50%;
    }

    .user-icon,
    .free-icon {
      width: 65%;
    }

    .lock-icon {
      width: 40%;
    }
  }

  .item-text {
    margin-top: 25px;
    text-align: center;

    @include sun-tablet {
      margin-top: 35px;
      text-align: left;
    }

    @include sun-tablet {
      margin-top: 35px;
    }

    @include sun-widescreen {
      margin-top: 45px;
    }

    @include sun-hd {
      margin-top: 50px;
    }

    @include sun-fullhd {
      margin-top: 55px;
    }
  }

  .description {
    font-family: 'Catamaran-Light';
    color: $light-gray;

    @include sun-tablet {
      line-height: 24px;
      font-size: 18px;
    }

    @include sun-widescreen {
      width: 85%;
    }

    @include sun-fullhd {
      margin-top: 25px;
    }
  }

  .feature-title {
    @include sun-tablet {
      margin-bottom: 5px;

      line-height: 25px;
      font-size: 20px;
    }

    @include sun-desktop {
      line-height: 25px;
      font-size: 20px;
      margin-bottom: 7px;
    }
  }
}
</style>
