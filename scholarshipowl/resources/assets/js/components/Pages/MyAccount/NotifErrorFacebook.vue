<template>
  <section class="notif-error-facebook">
    <p class="notif-error-facebook__text">{{ message }}</p>
  </section>
</template>

<script>
  export default {
    props: {
      message: {type: String, required: true}
    }
  }
</script>

<style lang="scss">
  $font-family: 'Open Sans';
  $pink: #f49a9a;
  $pink-darker: #a53d3d;

  .notif-error-facebook {
    background-color: $pink;
    padding: 15px;

    @include breakpoint($s) {
      padding: 15px 25px;
    }

    @include breakpoint($m) {
      padding: 20px 25px;
    }

    &__text {
      font-family: 'Open Sans';
      font-size: 14px;
      line-height: percentage(19px / 14px);
      color: $pink-darker;

      max-width: 960px;
      margin-left: auto;
      margin-right: auto;

      @include breakpoint($s) {
        font-size: 16px;
        line-height: percentage(22px / 16px);
      }
    }
  }
</style>