<template>
  <textarea :class="['input-textarea', {'input-textarea_error': error}]"
    @focus="ev => $emit('focus', ev)"
    @input="ev => $emit('input', ev.target.value)">
  </textarea>
</template>

<script>
  export default {
    props: {
      value: {type: String, required: true},
      error: {type: Boolean, default: false}
    }
  }
</script>

<style lang="scss">
  .input-textarea {
    @extend %input-text-basic;
    @extend %input-style-basic;
    max-width: 100%;
    min-width: 100%;

    &:focus {
      @extend %input-style-basic_focus;
    }

    &_error {
      border-color: $carnation;

      // &::placeholder {
      //   color: $carnation;
      // }
    }
  }
</style>