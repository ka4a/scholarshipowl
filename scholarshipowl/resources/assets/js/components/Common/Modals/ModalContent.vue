<template>
  <div class="vue-modal-content">
    <h3 v-if="title" class="vue-modal-content__title">{{ title }}</h3>
    <div v-if="html && !text" v-html="html"></div>
    <p v-if="text && !html" v-for="paragraph in text"
      class="vue-modal-content__paragraph" v-html="paragraph"></p>
  </div>
</template>

<script>
  export default {
    props: {
      title: {type: String},
      text: {type: Array},
      html: {type: String}
    }
  }
</script>

<style lang="scss">
  .vue-modal-content {
    h3, h5, &__title {
      font-family: $font-family-basic;
      font-size: 18px;
      font-weight: bold;
      letter-spacing: 0.1px;
      text-align: center;
      line-height: 1.41em;
      color: $mine-shaft;

      margin-top: 15px;
      margin-bottom: 14px;

      @include breakpoint($s) {
        font-size: 21.3px;
        line-height: 1.36em;

        margin-top: 25px;
        margin-bottom: 16px;
      }

      @include breakpoint($m) {
        font-size: 24px;

        margin-top: 30px;
      }

      @include breakpoint($l) {
        font-size: 25px;
      }
    }

    p, &____paragraph {
      font-family: $font-family-basic;
      font-size: 12px;
      line-height: 1.4em;
      text-align: center;
      color: $mine-shaft;
      margin-bottom: 0;

      @include breakpoint($s) {
        font-size: 14px;
      }

      & + & {
        margin-top: 15px;
      }

      a {
        color: $havelock-blue;
      }
    }

    b {
      font-weight: 700;
    }

    i {
      font-style: italic;
    }
  }
</style>