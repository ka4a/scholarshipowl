<template>
  <div class="sun-input">
    <input
      ref="input"
      :type="type"
      :class="[variant, {active: inputValue.length > 0}]"
      :required="required"
      @input="onInput"
      @blur="onBlur"
    >

    <span :class="variant" class="bar"/>

    <span class="highlight"/>

    <label :class="variant">
      {{ label }}
    </label>

    <div
      :class="{visible: isBlur && !isInputValid}"
      class="error"
    >
      {{ errorMsg }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'SunInput',

  props: {
    value: {
      type: null,
      required: true,
    },

    required: {
      type: Boolean,
      default: false,
    },

    label: {
      type: String,
      required: true,
    },

    variant: {
      type: String,
      default: '',
    },

    type: {
      type: String,
      default: 'text',
    },

    match: {
      type: String,
      default: '',
    },

    errors: {
      type: Object,
      default: () => ({
        required: 'Input is required.',
        type: 'Wrong input. Try again.',
        match: 'Passwords doesn\'t match',
      }),
    },
  },

  data() {
    return {
      inputValue: '',
      isBlur: false,
    };
  },

  computed: {
    isInputValid() {
      if (this.type === 'email') {
        const regexp = /\S+@\S+\.\S+/;

        return regexp.test(String(this.inputValue).toLowerCase());
      }

      if (this.match) {
        return this.match === this.inputValue;
      }

      if (this.type === 'password') {
        return this.inputValue.length > 5;
      }

      return this.inputValue.length > 0;
    },

    errorMsg() {
      if (this.required && this.inputValue.length === 0) {
        return this.errors.required || 'Input is required';
      }

      if (this.type === 'email') {
        const regexp = /\S+@\S+\.\S+/;

        if (this.inputValue.length === 0) {
          return 'Type your e-mail';
        }

        if (!regexp.test(String(this.inputValue).toLowerCase())) {
          if (this.errors.type) {
            return this.errors.type;
          }
        }
      } else if (this.type === 'password') {
        if (this.inputValue.length === 0) {
          return this.errors.required || 'Password is required';
        }

        if (this.match && this.match !== this.inputValue) {
          if (this.errors.match) {
            return this.errors.match;
          }
        }

        if (this.inputValue.length < 6) {
          return 'The password must be at least 6 characters.';
        }
      }

      return '';
    },
  },

  methods: {
    onInput() {
      this.inputValue = this.$refs.input.value;
      this.$emit('input', this.$refs.input.value);
    },

    onBlur() {
      this.isBlur = true;
    },

    validate() {
      this.isBlur = true;
      return this.isInputValid;
    },
  },
};
</script>

<style lang="scss" scoped>
@import '~assets/css/_variables.scss';
@import '~assets/css/_mixins.scss';

$bg-color: $white;
$muted-color: mix(white, $bg-color, 70%);
$trans-time: 300ms;
$width: 100%;

*,
:before,
:after {
  box-sizing: border-box;
}

.sun-input {
  position: relative;
  margin-bottom: 15px;
}

input {
  background: none;
  color: $muted-color;
  padding: 10px 10px 10px 0;
  display: block;
  width: $width;
  border: none;
  border-radius: 0;
  border-bottom: 1px solid $muted-color;
  font-family: 'Catamaran-Light';
  font-size: 20px;

  &.dark {
    color: $dark-color;
    border-bottom: 1px solid $middle-gray;
  }

  @include sun-widescreen {
    font-size: 24px;
  }

  @include sun-hd {
    font-size: 32px;
  }

  &:focus {
    outline: none;
  }

  &:focus ~ label,
  &.active ~ label {
    top: -14px;
    font-size: 13px;

    @include sun-widescreen {
      font-size: 15px;
    }

    @include sun-hd {
      font-size: 20px;
    }
  }

  &:focus ~ .bar:before {
    width: $width;
  }
}

input[type="password"] {
  letter-spacing: 0.3em;
}

label {
  color: $muted-color;
  font-size: 20px;
  font-weight: normal;
  position: absolute;
  pointer-events: none;
  left: 0;
  top: 10px;
  transition: $trans-time ease all;

  &.dark {
    color: $dark-color;
  }

  @include sun-widescreen {
    font-size: 24px;
  }

  @include sun-hd {
    font-size: 32px;
  }
}

.bar {
  position: relative;
  display: block;
  width: $width;
  &:before {
    content: '';
    height: 1px;
    width: 0;
    bottom: 0px;
    position: absolute;
    background: $red;
    transition: $trans-time ease all;
    left: 0%;
  }
}

.error {
  opacity: 0;
  text-align: left;
  font-size: 13px;
  min-height: 24px;
  padding: 5px 0;
  transition: all $trans-time ease;

  @include sun-widescreen {
    font-size: 15px;
  }

  @include sun-hd {
    font-size: 19px;
  }

  &.visible {
    opacity: 1;
  }
}
</style>
