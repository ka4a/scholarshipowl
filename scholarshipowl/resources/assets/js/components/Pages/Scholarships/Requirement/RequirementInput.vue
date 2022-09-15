<template lang="html">
  <div class="input-requirement">
    <input @input="ev => text = ev.target.value" :value="text"
      :placeholder="placeholder" class="input-requirement__input" type="text">
    <button @click="$emit('save', {text})" class="input-requirement__btn">
      <span v-if="xs || s || m" class="icon icon-save"></span><span v-else>save</span>
    </button>
  </div>
</template>

<script>
import { mapGetters } from "vuex";

export default {
  props: {
    placeholder: {type: String, required: true},
    inputText:   {type: String, required: true}
  },
  data() {
    return {
      text: ""
    }
  },
  mounted() {
    this.text = this.inputText;
  },
  computed: {
    ...mapGetters({
      xs: "screen/xs",
      s: "screen/s",
      m: "screen/m",
    })
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/mixins';

$light-grey: #C2C2C2;
$white: #FFFFFF;
$blue: #708FE7;
$black: #2F2F2F;

.input-requirement {
    @include flexbox();
    @include justify-content(space-between);
    margin-top: 20px;

    @include breakpoint($s) {
      margin-top: 25px;
    }

    @include breakpoint($m) {
      margin-top: 30px;
    }

    &__input {
      font-size: 12px;
      background: $white;
      border: 1px solid $blue;
      box-sizing: border-box;
      border-radius: 2px;
      height: 40px;
      padding-left: 15px;
      padding-right: 15px;
      @include flex-grow(1);

      display: block;
      outline: none;

      @include placeholder-color($light-grey);

      &:focus {
        border-color: darken($blue, 10)
      }

      @include breakpoint($s) {
        font-size: 14px;
      }
    }

    .icon-save {
      color: $blue;
      font-size: 27px;
    }

    &__btn {
      // font style
      font-family: "Open Sans", sans-serif;
      font-weight: bold;
      color: $black;
      text-transform: uppercase;
      height: 40px; width: 50px;
      background: $white;
      border: 1px solid $blue;
      line-height: 40px;
      text-align: center;
      border-radius: 2px;
      margin-left: 20px;
      cursor: pointer;

      &:hover {
        border-color: darken($blue, 10)
      }

      @include breakpoint($m) {
        width: 131px;
        font-size: 12px;
      }
    }
  }
</style>
