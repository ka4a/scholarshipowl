<template>
  <div class="field"
    :class="{
     'field__focused': focused,
     'has-error': !!error,
     'has-label': !!label,
    }">
    <div class="control">
      <label v-if="label" class="label" :class="{ 'label__small': focused || !!newValue }">
        {{ label }}
      </label>
      <div class="input-wrapper">
        <masked-input
          v-if="mask"
          ref="input"
          class="input"
          :type="type"
          :value="newValue"
          v-bind="$attrs"
          mask="\+\1 (111) 111-1111"
          @input="onMaskedInput"
          @blur="onBlur"
          @focus="onFocus" />
        <input
          v-else
          ref="input"
          class="input"
          :type="type"
          :value="newValue"
          v-bind="$attrs"
          @input="onInput"
          @blur="onBlur"
          @focus="onFocus" />
      </div>
    </div>
    <i class="bar" :class="{ 'bar__red': focused || !!error }" />
    <p v-if="error" class="help is-danger">{{ error }}</p>
  </div>
</template>

<script>
import MaskedInput from 'vue-masked-input/src/MaskedInput';

export default {
  name: 'BrandInput',

  components: {
    MaskedInput
  },

  props: {
    type: {
      type: String,
      default: 'text'
    },
    label: String,
    error: String,
    mask: String,
  },
  data: function() {
    return {
      focused: false,
      newValue: null,
    }
  },
  watch: {
    /**
     * When v-model is changed:
     *   1. Set internal value.
     *   2. If it's invalid, validate again.
     */
    value: {
      immediate: true,
      handler(val) {
        this.newValue = val;
      },
    },
    /**
     * Update user's v-model and validate again whenever
     * internal value is changed.
     */
    newValue(value) {
        this.$emit('input', value)
        // !this.isValid && this.checkHtml5Validity()
    }
  },
  methods: {
    onInput(event) {
      this.$nextTick(() => { this.newValue = event.target.value })
    },
    onMaskedInput(value) {
      this.$nextTick(() => { this.newValue = value });
    },
    onFocus() {
      this.focused = true;
    },
    onBlur() {
      this.$emit('blur');
      this.focused = false;
    },
  }
}
</script>
<style lang="scss" scoped>
.field {
  position: relative;
  margin: 0 0 10px 0;

  .control {
    position: relative;
    height: 58px;

    .input-wrapper {
      position: absolute;
      bottom: 0;
      right: 0;
      left: 0;

      height: 42px;

      transition: all ease-in-out .3s;
    }

    .input {
      display: block;

      background: none;
      border: none;
      box-shadow: none;
      -webkit-appearance: none;
      padding: 0;

      font-size: 18px;

      &.has-error {
        color: #D73148;
      }

      &:-webkit-autofill,
      &:-webkit-autofill:hover,
      &:-webkit-autofill:active,
      &:-webkit-autofill:focus {
        -webkit-animation-name: autofill;
        -webkit-animation-fill-mode: both;
      }
    }

    .label {
      position: absolute;
      top: 16px;
      left: 0;

      font-size: 20px;
      font-weight: 300;
      color: #606060;

      transition: all ease-in-out .3s;

      &__small {
        font-size: 13px;
        top: 8px;
      }
    }
  }

  .help {
    margin-top: 5px;
  }

  .bar {
    width: 100%;
    display: block;
    border-bottom: 1px solid #999999;

    &__red {
      border-color: #D73148;
    }
  }

  &.has-error {
    .label {
      color: #ff3860;
      left: 20px;
    }
    .control {
      background: #F6D6D6;
      border-radius: 6px;

      .input {
        color: #D73148;
      }

      .input-wrapper {
        left: 20px;
        right: 20px;
      }
    }
    .bar {
      display: none;
    }
  }

  @-webkit-keyframes autofill {
      to {
          color: #000;
          background: transparent;
      }
  }
}
</style>
