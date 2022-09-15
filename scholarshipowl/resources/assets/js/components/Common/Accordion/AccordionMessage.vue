<template>
  <p :class="['accordion-message', {'accordion-message_fail': !isValid}]">
    <Icon class="accordion-message__icon" :name="iconName" />
    <span class="accordion-message__text">{{ message }}</span>
  </p>
</template>

<script>
  import Icon from "components/Common/Icons/IconRound.vue";

  export default {
    components: {
      Icon
    },
    props: {
      message: {type: String, default: undefined},
      isValid: {type: Boolean, default: false}
    },
    computed: {
      iconName() {
        return this.isValid ? "success" : "fail"
      },
    }
  }
</script>

<style lang="scss">
  // global variables
  // assets/sass/style-gide/palette/_variables.scss
  // $carnation
  // $mine-shaft

  .accordion-message {
    display: flex;
    align-items: center;

    &__icon {
      margin-right: 15px;
    }

    &__text {
      font-size: 15px;
      color: $mine-shaft;
      line-height: 1.2em;
    }

    &_fail {
      .accordion-message__text {
        color: $carnation;
      }
    }
  }
</style>