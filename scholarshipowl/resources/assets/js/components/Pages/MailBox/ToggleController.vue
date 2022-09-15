<template>
  <a class="toggle-label">
    <span :class="['toggle-label__text', {'toggle-label__text_open': isOpen},
      {'toggle-label__text_disable': isDisable}]">{{ labelName }}</span>
    <i :class="['toggle-indicator toggle-label__indicator', {'toggle-label__indicator_open' : isOpen},
    {'toggle-label__indicator_disable': isDisable}]"></i>
  </a>
</template>

<script>
  export default {
    props: {
      labelName: {type: String, required: true},
      isOpen: {type: Boolean, default: false},
      isDisable: {type: Boolean, default: false}
    }
  }
</script>

<style lang="scss">
@import 'style-gide/mixins';

  // variables
  $blue: #708FE7;
  $dark: #2F2F2F;
  $grey: #b9b9b9;

  .toggle-label {
    cursor: pointer;
    &:hover {
      text-decoration: none;
    }

    &__text {
      line-height: 40px;
      color: $dark;
      font-size: 14px;
      margin-right: 5px;
      display: inline-block;
      text-transform: capitalize;

      &_open {
        color: $blue;
      }

      &_disable {
        color: $grey;
      }
    }

    &__indicator {
      &_open {
        @include angle(bottom, 5px, $blue);
        transform: rotate(180deg);
      }

      &_disable {
        @include angle(bottom, 5px, $grey);
      }
    }
  }
</style>