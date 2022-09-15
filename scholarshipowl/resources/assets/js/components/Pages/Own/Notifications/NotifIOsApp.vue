<template>
  <section :style="{width: !open && !animating ? '66px' : '100%'}" class="notif-ios-app">
    <ctrl-arrow-round v-if="!animating" @click.native="toggleNotif" :open="open" />
    <transition name="notif-bottom"
      @before-enter="() => {animating = true}"
      @after-enter="() => {animating = false}"
      @before-leave="() => {animating = true}"
      @after-leave="() => {animating = false}">
      <div v-if="open" class="notif-ios-app__wrp">
        <div class="notif-ios-app__img">
          <img src="./logo-ios-app.png" alt="download scholarshipowl IOs app" />
        </div>
        <div class="notif-ios-app__text-holder">
          <h4 class="notif-ios-app__title">ScholarshipOwl iOS App</h4>
          <p class="notif-ios-app__text">Apply on the go and get updates, download ScholarshipOwl app today!</p>
        </div>
        <a class="notif-ios-app__btn" target="_blank"
          href="https://itunes.apple.com/us/app/scholarshipowl/id1447107897?ls=1&mt=8">DOWNLOAD</a>
      </div>
    </transition>
  </section>
</template>

<script>
  import CtrlArrowRound from "components/Pages/Own/Ctrls/CtrlArrowRound.vue"

  const NOTIFICATION_NAME = 'notif-ios-open';

  export default {
    created() {
      if(window.localStorage && window.localStorage.getItem(NOTIFICATION_NAME)) {
        this.open = window.localStorage.getItem(NOTIFICATION_NAME) === 'true'
      }
    },
    components: {
      CtrlArrowRound
    },
    data() {
      return {
        open: true,
        animating: false
      }
    },
    methods: {
      toggleNotif() {
        this.open = !this.open;
        window.localStorage.setItem(NOTIFICATION_NAME, this.open.toString());
      }
    }
  }
</script>

<style lang="scss">
@import 'main/meta/reset';

@import 'style-gide/breakpoints';
@import 'style-gide/animation';

  $font-family-main: 'Open Sans';

  $light: #FFFFFF;
  $dark: #2F2F2F;
  $green: #2FD9B3;
  $grey: #F1F5F8;

  .notif-ios-app {
    position: absolute;
    bottom: 50px;
    z-index: 1000;
    width: 100%;
    min-height: 66px;
    overflow: hidden;
    right: 0;

    &__wrp {
      display: flex;
      flex-wrap: wrap;
      background: $grey;
      box-shadow: 0px -2px 3px rgba(0, 0, 0, 0.25);
      padding: 12px 15px;

      @include breakpoint($s) {
        padding-left: 25px;
        padding-right: 25px;
      }

      @include breakpoint($m) {
        padding-right: 68px;
        align-items: center;
        padding-left: 15px;
      }

       @include breakpoint($l) {
        padding-right: 88px;
      }
    }

    &__text-holder {
      @include breakpoint(max-width $m - 1px) {
        width: 73%;
      }
    }

    &__img {
      width: 60px;
      height: 60px;
      min-width: 60px;
      min-height: 60px;
      border-radius: 5px;
      overflow: hidden;
      margin-right: 15px;

      > img {
        display: block;
        width: 100%;
      }
    }

    &__title {
      font-family: $font-family-main;
      font-size: 14px;
      line-height: percentage(19px / 14px);
      font-weight: 700;
      color: $dark;

      @include breakpoint(max-width $s - 1px) {
        max-width: 60%;
      }

      @include breakpoint(max-width $m - 1px) {
        max-width: 65%;
      }

      @include breakpoint($s) {
        font-size: 16px;
      }
    }

    &__text {
      font-family: $font-family-main;
      font-size: 12px;
      line-height: percentage(16px / 12px);
      color: $dark;

      margin-top: 7px;

      @include breakpoint($s) {
        margin-top: 5px;
      }

      @include breakpoint($s max-width $m - 1px) {
        max-width: 70%;
      }
    }

    &__btn {
      font-family: $font-family-main;
      font-size: 14px;
      line-height: 30px;
      color: $light;
      text-align: center;
      text-transform: uppercase;
      font-weight: 700;
      cursor: pointer;

      &:hover {
        background-color: darken($green, 5);
      }

      width: 126px;
      height: 30px;
      background-color: $green;
      display: block;

      @include breakpoint(max-width $m - 1px) {
        margin-left: 75px;
        margin-top: 12px;
      }

      @include breakpoint($m) {
        margin-left: auto;
      }
    }
  }
</style>