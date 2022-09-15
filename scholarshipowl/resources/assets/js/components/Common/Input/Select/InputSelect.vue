<template>
  <multi-select
    :class="[
      'input-select-basic',
      {'input-select-basic_error': error}
    ]"
    v-bind="$attrs"
    v-on="$listeners"
    @open="openHolder"
    @close="closeHolder"
    :placeholder="placeholder"
    :open-direction="openDirection"
    :selectLabel="selectLabel"
    :deselectLabel="deselectLabel"
    :selectedLabel="selectedLabel"
    :tagPlaceholder="tagPlaceholder"
    :options-limit="optionsLimit"
    :max-height="maxHeight"
    :allow-empty="allowEmpty"
    :value="value"
    :option-height="optionHeight"
    :searchable="searchable">
    <input-select-option
      slot="option"
      slot-scope="props"
      :props="props" />
    <span slot="noOptions">Start type to search</span>
  </multi-select>
</template>

<script>
  import MultiSelect       from "vue-multiselect";
  import InputSelectOption from "components/Common/Input/Select/InputSelectOption.vue"

  const APPROXIMATE_LETTER_WIDTH = 6.7;

  export default {
    name: 'input-select',
    methods: {
      setInputType() {
        if(!this.searchable) return;

        let inputTargets = this.$el.querySelectorAll("input");

        if(!inputTargets.length || !this.type) return;

        inputTargets.forEach(input => {
          input.type = this.type;
        })
      },
      closeHolder(val) {
      },
      openHolder(val) {
        this.setInputType();
      },
    },
    mounted() {
      this.setInputType();
    },
    components: {
      MultiSelect,
      InputSelectOption
    },
    props: {
      placeholder:    {type: String, default: "— Select —"},
      openDirection:  {type: String, default: "bottom"},
      selectLabel:    {type: String, default: ""},
      deselectLabel:  {type: String, default: ""},
      selectedLabel:  {type: String, default: ""},
      tagPlaceholder: {type: String, default: ""},
      optionsLimit:   {type: Number, default: 150},
      maxHeight:      {type: Number, default: 200},
      allowEmpty:     {type: Boolean, default: false},
      searchable:     {type: Boolean, default: false},
      error:          {type: Boolean, default: false},
      value:          {type: String, required: true},
      optionHeight:   {type: Number, default: 50},
      type:           {type: String, default: 'text'}
    },
  }
</script>

<style lang="scss">
  $multiselect:     'multiselect';
  $blue-more-light: rgba(148, 171, 206, 0.5);
  $grey:            $silver;

  %vertical-align {
    margin-bottom: 0;
    display: table-cell;
    vertical-align: middle;
    line-height: 1em;
  }

  .input-select-basic {
    .#{$multiselect} {
      border: none;

      &__tags {
        border-radius: 1px;
        padding: 0 30px 0 15px;
        border-color: $geyser;
        display: table;
        width: 100%;
        height: 50px;
      }

      &__tag {
        height: 32px;
        border-radius: 1.5px;
        background-color: #f1f5f8;
        padding: 9px 40px 6px 15px;
        margin-bottom: 0;
        margin-top: 5px;
        margin-right: 12px;

        &-icon {
          &:hover {
            background: #f1f5f8;
          }

          &:after {
            content: none;
          }

          @include css-icon('cross', (
            height: 13px,
            width: 13px,
            color: #616161,
            line-width: 1px));
          margin: auto;
          right: 10px;
        }

        span {
          font-family: $font-family-basic;
          font-size: 14px;
          color: $dove-gray;
        }
      }

      &__tags-wrap {
        margin-top: 3px;
      }

      &__input {
        @extend %input-text-basic;
        padding: 14px 0;
        margin-bottom: 0;
        -webkit-tap-highlight-color: transparent;

        &::placeholder {
          color: $silver;
        }
      }

      &__placeholder {
        @extend %vertical-align;
        font-size: 16px;
        -webkit-tap-highlight-color: transparent;
      }

      &__single {
        @extend %vertical-align;
        @extend %input-text-basic;
        font-size: 15px;
        padding-left: 0;
        -webkit-tap-highlight-color: transparent;
      }

      &__select {
        height: auto;
        top: 0; bottom: 0;
        &:before {
          border-color: #555 transparent;
          border-width: 5px 4px 0;
        }
      }

      &__content {
        display: block !important;
      }

      &__content-wrapper {
        border-radius: 0 0 2px 2px;
        box-shadow: 0 1px 2px 0 rgba(53, 76, 109, 0.53);
        border-color: $blue-more-light;
        border-style: solid;
        border-width: 0 1px 1px 1px;
        max-height: 200px !important;
      }

      &__element {
        border: none;

        &:before {
          content: none;
        }
      }

      &__option {
        @extend %input-text-basic;
        font-size: 15px;
        padding: 9px 15px;
        height: 50px;
        white-space: normal;
        line-height: 1em;
        width: 100%;
        display: table;

        > span {
          display: table-cell;
          vertical-align: middle;
        }

        &--selected {
          background-color: white;
          color: $silver;
          font-weight: normal;
        }

        &--highlight {
          background-color: $onahau;
        }
      }
    }

    // active state
    &.multiselect--active {
      border: none;
      box-shadow: none;
      z-index: 2;

      .multiselect__tags {
        border-color: $havelock-blue;
        border-radius: 2px 2px 0 0;
      }
    }

    // error state
    &_error {
      .#{$multiselect} {
        &__tags {
          border-color: $carnation;
        }
      }
    }
  }

  .multiselect-option_choosen {
    display: block;
    color: $grey;
    margin: -0.9em -15px;
    padding: 0.9em 15px;
  }
</style>