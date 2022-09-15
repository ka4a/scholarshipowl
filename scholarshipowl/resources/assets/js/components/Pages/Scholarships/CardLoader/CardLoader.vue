<template>
  <div class="card-loader">
    <p class="card-loader__text" v-if="message">{{ message }}</p>
    <p class="card-loader__img-wrp"><img class="card-loader__img" src="./loader.gif"></p>
  </div>
</template>

<script>
export default {
  props: {
    message: { type: String }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

$grey: #C2C2C2;

.card-loader {
  @include flexbox();
  @include justify-content(center);
  @include align-items(center);

  box-sizing: border-box;

  &__img-wrp {
    margin-left: 10px;
    width: 30px;
  }

  &__img {
    display: block;
    width: 100%;
  }

  &__text {
    font-size: 12px;
    color: $grey;

    @include breakpoint($m) {
      font-size: 14px;
    }
  }
}
</style>
