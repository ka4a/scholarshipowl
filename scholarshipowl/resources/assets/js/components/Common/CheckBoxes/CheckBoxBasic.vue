<template>
  <p :class="['checkbox-basic', {'checkbox-basic_error': error}]">
    <input class="checkbox-basic__input" :id="id || name" type="checkbox"
           :name="name" :checked="value" :disabled="disabled" @change="v => $emit('input', v.target.checked)">
    <label class="checkbox-basic__wrp" tabindex="0" :for="id || name">
      <span class="checkbox-basic__checkbox"></span>
      <span v-if="$slots.label" class="checkbox-basic__text">
        <slot name="label" />
      </span>
    </label>
  </p>
</template>

<script>
  export default {
    name: "CheckBoxBasic",
    props: {
      value: {type: Boolean, default: false},
      name: {type: String, required: true},
      error: {type: Boolean, default: false},
      disabled: {type: Boolean, default: false},
      id: {type: String}
    },
    mounted() {
      let checkbox = this.$el.querySelector(".checkbox-basic__wrp");

      checkbox.addEventListener("keydown", ev => {
        let keyCode = ev.keyCode || ev.which;

        if(keyCode === 32) {
          ev.preventDefault();
          checkbox.click();
        }
      })
    }
  }
</script>

<style lang="scss">
  $grey: #9f9f9f;
  $grey-darker: #919daf;
  $white: #ffffff;
  $blue-darker: #495d7b;
  $dark: black;

  .checkbox-basic {
    &__input {
      display: none;
    }

    &__wrp {
      display: flex;
      @extend %tap-reset;
      cursor: pointer;

      &:focus {
        outline: none;

        .checkbox-basic__checkbox {
          box-shadow: 0 1px 3px 0 rgba(168, 168, 168, 0.5);
        }
      }
    }

    &__input:checked + label .checkbox-basic__checkbox {
      &:before {
        width: 6px;
        height: 12px;
        border: solid $dark;
        transform: rotate(45deg);
        border-width: 0 3px 3px 0;
        content: "";
        display: block;
        margin-left: auto;
        margin-right: auto;
        margin-top: 2px;
      }
    }

    &__input:disabled + label {
      cursor: default;

      .checkbox-basic__checkbox {
        background-color: $black-squeeze;
        border-color: $mystic;
        box-shadow: none;
      }
    }

    &__input:checked:disabled + label .checkbox-basic__checkbox {
      &:before {
        border-color: rgba(47, 217, 179, 0.4) !important;
      }
    }

    &__checkbox {
      display: block;
      min-width: 25px; min-height: 25px;
      width: 25px; height: 25px;
      border: 1px solid $grey;
      border-radius: 1px;
      background-color: $white;
      box-sizing: border-box;

      margin-right: 15px;
    }

    &__text {
      font-family: "Open Sans";
      font-size: 12px;
      line-height: 1.93em;
      color: $grey-darker;
      @extend %tap-reset;

      a {
        color: $blue-darker;
        font-weight: 600;
      }
    }

    &_error {
      .checkbox-basic__checkbox {
        border-color: $carnation;
      }
    }
  }
</style>