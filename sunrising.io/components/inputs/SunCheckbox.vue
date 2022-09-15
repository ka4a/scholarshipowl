<template>
  <label
    :class="type"
    class="sun-checkbox"
  >
    <span v-html="text"/>
    <a
      v-if="terms"
      href="javascript:;"
      class="terms-link"
      @click="$emit('termsOpen')"
    >
      Terms&amp;Conditions
    </a>

    <input
      ref="input"
      type="checkbox"
      @input="onChange"
    >
    <span class="checkmark"/>
  </label>
</template>

<script>

export default {
  name: 'SunCheckbox',

  props: {
    value: {
      type: null,
      required: true,
    },

    terms: {
      type: Boolean,
      default: false,
    },

    type: {
      type: String,
      default: 'basic',
    },

    text: {
      type: String,
      default: '',
    },
  },

  methods: {
    onChange() {
      this.$emit('input', this.$refs.input.checked);
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

.sun-checkbox {
  text-align: left;
  display: block;
  position: relative;
  padding-left: 45px;
  font-family: 'Catamaran-Light';
  cursor: pointer;
  font-size: 17px;
  height: 28px;
  color: $white;
  user-select: none;
  display: flex;
  align-items: center;

  @include sun-hd {
    font-size: 24px;
    height: 37px;
    padding-left: 59px;
  }

  .checkmark {
    position: absolute;
    top: 0;
    left: 0;
    height: 28px;
    width: 28px;
    background-color: transparent;
    border: 1px solid $white;
    border-radius: 3px;

    @include sun-hd {
      height: 37px;
      width: 37px;
    }

    &::after {
      content: "";
      position: absolute;
      display: none;
    }
  }

  input {
    position: absolute;
    opacity: 0;
    cursor: pointer;
    height: 0;
    width: 0;

    &:checked ~ .checkmark {
      border-color: $red;
    }

    &:checked ~ .checkmark::after {
      display: block;
      left: 9px;
      top: 5px;
      width: 8px;
      height: 13px;
      border: solid $red;
      border-width: 0 2px 2px 0;
      transform: rotate(45deg);

      @include sun-hd {
        left: 12px;
        top: 7px;
        width: 10px;
        height: 16px;
      }
    }
  }

  &:hover {
    .checkmark {
      border-color: $red;
    }
  }

  &.dark {
    color: $dark-color;

    .checkmark {
      border-color: $dark-color;
    }
  }

  .terms-link {
    text-decoration: underline;
    color: inherit;
    margin-left: 5px;
  }
}
</style>
