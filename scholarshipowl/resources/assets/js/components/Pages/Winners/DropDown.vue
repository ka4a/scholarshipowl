<template>
  <multi-select class="winners-drop-down" :options="options" :value="value" @select="value => select(value)" :showPointer="false" :searchable="false" selectLabel="" deselectLabel="" selectedLabel=""/>
</template>

<script>
import MultiSelect from "vue-multiselect";

export default {
  props: {
    options: {type: Array, required: true},
    select: {type: Function, required: true},
    value: {type: String, required: true}
  },
  components: {
    MultiSelect
  }
};
</script>

<style lang="scss">
  .winners-drop-down {
    .multiselect {
      &__tags {
        border: none;
      }

      &__single {
        font-size: 18px;
      }

      &__select {
        &:before {
          border-color: #000 transparent;
        }
      }

      &__content-wrapper {
        border: none;
        border-bottom: solid 1px #e0e0e0;
        background-color: #f7f7f8;
        border-radius: 0;
      }

      &__element {
        border-top: solid 1px #e0e0e0;
      }

      &__option {
        font-size: 14px;
        min-height: 30px;
        padding-top: 8px;
        padding-bottom: 8px;
      }
    }
  }
</style>