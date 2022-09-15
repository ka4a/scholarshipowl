<template>
  <div class="mail-area">
    <header class="mail-area__top mail-area__wrp">
      <p class="mail-area__name">{{ currentMail.subject }}</p>
      <p class="mail-area__date">{{ dateFormat(currentMail.date.date, 'MM/DD/YY') }}</p>
      <p class="mail-area__email">{{ currentMail.email }}</p>
    </header>
    <div v-if="currentMail.body" class="mail-area__body-wrp">
      <iframe class="mail-area__body mail-area__wrp" :src="'/mailbox/' + currentMail.emailId" ref="iframe" frameborder="0" />
    </div>
  </div>
</template>

<script>
import { dateFormat } from "lib/utils/format";
  export default {
    props: {
      currentMail: {type: Object, required: true},
    },
    methods: {
      dateFormat(dateString) {
        return dateFormat(dateString, 'MM/DD/YY');
      }
    }
  }
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

  $font-family: 'Open Sans';
  $dark: #2F2F2F;
  $grey: #C2C2C2;
  $grey-light: #E4E4E4;
  $white: #ffffff;

  %main-font {
    font-size: 14px;
    font-family: $font-family;
    line-height: 1.4em;
    color: $dark;

    @include breakpoint($s) {
      font-size: 18px;
    }
  }

  .mail-area {
    @include flexbox();
    @include flex-direction(column);
    height: 100%;

    &__top {
      @include flexbox();
      @include flex-wrap(wrap);
      border-bottom: 1px solid $grey-light;
      padding: 30px 0;
      min-height: 91px;

      @include breakpoint($s) {
        padding: 25px 0 20px 0;
      }

      @include breakpoint($l) {
        padding-top: 0;
        min-height: 72px;
      }
    }

    &__name {
      @extend %main-font;
      @include flex(1 1 50%);

      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }

    &__date {
      @extend %main-font;
      @include flex(1 1 50%);
      text-align: right;
    }

    &__email {
      font-size: 12px;
      line-height: 1.25em;
      font-family: $font-family;
      color: $grey;
      @include flex(2 2 100%);
      margin-top: 10px;
    }

    &__body-wrp {
      flex: 1 1 100%;
      display: flex;
      -webkit-overflow-scrolling: touch;
      overflow-y: auto;
    }

    &__body {
      width: 100%;
      height: 100%;
    }
  }
</style>