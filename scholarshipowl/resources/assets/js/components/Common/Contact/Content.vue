<template>
  <div class="contact-us-text">
    <Icon class="contact-us-text__icon" />
    <h4 class="contact-us-text__title">{{ title }}</h4>
    <p class="contact-us-text__text" v-html="text"></p>
  </div>
</template>

<script>
  import Icon from "components/Common/Contact/Icon.vue";

  export default {
    components: {
      Icon
    },
    props: {
      title: {type: String, required: true},
      text: {type: String, required: true}
    }
  }
</script>

<style lang="scss">
  .contact-us-text {
    @include breakpoint(max-width $l - 1px) {
      text-align: center;
    }

    &__icon {
      @include breakpoint(max-width $l - 1px) {
        margin-left: auto;
        margin-right: auto;
      }
    }

    &__title {
      @extend %plans-basic-title-text;
      font-size: 28px;
      margin-top: 7px;

      @include breakpoint($m) {
        font-size: 24px;
      }

      @include breakpoint($l) {
        font-size: 32px;
      }
    }

    &__text {
      @extend %plans-basic-paragraph-text;
      font-size: 15px;

      @include breakpoint($m) {
        font-size: 18px;
      }

      margin-top: 10px;

      a {
        color: $cornflower-blue;
      }

      a:hover {
        text-decoration: underline;
      }
    }
  }
</style>