<template>
  <section :class="['mail-list-item',
    {'mail-list-item_read': item.isRead, 'mail-list-item_selected': item.emailId === currentMail.emailId}]">
    <h3 class="mail-list-item__subject">{{ item.subject }}</h3>
    <p class="mail-list-item__origin">
      <span>{{ item.folder === 'Inbox' ? 'From' : 'To' }}:</span> {{ item.email }}
    </p>
    <p class="mail-list-item__body">{{ truncate(item.clearBody) }}</p>
    <p class="mail-list-item__date">{{ dateFormat(item.date.date, 'MM/DD/YY') }}</p>
  </section>
</template>

<script>
import { dateFormat } from "lib/utils/format";
const textLengths = {
  small: 80,
  large: 180
}

export default {
  props: {
    item: {type: Object, required: true},
    currentMail: {type: Object, required: true}
  },
  methods: {
    truncate(string) {
      let maxLength = this.$store.getters['screen/l'] ? textLengths.large : textLengths.small;

      return string.length > maxLength
        ? string.slice(0, maxLength) + '(...)'
        : string;
    },
    dateFormat(dateString) {
      return dateFormat(dateString, 'MM/DD/YY');
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';

  $font-family: "Open Sans";

  $dark: #2F2F2F;
  $light-blue: #ACCAF6;
  $grey: #E4E4E4;
  $grey-darker: #C2C2C2;
  $blue-darker: #708FE7;
  $blue-lighter: #F3F8FF;

  %base-font {
    font-size: 12px;
    color: $dark;
    font-family: $font-family;
    line-height: 1.5em;
  }

  .mail-list-item {
    padding: 15px;
    position: relative;
    border-bottom: 1px solid $grey;
    min-height: 104px;
    box-sizing: border-box;
    cursor: pointer;

    @include breakpoint($m) {
      padding: 15px 25px;
    }

    @include breakpoint($l) {
      padding: 15px;
    }

    // modificators
    &_read {
      .mail-list-item {
        &__subject,
        &__date {
          color: $blue-darker;
        }

        &__body {
          color: $grey-darker;
        }
      }
    }

    &_selected {
      background-color: $blue-lighter;
    }

    // elements
    &__subject {
      font-family: $font-family;
      font-weight: 700;
      font-size: 14px;
      color: $dark;
      line-height: 1.5em;

      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;

      max-width: 70%;
    }

    &__origin {
      @extend %base-font;

      span {
        font-weight: 600;
      }
    }

    &__body {
      font-size: 12px;
      font-family: $font-family;
      color: $light-blue;
      height: 2.67em;

      margin-top: 5px;

      max-width: 87%;

      @include breakpoint($s) {
        max-width: 70%;
      }

      @include breakpoint($m) {
        max-width: 87%;
      }
    }

    &__date {
      @extend %base-font;

      position: absolute;
      right: 15px; top: 15px;

      @include breakpoint($m) {
        right: 25px;
      }

      @include breakpoint($l) {
        right: 15px;
      }
    }
  }
</style>