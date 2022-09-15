<template>
  <p style="display: inline-block; width: 100%; position: relative">
    <input
      :type="type"
      :name="name"
      :class="['input-text', {'input-text_error': error}]"
      v-bind="$attrs"
      :value="value"
      :disabled="disabled"
      @input="input"
      @blur="() => { $emit('blur'); togglePlaceholder(); }"
      @focus="() => { $emit('focus'); togglePlaceholder(); }"
      :placeholder="placeholderDinamic"/>
    <svg class="input-text__icon" v-if="disabled === 'disabled'" width="10" height="12" viewBox="0 0 10 12" fill="none" xmlns="http://www.w3.org/2000/svg">
      <path d="M9.24999 4.5H8.49999V3.49999C8.49999 1.57008 6.92994 0 5 0C3.07006 0 1.50001 1.57008 1.50001 3.49999V4.5H0.750008C0.61182 4.5 0.5 4.61182 0.5 4.75001V11C0.5 11.5515 0.948477 12 1.50001 12H8.50002C9.05152 12 9.5 11.5515 9.5 11V4.75001C9.5 4.61182 9.38818 4.5 9.24999 4.5ZM5.74855 9.72241C5.75635 9.79296 5.73366 9.86377 5.6863 9.91676C5.63893 9.96973 5.57105 10 5.50002 10H4.50001C4.42897 10 4.36109 9.96973 4.31373 9.91676C4.26636 9.86379 4.24365 9.79298 4.25148 9.72241L4.40919 8.30421C4.15309 8.11793 4.00002 7.82325 4.00002 7.5C4.00002 6.94849 4.44849 6.49999 5.00002 6.49999C5.55155 6.49999 6.00003 6.94847 6.00003 7.5C6.00003 7.82325 5.84696 8.11793 5.59086 8.30421L5.74855 9.72241ZM6.99999 4.5H3.00001V3.49999C3.00001 2.39721 3.89722 1.5 5 1.5C6.10278 1.5 6.99999 2.39721 6.99999 3.49999V4.5Z" fill="#CCCCCC"/>
    </svg>
  </p>
</template>

<script>
  import { placeholderMixin } from "components/Common/Input/mixins";
  import { debounce } from "lib/utils/utils";

  export default {
    name: 'InputText',
    props: {
      placeholder: {type: String, default: ''},
      type:        {type: String, default: "text"},
      format:      {type: Function, default: val => val},
      error:       {type: String},
      name:        {type: String},
      value:       {type: String, required: true},
      disabled:    {type: String}
    },
    mixins: [placeholderMixin],
    methods: {
      input(ev) {
        let valueFormat = this.format(ev.target.value);
        this.$el.value = valueFormat;
        this.value = valueFormat;
        this.$emit('input', valueFormat);

        debounce(() => {
          this.$emit('validate');
        }, 2000)
      },
      togglePlaceholder() {
        if(this.placeHolder) {
          this.placeHolder();
        }
      }
    }
  }
</script>

<style lang="scss">
  .input-text {
    @extend %input-text-basic;
    @extend %input-style-basic;

    &__icon {
      position: absolute;
      top: 0; bottom: 0;
      right: 20px;
      margin: auto;
    }

    &::placeholder {
      color: $silver;
    }

    &_error {
      border-color: $carnation;

      // &::placeholder {
      //   color: $carnation;
      // }
    }

    &:focus {
      @extend %input-style-basic_focus;
    }

    &:disabled {
      color: #999999;
      background-color: #F6F6F6;
      border-color: #e8e8e8;
      cursor: not-allowed;
    }
  }
</style>