<template lang="html">
  <section :class="[`scholarship-notif scholarship-notif-${name}`]">
    <div class="scholarship-notif__img-wrp">
      <img v-if="notification.imageName" class="scholarship-notif__img" :src="imagePath">
    </div>

    <p class="scholarship-notif-message scholarship-notif__message" v-html="notification.message"></p>

    <a v-if="notification.controller && notification.controller.style !== 'button'"
       class="scholarship-notif-notif scholarship-notif__notif" href="#"
      @click.prevent="action(notification.controller.action); $emit('track')" v-html="notification.controller.text"></a>

    <p v-if="notification.notification" class="scholarship-notif-notif scholarship-notif__notif" v-html="notification.notification"></p>
    <p v-if="notification.text" class="scholarship-notif-text scholarship-notif__text" v-html="notification.text"></p>

    <template v-if="notification.controller && notification.controller.style === 'button'">
      <a v-if="notification.controller.action === 'EXTERNAL'" :href="notification.controller.link"
      @click="$emit('track')" :target="notification.controller.inNewTab ? '_blank' : '_self'"
      class="scholarship-notif-btn scholarship-notif__btn">{{ notification.controller.text }}</a>
      <a v-else href="#" @click.prevent="action(notification.controller.action); $emit('track')"
      class="scholarship-notif-btn scholarship-notif__btn">{{ notification.controller.text }}</a>
    </template>
  </section>
</template>

<script>

import failureImage from "./failureImage";
import { mapActions } from "vuex";

export default {
  props: {
    name: {type: String, required: true},
    notification: {type: Object, required: true}
  },
  computed: {
    imagePath() {
      window.router = this.$router;

      return this.notification.imageName === "failure"
        ? failureImage
        : "/assets/img/notif-" + this.notification.imageName + "." + this.notification.imageFormat;
    },
  },
  methods: {
    action(action) {
      this.$emit('action', action);
    },
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/functions';

$dark: #2F2F2F;
$white: #FFFFFF;
$orange: #FF6633;
$grey: #C2C2C2;
$blue: #708FE7;

.scholarship-notif {
  &-message {
    color: $dark;
    font-size: 18px;
    line-height: lhem(25, 18);

    @include breakpoint($s) {
      font-size: 20px;
    }
  }

  &-notif {
    color: $dark;
    font-size: 21px;
    line-height: lhem(29, 21);
    font-weight: 700;
    text-transform: capitalize;

    @include breakpoint($s) {
      font-size: 23px;
    }

    @include breakpoint($m) {
      font-size: 25px;
    }
  }

  &-text {
    color: $dark;
    // font-size: 13px;
    font-size: 11px;
    line-height: lhem(18, 13);
    text-align: center;

    @include breakpoint($s) {
      font-size: 13px;
    }
  }

  &-btn {
    color: $white;
    font-size: 14px;
    line-height: 50px;
    text-align: center;
    text-transform: uppercase;
    font-weight: 700;
    display: block;

    background-color: $orange;
    width: 250px;
    height: 50px;
    border-radius: 2px;

    &:hover {
      background-color: darken($orange, 5);
    }

    @include breakpoint($m) {
      font-size: 16px;
      border-radius: 4px;
    }
  }
}

.scholarship-notif {
  text-align: center;
  margin-left: auto;
  margin-right: auto;

  &__img {
    display: block;
    width:100%;
  }

  &__img-wrp {
    width: 185px;
    margin-left: auto;
    margin-right: auto;

    @include breakpoint($s) {
      width: 228px;
    }
  }

  &__message {
    // margin-top: 20px;
    margin-top: 5px;

    @include breakpoint($s) {
      p { display: inline }
    }
  }

  &__notif {
    // margin-top: 10px;
    display: block;

    span {
      color: $blue;
      cursor: pointer;
    }

    @include breakpoint($m) {
      margin-top: 15px;

      p { display: inline }
    }
  }

  &__text {
    @include breakpoint($s) {
      margin-top: 15px;
    }
  }

  // modificators
  $prefix : 'scholarship-notif';

  &-no-favourites {
    .scholarship-notif__img-wrp {
      width: 115px;

      @include breakpoint($s) {
        width: 180px;
      }
    }
  }

  &-no-new {
    @include breakpoint($s) {
      max-width: 406px;
    }

    @include breakpoint($l) {
      max-width: 100%;
    }

    .scholarship-notif__img-wrp {
      width: 205px;

      @include breakpoint($s) {
        width: 306px;
      }
    }
  }

  &-no-requirements,
  &-winner-chosen,
  &-missed {
    .#{$prefix} {
      &__message {
        margin-top: 20px;

        @include breakpoint($s) {
          margin-top: 30px;
        }
      }

      &__notif {
        margin-top: 13px;

        @include breakpoint($s) {
          margin-top: 15px;
        }
      }

      &__text {
        margin-top: 15px;
      }
    }
  }

  &-awarded,
  &-won {
    .#{$prefix} {
      &__message {
        margin-top: 0;

        @include breakpoint($s) {
          margin-top: 10px;
        }
      }

      &__notif {
        margin-top: 13px;

        @include breakpoint($s) {
          margin-top: 15px;
        }
      }

      &__text {
        margin-top: 15px;
      }
    }
  }

  &__btn {
    margin-top: 30px;
    margin-left: auto;
    margin-right: auto;
  }
}

</style>