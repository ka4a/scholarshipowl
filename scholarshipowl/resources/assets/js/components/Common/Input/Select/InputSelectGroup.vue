<template>
  <div class="input-select-group"
    v-click-outside="{
      exclude: ['addCollageBtn'],
      handler: 'removeNotFilled',
      skipIfTargetRemoved: true
    }">
    <div v-for="(item, index) in data"
      :key="item"
      class="input-select-group__item">
      <span class="input-select-group__icon css-icon-cross"
        v-if="data.length > min"
        @click="removeItem(index)"></span>
      <select-item
        :class="{'input-left-indent': isMoreThanMin && !isItemOpen(index)}"
        name="university"
        :value="item"
        :error="!item && error && index < min"
        @select="val => setItem(val, index)"
        @tag="val => setItem(formatTagItem(val), index)"
        @open="id => openHolder(id, index)"
        @close="(val, id) => closeHolder(val, id)"
        :selected-values="selectedValues"
        :placeholder="placeholder" />
    </div>
    <btn-out-line
      ref="addCollageBtn"
      v-if="data.length < max"
      text="+ Add college"
      @click.native="addItem"
      class="input-select-group__btn" />
  </div>
</template>

<script>
  import { isEqual }                 from "lodash";
  import { strArrayToLableValueObj } from "components/Pages/MyAccount/SubTabs/initialInputDataFormaters";
  import SelectItem                  from "components/Common/Input/Select/InputSelectDinamic.vue";
  import BtnOutLine                  from "components/Common/Buttons/ButtonOutLine.vue";

  /**
   * Input (value prop) data normalization.
   * @param  {Null|StringArray} data whihc pass from parent component
   * @param  {Number} min  count of select fields
   * @param  {Number} max  count of select fields
   * @return {Array} array of strings, null values
   */
  const normalizeData = (data, min, max) => {
    if(!data) {
      data = [];
    }

    if(data.length < min) {
      let diff = min - data.length;

      while(diff) {
        data.push(null)
        diff -= 1;
      }

      return data;
    }

    if(data.length > max) {
      return data.slice(0, max);
    }

    return data;
  }

  const formatInitData = (data, min, max) => normalizeData(strArrayToLableValueObj(data), min, max);

  export default {
    props: {
      min:          {type: Number, default: 3},
      max:          {type: Number, default: 5},
      value:        {type: Array, default: []},

      placeholder:  {type: String},
      error:        {type: Boolean, default: false}
    },
    components: {
      SelectItem,
      BtnOutLine
    },
    created() {
      this.data = formatInitData(this.value, this.min, this.max);
    },
    data() {
      return {
        data: [],
        openItemIndex: undefined
      }
    },
    computed: {
      isMoreThanMin() {
        return this.data.length > this.min;
      },

      /**
       * Format and filter selected values from array of objects label, value
       * to array of string values (label) [{label: "collage": value: 213}, null] => ["collage"]
       * @return {Array} array of strings
       */
      selectedValues() {
        return this.data.filter(item => item).map(item => item.label)
      }
    },
    watch: {
      // data: array of strings only
      value(val) {
        if(!val || isEqual(this.selectedValues, val)) return;

        this.data = formatInitData(val, this.min, this.max);
      }
    },
    methods: {
      emitValue() {
        this.$emit("input", this.selectedValues);
      },
      /**
       * set selected item to data and emit value
       * @param {Object} val   object in format {label: collegeName:String, value: id:Number}
       * @param {Number} index representation position of select component
       */
      setItem(val, index) {
        this.data.splice(index, 1, val);

        this.emitValue();
      },
      /**
       * add null value to the end of this.data. It will lounch adding
       * new select item to representation.
       * @return {undefined}
       */
      addItem() {
        if(this.data.length >= this.max) return;

        this.data.push(null);
      },
      /**
       * remove item from this.data, and triggers emit value
       * @param  {Number} index of item which need to remove
       * @return {undefined}
       */
      removeItem(index) {
        if(this.data.length <= this.min) return;

        this.data.splice(index, 1);

        this.emitValue();
      },
      openHolder(id, itemIndex) {
        this.openItemIndex = itemIndex;

        this.$emit("open", id);
      },
      closeHolder(val, id) {
        this.openItemIndex = undefined;

        this.$emit("close", val, id);
      },
      formatTagItem(tag) {
        return {
          label: tag,
          value: tag,
          choosen: false
        }
      },
      isItemOpen(index) {
        return index === this.openItemIndex;
      },
      removeNotFilled() {
        let data = this.data.filter(item => item);

        while(this.min > data.length) {
          data.push(null);
        }

        this.data = data;
      }
    }
  }
</script>

<style lang="scss">
  $red: #ed5858;

  .input-left-indent {
    .multiselect__placeholder,
    .multiselect__input,
    .multiselect__single {
      padding-left: 30px !important;
    }
  }

  .multiselect--error {
    .css-icon-cross {
      &:before,
      &:after {
        background-color: $red;
      }
    }
  }

  .css-icon-cross {
    right: 0;
    top: 10px;
    right: 13px;
  }

  .css-icon-cross {
    @include css-icon('plus', (
      height: 21px,
      width: 21px,
      color: #616161,
      line-width: 1px));
    transform: rotate(45deg);
  }

  .input-select-group {
    &__item {
      position: relative;

      & + & {
        margin-top: 12px;
      }
    }

    &__icon {
      position: absolute;
      top: 0; bottom: 0;
      left: 10px;
      padding: 5px;
      margin-top: auto;
      margin-bottom: auto;
      z-index: 1;
    }

    &__btn {
      margin-top: 5px;

      @include breakpoint($m) {
        margin-top: 10px;
      }
    }

    .input-select-group__item {
      .multiselect__content-wrapper {
        z-index: 2;
      }
    }


  }
</style>