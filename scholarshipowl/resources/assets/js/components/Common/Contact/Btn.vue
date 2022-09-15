<template>
  <button class="contact-us-btn">
    <span class="contact-us-btn__text">{{text}}</span>
    <Loader class="contact-us-btn__loader" size="s" v-if="isLoading" />
  </button>
</template>

<script>
  import Loader from "components/Common/Buttons/BtnLoader.vue";

  export default {
    components: {
      Loader
    },
    props: {
      text: {type: String, required: true},
      isLoading: {type: Boolean, default: false}
    }
  }
</script>

<style lang="scss">
  .contact-us-btn {
    background-color: $turquoise;
    height: 50px;
    border-radius: 1px;
    width: 100%;
    transition: all 200ms;
    display: flex;
    justify-content: center;
    align-items: center;

    @include breakpoint($m) {
      max-width: 186px;
    }

    @include breakpoint($l $xl - 1px) {
      max-width: 148px;
    }

    &:hover {
      background-color: darken($turquoise, 5);
    }

    &:active {
      transform: translateY(2px);
    }

    &__text {
      color: $white;
      font-size: 15px;
      font-weight: bold;
      text-transform: uppercase;
    }

    &__loader {
      margin-left: 5px;
    }
  }
</style>