<template>
  <section class="search-bar">
    <input @input="inputHolder" :value="filtered" class="search-bar__input search-bar__input-text" type="text" name="search-bar">
    <i class="search-bar__icon icon icon-search"></i>
  </section>
</template>

<script>

export default {
  props: {
    nameSpace: { required: true, type: String },
    filterBy: { required: true, type: String },
    moduleName: { required: true, type: String },
    focusInput: { type: Boolean, default: false }
  },
  methods: {
    inputHolder(ev) {
      this.$store.dispatch('list/setFilterParam', {
        nameSpace: this.nameSpace,
        filterBy: this.filterBy,
        parameter: ev.target.value
      });

      this.$store.dispatch('list/applyFilter', this.nameSpace);

      this.$emit('filter', ev.target);
    }
  },
  computed: {
    filtered() {
      let value = this.$store.state[this.moduleName][this.nameSpace].filter[this.filterBy];

      return value;
    }
  }
};
</script>

<style lang="scss">

@import 'style-gide/breakpoints';
@import 'main/meta/flex-box';
@import 'style-gide/functions';

  $blue-light: #F3F8FF;
  $blue: #708FE7;
  $dark: #354C6D;

  // TODO remove imports afer moving to Vue.js side.

  .search-bar {
    position: relative;

    &__input {
      box-sizing: border-box;
      height: 35px !important;
      width: 100%;
      padding: 0 62px 0 10px !important;
      background-color: $blue-light !important;
      border: none !important;
      outline: none;

      @include breakpoint($s) {
        height: 40px !important;
      }
    }

    &__input-text {
      font-size: 12px !important;
      color: $dark !important;
      font-family: "Open Sans" !important;
      line-height: 1.2em !important;
    }

    &__icon {
      position: absolute;
      font-size: 18px;
      height: 18px;
      color: $blue;
      top: 0; bottom: 0;
      right: 21px;
      margin-top: auto;
      margin-bottom: auto;
    }
  }
</style>