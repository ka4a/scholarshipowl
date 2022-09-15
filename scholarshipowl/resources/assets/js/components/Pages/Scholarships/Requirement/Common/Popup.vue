w<template lang="html">
  <div ref="tih" class="popup-wrapper">
    <section class="popup-wrapper__frame">
      <div class="popup-wrapper__wrp">
        <h2 v-if="title" class="popup-wrapper__title">{{ title }}</h2>
        <slot name="text"></slot>
        <button @click="$emit('button')" v-if="buttonText" class="popup-wrapper__btn">{{ buttonText }}</button>
      </div>
    </section>
  </div>
</template>

<script>
export default {
  name: "RequirementTextInputPopup",
  props: {
    title: {type: String, required: true},
    buttonText: {type: String, required: true},
  },
  mounted() {
    this.initialHeight = this.$refs.tih.clientHeight;
    let parent = document.querySelector(".right-container");
    parent.style.overflow = "hidden";
  },
  destroyed() {
    // TODO fast hack reimplement it!
    let parent = document.querySelector(".right-container");
    parent.style.overflow = "auto";
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';

.popup-wrapper {
  $blue: #708FE7;
  $blue-light: #F3F8FF;
  $blue-dark: #4E8EEC;
  $dark: #2F2F2F;
  $grey: #D8D8D8;
  $dark-grey: #797979;

  font-size: 14px;
  line-height: 1.42em;
  color: $dark;
  background-color: white;
  width: 100%;
  top: 0; left: 0;
  position: fixed;
  z-index: 9999;

  @include breakpoint($s $l - 1px) {
    background-color: #F3F8FF;
  }

  @include breakpoint(max-width $l - 1px) {
    bottom: 0;
    overflow-y: auto;
    min-height: 0;
  }

  @include breakpoint($l) {
    background-color: rgba(53, 76, 109, 0.25);
    @include flexbox();
    @include align-items(center);
    height: 100%;
  }

  &__frame {
    overflow: auto;
    -webkit-overflow-scrolling: touch;
    padding: 0 15px;
    background-color: white;
    box-sizing: border-box;
    @include flexbox();
    @include flex-direction(column);
    height: 100%;

    @include breakpoint($s) {
      margin: 25px;
      box-shadow: 0px 2px 8px rgba(0, 0, 0, 0.07);
    }

    @include breakpoint($l) {
      width: 60%;
      height: auto;
      max-height: 66%;
      max-width: 890px;
      margin-left: auto;
      margin-right: auto;
      padding: 0 25px;
    }
  }

  &__wrp {
    margin-top: 15px;
    margin-bottom: 25px;
    overflow: hidden;
    @include flexbox();
    @include flex-direction(column);
    height: 100%;

    @include breakpoint($l) {
      margin-top: 25px;
      margin-bottom: 25px;
      @include flex(1 1 auto);
    }
  }

  &__btn {
    margin-top: 20px;
    @include align-self(flex-end);
    color: white;
    text-transform: uppercase;
    text-align: center;
    font-size: 12px;
    font-weight: 700;
    width: 118px;
    height: 30px;
    min-height: 30px;
    line-height: 30px;
    background-color: $blue-dark;

    &:hover {
      background-color: darken($blue-dark, 5);
    }

    .icon {
      color: $grey;
      margin-right: 5px;
      vertical-align: middle;
      font-size: 24px;
    }
  }

  &__title {
    color: $blue;
    font-size: 16px;
    font-weight: 700;
  }
}

</style>
